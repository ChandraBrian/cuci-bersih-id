<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = Service::create([
        'name' => 'Cuci Mobil Reguler',
        'price' => 50000.00,
        'duration_estimate' => 45,
        'description' => 'Standard car wash.',
        'is_active' => true,
    ]);

    $this->customer = Customer::factory()->create();

    $this->booking = Booking::withoutStatusProtection(fn() => Booking::create([
        'booking_code' => 'CB-PAY-123',
        'customer_id' => $this->customer->id,
        'service_id' => $this->service->id,
        'vehicle_type' => 'mobil',
        'vehicle_brand' => 'Toyota',
        'vehicle_model' => 'Avanza',
        'vehicle_color' => 'Hitam',
        'plate_number' => 'B 1234 CD',
        'booking_date' => now()->toDateString(),
        'status' => 'BOOKED',
    ]));

    $this->staffUser = User::factory()->create([
        'role' => 'staff',
    ]);
});

it('allows staff to create and record payments', function () {
    $paymentData = [
        'payment_method' => 'cash',
        'amount' => 50000.00,
    ];

    $this->actingAs($this->staffUser)
        ->post(route('payments.store', $this->booking), $paymentData)
        ->assertRedirect(route('bookings.show', $this->booking));

    $this->assertDatabaseHas('payments', [
        'booking_id' => $this->booking->id,
        'amount' => 50000.00,
        'payment_method' => 'cash',
        'payment_status' => 'paid',
    ]);
});

it('allows staff to verify pending payments', function () {
    $payment = Payment::create([
        'booking_id' => $this->booking->id,
        'amount' => 50000.00,
        'payment_method' => 'qris',
        'payment_status' => 'pending',
    ]);

    $this->actingAs($this->staffUser)
        ->patch(route('payments.verify', $payment))
        ->assertRedirect(route('bookings.show', $this->booking));

    expect($payment->fresh()->payment_status)->toBe('paid');
    expect($payment->fresh()->paid_at)->not->toBeNull();
});
