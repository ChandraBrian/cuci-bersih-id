<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('service_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('vehicle_type');
            $table->string('vehicle_brand');
            $table->string('vehicle_model');
            $table->string('vehicle_color');
            $table->string('plate_number');

            $table->date('booking_date');

            $table->enum('status', [
                'BOOKED',
                'CHECK_IN',
                'QUEUE',
                'WASHING',
                'QUALITY_CHECK',
                'COMPLETED',
                'PICKED_UP',
                'CANCELLED'
            ])->default('BOOKED');

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
