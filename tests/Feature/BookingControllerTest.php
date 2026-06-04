<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use App\Services\BookingStatusService;
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

    $this->adminUser = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->staffUser = User::factory()->create([
        'role' => 'staff',
    ]);

    $this->customerUser = User::factory()->create([
        'role' => 'customer',
    ]);
});

it('denies guest and customer access to bookings index', function () {
    $this->get(route('bookings.index'))->assertRedirect(route('login'));

    $this->actingAs($this->customerUser)
        ->get(route('bookings.index'))
        ->assertStatus(403);
});

it('allows admin and staff access to bookings index', function () {
    $this->actingAs($this->adminUser)
        ->get(route('bookings.index'))
        ->assertStatus(200);

    $this->actingAs($this->staffUser)
        ->get(route('bookings.index'))
        ->assertStatus(200);
});

it('creates a booking successfully and resolves customer', function () {
    $bookingData = [
        'customer_name' => 'Customer Baru',
        'customer_phone' => '081234567890',
        'customer_email' => 'baru@example.com',
        'service_id' => $this->service->id,
        'vehicle_type' => 'mobil',
        'vehicle_brand' => 'Honda',
        'vehicle_model' => 'Civic',
        'vehicle_color' => 'Putih',
        'plate_number' => 'D 9999 AB',
        'booking_date' => now()->addDay()->toDateString(),
        'notes' => 'Tolong dicuci bersih bagian dalam.',
    ];

    $this->actingAs($this->staffUser)
        ->post(route('bookings.store'), $bookingData)
        ->assertRedirect(route('bookings.index'));

    $this->assertDatabaseHas('bookings', [
        'vehicle_model' => 'Civic',
        'plate_number' => 'D 9999 AB',
        'status' => 'BOOKED',
    ]);

    $this->assertDatabaseHas('customers', [
        'phone' => '081234567890',
        'name' => 'Customer Baru',
    ]);
});

it('transitions status through the endpoint successfully', function () {
    $booking = Booking::withoutStatusProtection(fn() => Booking::create([
        'booking_code' => 'CB-TEST-123',
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

    $this->actingAs($this->staffUser)
        ->patch(route('bookings.transition', $booking), [
            'status' => 'CHECK_IN',
        ])
        ->assertRedirect();

    expect($booking->fresh()->status)->toBe('CHECK_IN');

    $this->assertDatabaseHas('booking_status_logs', [
        'booking_id' => $booking->id,
        'status' => 'CHECK_IN',
        'updated_by' => $this->staffUser->id,
    ]);
});
