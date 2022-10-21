<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PurchaseTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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

            Customer::factory()->create([
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
            ]);

            // Customer 1 has 3 transactions within 30 days
            PurchaseTransaction::factory()->within30Days()->count(3)->create([
                'customer_id' => 1,
                'total_spent' => 40.0,
            ]);

            Customer::factory()->create([
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
            ]);

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
