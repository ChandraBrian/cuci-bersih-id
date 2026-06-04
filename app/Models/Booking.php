<?php

namespace App\Models;

use App\Exceptions\DirectBookingStatusModificationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    /**
     * Flag to temporarily bypass status modification protection.
     *
     * @var bool
     */
    public static bool $allowStatusModification = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_code',
        'customer_id',
        'service_id',
        'vehicle_type',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_color',
        'plate_number',
        'booking_date',
        'status',
        'notes',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::saving(function (Booking $booking) {
            if ($booking->exists && $booking->isDirty('status') && !static::$allowStatusModification) {
                throw new DirectBookingStatusModificationException(
                    "Direct status update is not allowed. Use BookingStatusService."
                );
            }
        });
    }

    /**
     * Run a callback bypassing status modification protection.
     *
     * @param callable $callback
     * @return mixed
     */
    public static function withoutStatusProtection(callable $callback): mixed
    {
        $original = static::$allowStatusModification;
        static::$allowStatusModification = true;

        try {
            return $callback();
        } finally {
            static::$allowStatusModification = $original;
        }
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    /**
     * Get the customer that owns this booking.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the service associated with this booking.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get all status logs for this booking.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(BookingStatusLog::class);
    }

    /**
     * Get the payment for this booking.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
