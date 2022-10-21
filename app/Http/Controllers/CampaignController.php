<?php

namespace App\Http\Controllers;

use App\Jobs\ReleaseVoucherLock;
use App\Models\Customer;
use App\Models\Voucher;

class CampaignController extends Controller
{
    public function availability()
    {
        // get count of vouchers with 'pending' status
        $pending = Voucher::where('status', 'pending')->count();

        return response()->json([
            'remaining' => $pending,
            'available' => $pending > 0 ? true : false,
        ]);
    }

    public function eligibility()
    {
        // check if customer exists
        request()->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = Customer::findOrFail(request()->customer_id)->load(['PurchaseTransactions' => function ($query) {
            // last 30 days
            $query->where('created_at', '>=', now()->subDays(30));
        }]);

        // check if customer already has a voucher
        $found = Voucher::where('customer_id', $customer->id)->first();
        if ($found) {
            return response()->json([
                'eligible' => false,
                'message' => 'Customer already has a voucher',
                'lock_expiry' => null,
            ]);
        }

        $total_amount = $customer->PurchaseTransactions->sum('total_spent');

        $eligible = $total_amount >= 100 ? true : false;
        $voucher = null;
        $lock_expiry = null;

        if ($eligible) {
            // Locking down a voucher
            $voucher = Voucher::where('status', 'pending')->first();
            $voucher->status = 'locked';
            $voucher->customer_id = $customer->id;
            $voucher->save();

            // dispatch job to release voucher lock after 10 minutes
            $lock_expiry = now()->addMinutes(10);
            ReleaseVoucherLock::dispatch($customer)->delay($lock_expiry);
        }

        return response()->json([
            'eligible' => $eligible,
            'message' => $eligible ? 'Customer is eligible for a voucher' : 'Customer is not eligible for a voucher',
            'lock_expiry' => $lock_expiry,
        ]);
    }

    public function submission()
    {
        // check if customer exists
        request()->validate([
            'customer_id' => 'required|exists:customers,id',
            'submission_image_path' => 'required',
        ]);


        $customer = Customer::findOrFail(request()->customer_id);
        $voucher = Voucher::where('customer_id', $customer->id)->where('status', 'locked')->first();

        if ($voucher) {
            // handle image upload to image recognition API
            // $client = new \GuzzleHttp\Client();
            // $res = $client->request('POST', 'http://example.com');

            // if ($res->getStatusCode() == 200) {}

            // update voucher status
            $voucher->status = 'active';
            $voucher->save();

            return response()->json([
                'success' => true,
                'message' => 'Voucher successfully activated',
                'voucher' => $voucher->only(['code']),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Voucher not found',
            ]);
        }
    }
}
