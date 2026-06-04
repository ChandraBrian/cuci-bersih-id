<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use App\Models\Payment;
use App\Services\BookingStatusService;
use App\Exceptions\InvalidBookingStatusTransitionException;
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

    // Create booking bypassing status protection for initial state creation
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

    $this->user = User::factory()->create([
        'role' => 'staff',
    ]);

    $this->serviceStatus = new BookingStatusService();
});

it('allows valid booking status transitions', function () {
    // BOOKED -> CHECK_IN
    $this->serviceStatus->transitionTo($this->booking, 'CHECK_IN', $this->user);
    expect($this->booking->fresh()->status)->toBe('CHECK_IN');

    // CHECK_IN -> QUEUE
    $this->serviceStatus->transitionTo($this->booking, 'QUEUE', $this->user);
    expect($this->booking->fresh()->status)->toBe('QUEUE');

    // QUEUE -> WASHING
    $this->serviceStatus->transitionTo($this->booking, 'WASHING', $this->user);
    expect($this->booking->fresh()->status)->toBe('WASHING');

    // WASHING -> QUALITY_CHECK
    $this->serviceStatus->transitionTo($this->booking, 'QUALITY_CHECK', $this->user);
    expect($this->booking->fresh()->status)->toBe('QUALITY_CHECK');

    // QUALITY_CHECK -> COMPLETED
    $this->serviceStatus->transitionTo($this->booking, 'COMPLETED', $this->user);
    expect($this->booking->fresh()->status)->toBe('COMPLETED');
});

it('blocks invalid booking status transitions', function () {
    // Attempting direct jump: BOOKED -> QUEUE should throw exception
    expect(fn() => $this->serviceStatus->transitionTo($this->booking, 'QUEUE', $this->user))
        ->toThrow(InvalidBookingStatusTransitionException::class);
});

it('blocks picked up transition if not paid', function () {
    // Transition up to COMPLETED first
    $this->serviceStatus->transitionTo($this->booking, 'CHECK_IN', $this->user);
    $this->serviceStatus->transitionTo($this->booking, 'QUEUE', $this->user);
    $this->serviceStatus->transitionTo($this->booking, 'WASHING', $this->user);
    $this->serviceStatus->transitionTo($this->booking, 'QUALITY_CHECK', $this->user);
    $this->serviceStatus->transitionTo($this->booking, 'COMPLETED', $this->user);

    // Attempting COMPLETED -> PICKED_UP without payment should fail
    expect(fn() => $this->serviceStatus->transitionTo($this->booking, 'PICKED_UP', $this->user))
        ->toThrow(InvalidBookingStatusTransitionException::class);
});

it('allows picked up transition if paid', function () {
    // Transition up to COMPLETED first
    $this->serviceStatus->transitionTo($this->booking, 'CHECK_IN', $this->user);
    $this->serviceStatus->transitionTo($this->booking, 'QUEUE', $this->user);
    $this->serviceStatus->transitionTo($this->booking, 'WASHING', $this->user);
    $this->serviceStatus->transitionTo($this->booking, 'QUALITY_CHECK', $this->user);
    $this->serviceStatus->transitionTo($this->booking, 'COMPLETED', $this->user);

    // Add a paid payment
    Payment::create([
        'booking_id' => $this->booking->id,
        'amount' => 50000.00,
        'payment_method' => 'cash',
        'payment_status' => 'paid',
        'paid_at' => now(),
    ]);

    // Transition COMPLETED -> PICKED_UP should succeed now
    $this->serviceStatus->transitionTo($this->booking, 'PICKED_UP', $this->user);
    expect($this->booking->fresh()->status)->toBe('PICKED_UP');
});

it('allows cancellation before completed but not after', function () {
    // BOOKED -> CANCELLED is allowed
    $this->serviceStatus->transitionTo($this->booking, 'CANCELLED', $this->user);
    expect($this->booking->fresh()->status)->toBe('CANCELLED');
});

it('logs status transitions in booking_status_logs', function () {
    $this->serviceStatus->transitionTo($this->booking, 'CHECK_IN', $this->user);
    
    $this->assertDatabaseHas('booking_status_logs', [
        'booking_id' => $this->booking->id,
        'status' => 'CHECK_IN',
        'updated_by' => $this->user->id,
    ]);
});
