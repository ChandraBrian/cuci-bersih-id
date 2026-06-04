<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Exceptions\DirectBookingStatusModificationException;
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

    $this->booking = Booking::withoutStatusProtection(function () {
        return Booking::create([
            'booking_code' => 'BK-' . uniqid(),
            'customer_id' => $this->customer->id,
            'service_id' => $this->service->id,
            'vehicle_type' => 'mobil',
            'vehicle_brand' => 'Toyota',
            'vehicle_model' => 'Avanza',
            'vehicle_color' => 'Hitam',
            'plate_number' => 'B 1234 CD',
            'booking_date' => now()->toDateString(),
            'status' => 'BOOKED',
        ]);
    });
});

it('prevents direct status modification via update()', function () {
    expect(fn() => $this->booking->update(['status' => 'CHECK_IN']))
        ->toThrow(DirectBookingStatusModificationException::class);

    expect($this->booking->fresh()->status)->toBe('BOOKED');
});

it('prevents direct status modification via property assignment and save()', function () {
    $this->booking->status = 'CHECK_IN';
    expect(fn() => $this->booking->save())
        ->toThrow(DirectBookingStatusModificationException::class);

    expect($this->booking->fresh()->status)->toBe('BOOKED');
});

it('allows status modification when wrapped in withoutStatusProtection', function () {
    Booking::withoutStatusProtection(function () {
        $this->booking->update(['status' => 'CHECK_IN']);
    });

    expect($this->booking->fresh()->status)->toBe('CHECK_IN');
});
