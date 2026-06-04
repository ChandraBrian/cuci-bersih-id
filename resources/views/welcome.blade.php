<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cuci Bersih.id - Premium Vehicle Wash & Reservation Management</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
    <div class="min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">
        
        <!-- Navbar -->
        <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100 dark:border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
                <a href="/" class="flex items-center space-x-2 font-black text-2xl text-indigo-600 dark:text-indigo-400">
                    <svg class="w-8 h-8 animate-pulse text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    <span>Cuci Bersih<span class="text-slate-400">.id</span></span>
                </a>

                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            Staff Login
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow">
            <div class="relative overflow-hidden py-20 sm:py-32">
                <!-- Background decorative elements -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-gradient-to-tr from-indigo-500/10 to-purple-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        
                        <!-- Left text area -->
                        <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black tracking-wide uppercase bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-ping"></span>
                                Now Live & Online
                            </span>
                            <h1 class="text-4xl sm:text-6xl font-black text-slate-900 dark:text-white tracking-tight leading-none">
                                Premium Wash Services <br>
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-500">For Your Vehicle.</span>
                            </h1>
                            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-xl mx-auto lg:mx-0">
                                Professional car and motorcycle cleaning service. Track reservation statuses in real-time, register as member to get special promos, and pay online seamlessly.
                            </p>
                            
                            <!-- Action buttons -->
                            <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                                <a href="{{ route('tracking') }}" class="inline-flex justify-center items-center px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-base rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-0.5">
                                    Track Your vehicle
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                                <a href="#services" class="inline-flex justify-center items-center px-8 py-4 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-800 dark:text-white font-bold text-base rounded-2xl border border-slate-200 dark:border-slate-700 transition-all transform hover:-translate-y-0.5">
                                    Explore Services
                                </a>
                            </div>
                        </div>

                        <!-- Right Card area: Track Box -->
                        <div class="lg:col-span-5">
                            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl p-6 sm:p-8 border border-slate-100 dark:border-slate-800 relative">
                                <div class="absolute -top-3 -right-3 bg-indigo-600 text-white text-[10px] uppercase tracking-widest font-black py-1 px-3.5 rounded-full shadow-lg">
                                    Instant Trace
                                </div>
                                <h3 class="text-xl font-bold text-slate-950 dark:text-white mb-2">Check Wash Status</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Type in your reservation code to view live stepper updates.</p>
                                
                                <form method="GET" action="{{ route('tracking.search') }}" class="space-y-4">
                                    <div>
                                        <label for="code" class="sr-only">Reservation Code</label>
                                        <input type="text" name="code" id="code" required class="block w-full rounded-2xl border-slate-300 dark:border-slate-800 dark:bg-slate-950 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4" placeholder="e.g. CB-20260604-00001">
                                    </div>
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/20 transition">
                                        Track Live Status
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Features / Info section -->
            <section id="services" class="py-20 bg-slate-100/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-800/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center space-y-4 mb-16">
                        <h2 class="text-3xl font-black text-slate-950 dark:text-white tracking-tight">Our Wash Services</h2>
                        <p class="text-base text-slate-500 dark:text-slate-400 max-w-xl mx-auto">Choose the perfect treatment package for your ride.</p>
                    </div>

                    <!-- Services list (dynamic from database) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @php
                            $colors = [
                                ['bg' => 'bg-indigo-50 dark:bg-indigo-950/50', 'text' => 'text-indigo-600 dark:text-indigo-400'],
                                ['bg' => 'bg-purple-50 dark:bg-purple-950/50', 'text' => 'text-purple-600 dark:text-purple-400'],
                                ['bg' => 'bg-emerald-50 dark:bg-emerald-950/50', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                                ['bg' => 'bg-amber-50 dark:bg-amber-950/50', 'text' => 'text-amber-600 dark:text-amber-400'],
                            ];
                            $icons = [
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>',
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>',
                            ];
                        @endphp

                        @forelse($services as $index => $service)
                            @php
                                $color = $colors[$index % count($colors)];
                                $icon = $icons[$index % count($icons)];
                            @endphp
                            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800/50 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                <div class="space-y-4">
                                    <div class="w-12 h-12 {{ $color['bg'] }} rounded-2xl flex items-center justify-center {{ $color['text'] }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ $service->name }}</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $service->description ?? 'Professional wash service.' }}</p>
                                </div>
                                <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-800/50 flex justify-between items-center">
                                    <span class="text-lg font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                    <span class="text-xs text-slate-400 font-semibold">{{ $service->duration_estimate }} mins</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12 text-slate-400">
                                <p>No services available yet.</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800/80 py-8">
            <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-400 dark:text-slate-500 space-y-2">
                <div>&copy; {{ date('Y') }} Cuci Bersih.id. Built using modern tech-stack.</div>
                <div>Manage reservations, payments, and workflow queues in a unified portal.</div>
            </div>
        </footer>

    </div>
</body>
</html>
