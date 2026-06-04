<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Record Payment for: ') }} {{ $booking->booking_code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('payments.store', $booking) }}" enctype="multipart/form-data" x-data="{ method: 'cash' }" class="space-y-6">
                        @csrf

                        <!-- Notification Alerts -->
                        @if ($errors->any())
                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Booking Details summary -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg space-y-2 mb-6">
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Booking Info</h3>
                            <div class="grid grid-cols-2 text-sm gap-2">
                                <span class="text-gray-500">Customer:</span>
                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $booking->customer->name }}</span>

                                <span class="text-gray-500">Service:</span>
                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $booking->service->name }}</span>

                                <span class="text-gray-500">Price:</span>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                            <select name="payment_method" id="payment_method" x-model="method" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="cash">Cash (Tunai)</option>
                                <option value="qris">QRIS (Digital QR)</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount to Pay (Rp)</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $defaultAmount) }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Payment Reference -->
                        <div>
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference Number (Optional)</label>
                            <input type="text" name="payment_reference" id="payment_reference" value="{{ old('payment_reference') }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. TxRef-98213">
                        </div>

                        <!-- Payment Proof (for QRIS) -->
                        <div x-show="method === 'qris'" x-transition class="space-y-2">
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload QRIS Payment Proof</label>
                            <input type="file" name="payment_proof" id="payment_proof" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-gray-700 dark:file:text-gray-300 hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-400 mt-1">Accepted formats: JPG, JPEG, PNG. Max 2MB.</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-2 pt-4">
                            <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-emerald-500/20">
                                Save Payment Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
