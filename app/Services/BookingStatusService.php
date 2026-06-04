<?php

namespace App\Services;

use App\Exceptions\InvalidBookingStatusTransitionException;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BookingStatusService
{
    // Define booking statuses as constants to avoid hardcoded string repetition
    public const STATUS_BOOKED = 'BOOKED';
    public const STATUS_CHECK_IN = 'CHECK_IN';
    public const STATUS_QUEUE = 'QUEUE';
    public const STATUS_WASHING = 'WASHING';
    public const STATUS_QUALITY_CHECK = 'QUALITY_CHECK';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_PICKED_UP = 'PICKED_UP';
    public const STATUS_CANCELLED = 'CANCELLED';

    // Map of allowed transitions: Current status => [Allowed new statuses]
    private const ALLOWED_TRANSITIONS = [
        self::STATUS_BOOKED => [
            self::STATUS_CHECK_IN,
            self::STATUS_CANCELLED,
        ],
        self::STATUS_CHECK_IN => [
            self::STATUS_QUEUE,
            self::STATUS_CANCELLED,
        ],
        self::STATUS_QUEUE => [
            self::STATUS_WASHING,
        ],
        self::STATUS_WASHING => [
            self::STATUS_QUALITY_CHECK,
        ],
        self::STATUS_QUALITY_CHECK => [
            self::STATUS_COMPLETED,
        ],
        self::STATUS_COMPLETED => [
            self::STATUS_PICKED_UP,
        ],
    ];

    /**
     * Transition a booking to a new status.
     *
     * @param Booking $booking
     * @param string $newStatus
     * @param User $updatedBy
     * @return Booking
     *
     * @throws InvalidBookingStatusTransitionException
     */
    public function transitionTo(Booking $booking, string $newStatus, User $updatedBy): Booking
    {
        $currentStatus = $booking->status;

        // 1. Validate if transition is defined and allowed
        $allowed = self::ALLOWED_TRANSITIONS[$currentStatus] ?? [];
        if (!in_array($newStatus, $allowed, true)) {
            throw new InvalidBookingStatusTransitionException(
                "Transition from {$currentStatus} to {$newStatus} is not allowed."
            );
        }

        // 2. Special validation for COMPLETED -> PICKED_UP (Requires payment status to be 'paid')
        if ($currentStatus === self::STATUS_COMPLETED && $newStatus === self::STATUS_PICKED_UP) {
            $payment = $booking->payment;
            if (!$payment || $payment->payment_status !== 'paid') {
                throw new InvalidBookingStatusTransitionException(
                    "Vehicle cannot be picked up because the payment status is not paid."
                );
            }
        }

        // 3. Execute update and log insertion inside a database transaction
        return DB::transaction(function () use ($booking, $newStatus, $updatedBy) {
            // Update booking status
            $booking->update([
                'status' => $newStatus,
            ]);

            // Log status change
            $booking->statusLogs()->create([
                'status' => $newStatus,
                'updated_by' => $updatedBy->id,
            ]);

            return $booking;
        });
    }
}
