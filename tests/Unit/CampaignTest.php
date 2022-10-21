<?php

use App\Models\Customer;
use App\Models\PurchaseTransaction;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

// Availability Test
it('can ping the campaign availability route', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('campaign.availability'))
        ->assertOk();
});

// Eligibility Test
it('can ping the campaign eligibility route', function () {
    $admin = User::factory()->create();
    $customer = Customer::factory()->create();

    $this->actingAs($admin)
        ->post(route('campaign.eligibility'), [
            'customer_id' => $customer->id,
        ])
        ->assertOk();
});

// Eligibility Test with eligibile transactions
it('can lock a voucher if customer has eligible transactions', function () {
    $admin = User::factory()->create();
    $customer = Customer::factory()->create();
    Voucher::factory()->create([
        'status' => 'pending',
    ]);
    PurchaseTransaction::factory()->within30Days()->count(3)->create([
        'customer_id' => $customer->id,
        'total_spent' => 40.0,
    ]);

    $res = $this->actingAs($admin)
        ->postJson(route('campaign.eligibility'), [
            'customer_id' => $customer->id,
        ]);

    expect($res->json())->message->toBe('Customer is eligible for a voucher');
});

// Eligibility Test without eligibile transactions
it('can lock a voucher if customer does not have eligible transactions', function () {
    $admin = User::factory()->create();
    $customer = Customer::factory()->create();
    Voucher::factory()->create([
        'status' => 'pending',
    ]);
    PurchaseTransaction::factory()->within30Days()->count(3)->create([
        'customer_id' => $customer->id,
        'total_spent' => 10.0,
    ]);

    $res = $this->actingAs($admin)
        ->postJson(route('campaign.eligibility'), [
            'customer_id' => $customer->id,
        ]);

    expect($res->json())->message->toBe('Customer is not eligible for a voucher');
});

// Submission Test
it('can ping the campaign submission route', function () {
    $admin = User::factory()->create();
    $customer = Customer::factory()->create();
    Voucher::factory()->create([
        'status' => 'locked',
    ]);

    $this->actingAs($admin)
        ->post(route('campaign.submission'), [
            'customer_id' => $customer->id,
            'submission_image_path' => 'https://www.google.com',
        ])
        ->assertOk();
});
