<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('campaign')->group(function () {
        Route::get('/availability', [CampaignController::class, 'availability'])->name('campaign.availability');
        Route::post('/eligibility', [CampaignController::class, 'eligibility'])->name('campaign.eligibility');
        Route::post('/submission', [CampaignController::class, 'submission'])->name('campaign.submission');
    });

    // Customers
    Route::prefix('/customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('customer.show');
    });

    // Vouchers
    Route::prefix('/vouchers')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('voucher.index');
        Route::get('/{voucher_code}', [VoucherController::class, 'show'])->name('voucher.show');
    });

    Route::post('/upload-photo-submission', function () {
        // ideally this would be a controller method that handles the file upload to S3
        $file = request()->file('file');
        $dir = "public/images/submissions";
        $path = $file->store($dir);
        return response()->json(['path' => str_replace("$dir/", '', $path)]);
    });
});
