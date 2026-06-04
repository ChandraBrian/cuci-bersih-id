<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Show the payment form for a booking.
     */
    public function create(Booking $booking): View
    {
        $booking->load(['customer', 'service', 'payment']);

        // Default amount is the price of the booking's service
        $defaultAmount = $booking->service->price;

        return view('payments.create', compact('booking', 'defaultAmount'));
    }

    /**
     * Store a payment record for a booking.
     */
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'in:cash,qris'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'payment_proof' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // Cash payments are usually verified immediately by staff.
        // QRIS payments uploaded by customers are pending until verified,
        // but if staff/admin creates the payment, they can mark it as paid.
        $isStaff = auth()->check() && in_array(auth()->user()->role, ['admin', 'staff']);
        $paymentStatus = ($request->input('payment_method') === 'cash' || $isStaff) ? 'paid' : 'pending';
        $paidAt = ($paymentStatus === 'paid') ? now() : null;

        // Use updateOrCreate in case a pending payment is being updated/re-submitted
        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_method'),
                'payment_reference' => $request->input('payment_reference'),
                'payment_proof' => $proofPath ?? $booking->payment?->payment_proof,
                'payment_status' => $paymentStatus,
                'paid_at' => $paidAt,
            ]
        );

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Payment details successfully saved.');
    }

    /**
     * Verify a pending payment (Admin/Staff only).
     */
    public function verify(Payment $payment): RedirectResponse
    {
        if (auth()->check() && !in_array(auth()->user()->role, ['admin', 'staff'])) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $payment->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('bookings.show', $payment->booking_id)
            ->with('success', 'Payment has been verified and marked as PAID.');
    }
}
