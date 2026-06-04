<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Booking Details: ') }} {{ $booking->booking_code }}
            </h2>
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                &larr; Back to Queue
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Notification Alerts -->
            @if(session('success'))
                <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Details Card -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- General Details -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 space-y-6">
                            <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-700 pb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Vehicle & Reservation Details</h3>
                                    <p class="text-sm text-gray-500 mt-1">Booked for {{ $booking->booking_date->format('d M Y') }}</p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusStyles = [
                                            'BOOKED' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                                            'CHECK_IN' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
                                            'QUEUE' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
                                            'WASHING' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800',
                                            'QUALITY_CHECK' => 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300 border border-pink-200 dark:border-pink-800',
                                            'COMPLETED' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800',
                                            'PICKED_UP' => 'bg-emerald-600 text-white dark:bg-emerald-600 dark:text-white',
                                            'CANCELLED' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehicle Plate Number</h4>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $booking->plate_number }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehicle Description</h4>
                                    <p class="text-base font-medium text-gray-900 dark:text-white mt-1">
                                        {{ $booking->vehicle_brand }} {{ $booking->vehicle_model }} ({{ $booking->vehicle_color }})
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $booking->vehicle_type }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Wash Service</h4>
                                    <p class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $booking->service->name }}</p>
                                    <p class="text-sm text-indigo-500 font-semibold mt-0.5">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Notes</h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 italic">
                                        {{ $booking->notes ?? 'No special notes.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-4 mb-4">Customer Info</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</h4>
                                    <p class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $booking->customer->name }}</p>
                                    @if($booking->customer->is_member)
                                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            MEMBER
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Phone</h4>
                                    <p class="text-base font-medium text-gray-900 dark:text-white mt-1">{{ $booking->customer->phone }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</h4>
                                    <p class="text-base font-medium text-gray-900 dark:text-white mt-1">{{ $booking->customer->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-4 mb-4">Payment & Invoicing</h3>
                            
                            @if($booking->payment)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Amount Paid</h4>
                                            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Method</h4>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1 uppercase">{{ $booking->payment->payment_method }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</h4>
                                            <div class="mt-1">
                                                @if($booking->payment->payment_status === 'paid')
                                                    <span class="inline-flex items-center px-3 py-1 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 font-bold text-xs uppercase border border-emerald-200">
                                                        PAID
                                                    </span>
                                                    <p class="text-xs text-gray-500 mt-1">Verified on {{ $booking->payment->paid_at->format('d M Y H:i') }}</p>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 font-bold text-xs uppercase border border-amber-200">
                                                        PENDING VERIFICATION
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($booking->payment->payment_proof)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Payment Proof (QRIS)</h4>
                                            <div class="border dark:border-gray-700 rounded-lg p-2 max-w-xs bg-gray-50 dark:bg-gray-900">
                                                <a href="{{ asset('storage/' . $booking->payment->payment_proof) }}" target="_blank" class="block hover:opacity-90 transition">
                                                    <img src="{{ asset('storage/' . $booking->payment->payment_proof) }}" alt="Payment Proof" class="w-full h-auto rounded max-h-48 object-contain">
                                                    <span class="block text-center text-xs text-indigo-500 hover:text-indigo-600 mt-2 font-medium">View Fullscreen</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if($booking->payment->payment_status === 'pending')
                                    <div class="flex border-t dark:border-gray-700 pt-4 justify-end">
                                        <form method="POST" action="{{ route('payments.verify', $booking->payment) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow shadow-emerald-500/20">
                                                Verify & Approve Payment
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @else
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg flex flex-col md:flex-row justify-between items-center">
                                    <div class="text-center md:text-left mb-4 md:mb-0">
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">No payment has been recorded yet for this booking.</p>
                                        <p class="text-xs text-red-500 mt-0.5">Note: Vehicle cannot be marked as PICKED_UP until payment is recorded as Paid.</p>
                                    </div>
                                    <a href="{{ route('payments.create', $booking) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-xs font-semibold text-white uppercase tracking-widest rounded-md shadow-lg shadow-blue-500/20 transition">
                                        Record Payment
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Workflow / Action Panel & Timeline -->
                <div class="space-y-6">
                    <!-- Workflow Controller -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-4 mb-4">Workflow Actions</h3>
                            
                            @php
                                $currentStatus = $booking->status;
                                $nextStatuses = [];
                                
                                switch ($currentStatus) {
                                    case 'BOOKED':
                                        $nextStatuses = ['CHECK_IN', 'CANCELLED'];
                                        break;
                                    case 'CHECK_IN':
                                        $nextStatuses = ['QUEUE', 'CANCELLED'];
                                        break;
                                    case 'QUEUE':
                                        $nextStatuses = ['WASHING'];
                                        break;
                                    case 'WASHING':
                                        $nextStatuses = ['QUALITY_CHECK'];
                                        break;
                                    case 'QUALITY_CHECK':
                                        $nextStatuses = ['COMPLETED'];
                                        break;
                                    case 'COMPLETED':
                                        $nextStatuses = ['PICKED_UP'];
                                        break;
                                }
                            @endphp

                            @if(count($nextStatuses) > 0)
                                <div class="space-y-3">
                                    <p class="text-xs text-gray-500 font-medium">Select next state transition:</p>
                                    @foreach($nextStatuses as $next)
                                        <form method="POST" action="{{ route('bookings.transition', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $next }}">
                                            
                                            @if($next === 'CANCELLED')
                                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 dark:border-red-700 text-xs font-semibold text-red-700 dark:text-red-400 bg-white dark:bg-gray-800 rounded-md hover:bg-red-50 dark:hover:bg-red-950/20 transition">
                                                    Cancel Booking (CANCELLED)
                                                </button>
                                            @elseif($next === 'PICKED_UP')
                                                @php
                                                    $isPaid = $booking->payment && $booking->payment->payment_status === 'paid';
                                                @endphp
                                                <button type="submit" 
                                                    {{ !$isPaid ? 'disabled' : '' }}
                                                    class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition shadow shadow-emerald-500/10 {{ !$isPaid ? 'bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                                    Deliver to Customer (PICKED_UP)
                                                </button>
                                                @if(!$isPaid)
                                                    <p class="text-xxs text-red-500 mt-1 text-center font-semibold">Payment must be PAID first to PICK UP vehicle.</p>
                                                @endif
                                            @else
                                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white uppercase tracking-widest rounded-md shadow shadow-indigo-500/20 transition">
                                                    Transition to {{ $next }}
                                                </button>
                                            @endif
                                        </form>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm font-medium text-gray-500 text-center py-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                    This booking has reached its final state: <span class="font-bold text-gray-700 dark:text-gray-300">{{ $currentStatus }}</span>.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline Status Log -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-4 mb-4">Status Logs Timeline</h3>
                            
                            <ol class="relative border-l border-gray-200 dark:border-gray-700 space-y-6">
                                @foreach($booking->statusLogs->sortByDesc('created_at') as $log)
                                    <li class="mb-4 ml-4">
                                        <div class="absolute w-3 h-3 bg-gray-200 dark:bg-gray-700 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-800"></div>
                                        <time class="mb-1 text-xs font-normal leading-none text-gray-400 dark:text-gray-500">
                                            {{ $log->created_at->format('d M Y H:i:s') }}
                                        </time>
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mt-1 uppercase">
                                            {{ $log->status }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            By: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $log->updatedBy->name ?? 'System' }}</span>
                                        </p>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
