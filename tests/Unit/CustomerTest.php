<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

// Index Test
it('can ping the customer index route', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('customer.index'))
        ->assertOk();
});

// Show Test
it('can ping the customer show route', function () {
    $admin = User::factory()->create();
    $customer = Customer::factory()->create();

    $this->actingAs($admin)
        ->get(route('customer.show', $customer))
        ->assertOk();
});
