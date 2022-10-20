<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PurchaseTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() !== 'production') {
            Customer::factory()
                ->count(100)
                ->create()
                ->each(function ($customer) {
                    $customer->PurchaseTransactions()->saveMany(
                        PurchaseTransaction::factory()->times(random_int(2, 5))->make()
                    );
                });
        }
    }
}
