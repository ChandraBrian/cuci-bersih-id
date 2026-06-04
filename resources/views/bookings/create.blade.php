<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New Booking Reservation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('bookings.store') }}" x-data="{ newCustomer: false }" class="space-y-6">
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

                        <!-- Customer Selection -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Customer Details</h3>
                            
                            <div class="flex items-center space-x-6 mb-4">
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="false" x-model="newCustomer" name="is_new_customer" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 mr-2">
                                    Existing Customer
                                </label>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="true" x-model="newCustomer" name="is_new_customer" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 mr-2">
                                    New Customer
                                </label>
                            </div>

                            <!-- Existing Customer Dropdown -->
                            <div x-show="!newCustomer" x-transition class="space-y-2">
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Customer</label>
                                <select name="customer_id" id="customer_id" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Choose Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- New Customer Input Fields -->
                            <div x-show="newCustomer" x-transition class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                                        <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. John Doe">
                                    </div>
                                    <div>
                                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                                        <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. 08123456789">
                                    </div>
                                </div>
                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address (Optional)</label>
                                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. customer@example.com">
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Details -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Vehicle Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle Type</label>
                                    <select name="vehicle_type" id="vehicle_type" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="Motor" {{ old('vehicle_type') === 'Motor' ? 'selected' : '' }}>Motorcycle (Motor)</option>
                                        <option value="Mobil" {{ old('vehicle_type') === 'Mobil' ? 'selected' : '' }}>Car (Mobil)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="plate_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plate Number (Nomor Plat)</label>
                                    <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number') }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. B 1234 ABC">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="vehicle_brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle Brand</label>
                                    <input type="text" name="vehicle_brand" id="vehicle_brand" value="{{ old('vehicle_brand') }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Honda / Toyota">
                                </div>
                                <div>
                                    <label for="vehicle_model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle Model</label>
                                    <input type="text" name="vehicle_model" id="vehicle_model" value="{{ old('vehicle_model') }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Vario / Avanza">
                                </div>
                                <div>
                                    <label for="vehicle_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle Color</label>
                                    <input type="text" name="vehicle_color" id="vehicle_color" value="{{ old('vehicle_color') }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Black / White">
                                </div>
                            </div>
                        </div>

                        <!-- Service and Schedule -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Service & Schedule</h3>
                            
                            <div>
                                <label for="service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Choose Wash Service</label>
                                <select name="service_id" id="service_id" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Choose Service --</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="booking_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Booking Date</label>
                                <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Special request, scratch notes">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-2 pt-4">
                            <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-indigo-500/20">
                                Create Reservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
