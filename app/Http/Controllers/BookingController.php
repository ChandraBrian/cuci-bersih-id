<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidBookingStatusTransitionException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Services\BookingStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    protected BookingStatusService $statusService;

    /**
     * Inject the BookingStatusService.
     */
    public function __construct(BookingStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    /**
     * Display a listing of the bookings (Queue / Dashboard).
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Booking::with(['customer', 'service', 'payment']);

        if ($status) {
            $query->where('status', $status);
        }

        // Ordered by latest bookings
        $bookings = $query->latest()->paginate(15);

        // Group counts for dashboard summary cards
        $counts = Booking::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('bookings.index', compact('bookings', 'counts'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(): View
    {
        $services = Service::where('is_active', true)->get();
        $customers = Customer::orderBy('name')->get();

        return view('bookings.create', compact('services', 'customers'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // 1. Resolve customer
        if (empty($validated['customer_id'])) {
            $customer = Customer::firstOrCreate(
                ['phone' => $validated['customer_phone']],
                [
                    'name' => $validated['customer_name'],
                    'email' => $validated['customer_email'],
                    'is_member' => false,
                ]
            );
            $customerId = $customer->id;
        } else {
            $customerId = $validated['customer_id'];
        }

        // 2. Generate unique booking code: CB-YYYYMMDD-XXXX
        do {
            $bookingCode = 'CB-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Booking::where('booking_code', $bookingCode)->exists());

        // 3. Create the Booking (initial status is BOOKED by default)
        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'customer_id' => $customerId,
            'service_id' => $validated['service_id'],
            'vehicle_type' => $validated['vehicle_type'],
            'vehicle_brand' => $validated['vehicle_brand'],
            'vehicle_model' => $validated['vehicle_model'],
            'vehicle_color' => $validated['vehicle_color'],
            'plate_number' => $validated['plate_number'],
            'booking_date' => $validated['booking_date'],
            'status' => BookingStatusService::STATUS_BOOKED,
            'notes' => $validated['notes'] ?? null,
        ]);

        // 4. Create initial status log
        $booking->statusLogs()->create([
            'status' => BookingStatusService::STATUS_BOOKED,
            'updated_by' => auth()->id(), // null if guest customer creates it
        ]);

        return redirect()->route('bookings.index')
            ->with('success', "Booking successfully created with code: {$booking->booking_code}");
    }

    /**
     * Display the specified booking details.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['customer', 'service', 'payment', 'statusLogs.updatedBy']);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Trigger status transition using the BookingStatusService.
     */
    public function transition(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string'],
        ]);

        $newStatus = $request->input('status');
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        try {
            $this->statusService->transitionTo($booking, $newStatus, $user);

            return redirect()->back()
                ->with('success', "Booking status transitioned to {$newStatus} successfully.");
        } catch (InvalidBookingStatusTransitionException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
