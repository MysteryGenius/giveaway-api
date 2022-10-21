<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

// Index Test
it('can ping the voucher index route', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('voucher.index'))
        ->assertOk();
});
