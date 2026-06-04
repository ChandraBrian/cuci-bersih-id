<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Reservation - Cuci Bersih.id</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col justify-between">
        
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
                <a href="/" class="flex items-center space-x-2 font-bold text-xl text-indigo-600 dark:text-indigo-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    <span>Cuci Bersih.id</span>
                </a>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition">
                    Staff Login &rarr;
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto space-y-8">
                
                <!-- Hero section -->
                <div class="text-center space-y-3">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 dark:text-white tracking-tight">Track Your Vehicle Status</h1>
                    <p class="text-base text-gray-500 max-w-xl mx-auto">Enter your booking reservation code below to trace the real-time washing status of your vehicle.</p>
                </div>

                <!-- Search form -->
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 sm:p-8 max-w-2xl mx-auto border border-gray-100 dark:border-gray-700/50">
                    <form method="GET" action="{{ route('tracking.search') }}" class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-grow relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="code" value="{{ request('code') }}" required class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-base" placeholder="e.g. CB-20260604-00001">
                        </div>
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition">
                            Track Status
                        </button>
                    </form>
                </div>

                @if(isset($booking))
                    @if($booking)
                        <!-- Booking Details and Stepper -->
                        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                            
                            <!-- Header Info -->
                            <div class="p-6 sm:p-8 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                                <div>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Booking Code</span>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $booking->booking_code }}</h2>
                                </div>
                                <div class="text-left sm:text-right">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Current Status</span>
                                    <div class="mt-1">
                                        @php
                                            $statusStyles = [
                                                'BOOKED' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                                                'CHECK_IN' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
                                                'QUEUE' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
                                                'WASHING' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800',
                                                'QUALITY_CHECK' => 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300 border border-pink-200 dark:border-pink-800',
                                                'COMPLETED' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800',
                                                'PICKED_UP' => 'bg-emerald-600 text-white',
                                                'CANCELLED' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-extrabold tracking-wide uppercase {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $booking->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8 space-y-8">
                                <!-- Stepper Progress Tracker -->
                                @if($booking->status === 'CANCELLED')
                                    <div class="p-5 text-center bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-400 rounded-xl border border-red-100 dark:border-red-800">
                                        <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <h3 class="font-bold text-lg">This booking has been cancelled</h3>
                                        <p class="text-sm mt-1">Please contact our staff or customer support for more details.</p>
                                    </div>
                                @else
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Washing Progress</h3>
                                        
                                        @php
                                            $steps = ['BOOKED', 'CHECK_IN', 'QUEUE', 'WASHING', 'QUALITY_CHECK', 'COMPLETED', 'PICKED_UP'];
                                            $currentStepIdx = array_search($booking->status, $steps);
                                        @endphp
                                        
                                        <!-- Desktop/Tablet Stepper -->
                                        <div class="hidden md:flex items-center justify-between relative">
                                            <!-- Connection Line -->
                                            <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>
                                            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-indigo-600 z-0 transition-all duration-500" style="width: {{ $currentStepIdx !== false ? ($currentStepIdx / 6) * 100 : 0 }}%;"></div>

                                            @foreach($steps as $idx => $step)
                                                @php
                                                    $isCompleted = $idx < $currentStepIdx;
                                                    $isActive = $idx === $currentStepIdx;
                                                    $isUpcoming = $idx > $currentStepIdx;
                                                @endphp
                                                <div class="flex flex-col items-center z-10">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300
                                                        @if($isCompleted)
                                                            bg-indigo-600 border-indigo-600 text-white
                                                        @elseif($isActive)
                                                            bg-white dark:bg-gray-800 border-indigo-600 text-indigo-600 ring-4 ring-indigo-100 dark:ring-indigo-950
                                                        @else
                                                            bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-400
                                                        @endif">
                                                        @if($isCompleted)
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        @else
                                                            {{ $idx + 1 }}
                                                        @endif
                                                    </div>
                                                    <span class="text-xs font-semibold mt-2 text-center 
                                                        @if($isActive) text-indigo-600 dark:text-indigo-400 font-bold @elseif($isCompleted) text-gray-900 dark:text-gray-100 @else text-gray-400 @endif">
                                                        {{ str_replace('_', ' ', $step) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Mobile Vertical Stepper -->
                                        <div class="md:hidden space-y-6">
                                            @foreach($steps as $idx => $step)
                                                @php
                                                    $isCompleted = $idx < $currentStepIdx;
                                                    $isActive = $idx === $currentStepIdx;
                                                    $isUpcoming = $idx > $currentStepIdx;
                                                @endphp
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300 shrink-0
                                                        @if($isCompleted)
                                                            bg-indigo-600 border-indigo-600 text-white
                                                        @elseif($isActive)
                                                            bg-white dark:bg-gray-800 border-indigo-600 text-indigo-600 ring-4 ring-indigo-100 dark:ring-indigo-950
                                                        @else
                                                            bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-400
                                                        @endif">
                                                        @if($isCompleted)
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        @else
                                                            {{ $idx + 1 }}
                                                        @endif
                                                    </div>
                                                    <span class="text-sm font-semibold 
                                                        @if($isActive) text-indigo-600 dark:text-indigo-400 font-bold @elseif($isCompleted) text-gray-900 dark:text-gray-100 @else text-gray-400 @endif">
                                                        {{ str_replace('_', ' ', $step) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Vehicle & Customer Details -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 dark:border-gray-700 pt-8">
                                    <div class="space-y-4">
                                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Vehicle Details</h3>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <span class="text-gray-500">Plate Number:</span>
                                            <span class="font-bold text-gray-900 dark:text-white">{{ $booking->plate_number }}</span>

                                            <span class="text-gray-500">Brand / Model:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $booking->vehicle_brand }} {{ $booking->vehicle_model }}</span>

                                            <span class="text-gray-500">Type:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $booking->vehicle_type }}</span>

                                            <span class="text-gray-500">Color:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $booking->vehicle_color }}</span>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Customer & Invoice</h3>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <span class="text-gray-500">Name:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $booking->customer->name }}</span>

                                            <span class="text-gray-500">Service chosen:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $booking->service->name }}</span>

                                            <span class="text-gray-500">Payment Status:</span>
                                            @if($booking->payment)
                                                @if($booking->payment->payment_status === 'paid')
                                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">PAID</span>
                                                @else
                                                    <span class="font-bold text-amber-500">PENDING VERIFICATION</span>
                                                @endif
                                            @else
                                                <span class="font-bold text-red-500">UNPAID</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Add a customer QRIS payment submission if they have not paid yet -->
                                @if(!$booking->payment)
                                    <div class="bg-indigo-50 dark:bg-gray-900 p-6 rounded-xl border border-indigo-100 dark:border-indigo-950 flex flex-col md:flex-row justify-between items-center gap-4">
                                        <div>
                                            <h4 class="font-bold text-indigo-900 dark:text-indigo-400">Submit Payment Online</h4>
                                            <p class="text-sm text-indigo-700 dark:text-indigo-300 mt-1">Upload your QRIS transfer receipt to speed up checking out.</p>
                                        </div>
                                        <a href="{{ route('payments.create', $booking) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-xs font-bold text-white uppercase tracking-widest rounded-lg shadow-lg shadow-indigo-500/20 transition">
                                            Upload Proof
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @else
                        <!-- Not Found message -->
                        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-700/50 text-center max-w-xl mx-auto">
                            <svg class="w-12 h-12 text-amber-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Booking code not found</h3>
                            <p class="text-sm text-gray-500 mt-1">Please double-check the booking code format and try again.</p>
                        </div>
                    @endif
                @endif

            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 py-6">
            <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Cuci Bersih.id. All rights reserved.
            </div>
        </footer>

    </div>
</body>
</html>
