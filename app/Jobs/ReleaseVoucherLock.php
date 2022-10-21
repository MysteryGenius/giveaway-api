<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleaseVoucherLock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The customer instance.
     *
     * @var \App\Models\Customer
     */
    protected $customer;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // release voucher lock to pending if voucher is not active
        if ($this->customer->Voucher->status === 'locked') {
            $this->customer->Voucher->status = 'pending';
            $this->customer->Voucher->customer_id = null;
            $this->customer->Voucher->save();
        }
    }
}
