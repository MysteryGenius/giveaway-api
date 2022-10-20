<?php

namespace App\Http\Controllers;

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

        $total_amount = $customer->PurchaseTransactions->sum('total_spent');

        $eligible = $total_amount >= 100 ? true : false;

        if ($eligible) {
            // Locking down a voucher
            $voucher = Voucher::where('status', 'pending')->first();
            $voucher->status = 'locked';
            $voucher->customer_id = $customer->id;
            $voucher->save();
        }

        return response()->json([
            'eligible' => $total_amount >= 100 ? true : false,
        ]);
    }
}
