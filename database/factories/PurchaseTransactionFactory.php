<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseTransaction>
 */
class PurchaseTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'total_spent' => $this->faker->randomFloat(2, 0, 1000),
            'total_saving' => $this->faker->randomFloat(2, 0, 1000),
            'transaction_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
