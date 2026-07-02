<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Smart Classroom') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            }
            .heading-font {
                font-family: 'Outfit', sans-serif;
            }
            .tech-grid {
                background-size: 40px 40px;
                background-image: 
                    linear-gradient(to right, rgba(99, 102, 241, 0.03) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
            }
            @keyframes pulse-slow {
                0%, 100% { transform: scale(1); opacity: 0.15; }
                50% { transform: scale(1.1); opacity: 0.25; }
            }
            .animate-pulse-slow {
                animation: pulse-slow 8s ease-in-out infinite;
            }
            @keyframes float-slow {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-12px); }
            }
            .animate-float-slow {
                animation: float-slow 7s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="antialiased bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between tech-grid overflow-x-hidden">
        
        <!-- Glowing gradient backdrops -->
        <div class="absolute top-0 right-1/4 w-[600px] h-[600px] rounded-full bg-indigo-500/10 blur-[130px] animate-pulse-slow pointer-events-none"></div>
        <div class="absolute bottom-1/3 left-1/4 w-[500px] h-[500px] rounded-full bg-cyan-500/10 blur-[120px] animate-pulse-slow pointer-events-none" style="animation-delay: -3s;"></div>

        <!-- Header -->
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between border-b border-slate-800/60 relative z-10">
            <a href="/" class="flex items-center gap-3 group">
                <div class="p-2 bg-indigo-950/60 rounded-xl border border-indigo-900/50 group-hover:scale-105 transition-all duration-300">
                    <x-application-logo class="w-8 h-8" />
                </div>
                <div>
                    <span class="heading-font text-lg font-bold tracking-tight bg-gradient-to-r from-indigo-400 via-violet-400 to-cyan-400 bg-clip-text text-transparent">Smart Classroom</span>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-500">IoT Ecosystem</span>
                </div>
            </a>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-all duration-200 shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20 active:scale-[0.98]">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors py-2 px-4">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white border border-slate-700/60 hover:border-slate-600 font-semibold text-sm transition-all duration-200 active:scale-[0.98]">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content -->
        <main class="w-full max-w-7xl mx-auto px-6 py-12 md:py-20 relative z-10 my-auto">
            <div class="max-w-4xl mx-auto text-center flex flex-col items-center justify-center space-y-6 py-8 md:py-14">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-indigo-500/10 border border-indigo-500/30 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-300">ESP32 & Laravel IoT Project</span>
                </div>

                <h1 class="heading-font text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                    Next-Gen Automation for <br>
                    <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-cyan-400 bg-clip-text text-transparent">Smart Classrooms</span>
                </h1>

                <p class="text-slate-400 text-base sm:text-lg leading-relaxed max-w-2xl">
                    A robust, automated environmental system that tracks real-time classroom telemetry, monitors active student occupancy, and triggers smart appliances based on energy-saving presets.
                </p>

                <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white font-bold text-sm shadow-xl shadow-indigo-600/10 hover:shadow-indigo-600/20 active:scale-[0.99] transition-all duration-200">
                            Open Hub Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white font-bold text-sm shadow-xl shadow-indigo-600/10 hover:shadow-indigo-600/20 active:scale-[0.99] transition-all duration-200">
                            Access Dashboard Portal
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white border border-slate-700/60 font-semibold text-sm transition-all duration-200 active:scale-[0.99]">
                            Create Device Account
                        </a>
                    @endauth
                </div>
            </div>


            <!-- Features Grid Section -->
            <div class="mt-24 pt-16 border-t border-slate-800/80">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="heading-font text-3xl font-extrabold text-white">Ecosystem Capabilities</h2>
                    <p class="text-slate-400 text-sm mt-2">Explore the integrated hardware and software automation components designed for our smart learning environment.</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Feature 1 -->
                    <div class="p-6 bg-slate-800/20 border border-slate-800/60 rounded-2xl hover:border-slate-700/60 transition-all duration-300">
                        <div class="w-10 h-10 bg-indigo-500/10 rounded-xl text-indigo-400 flex items-center justify-center mb-4 border border-indigo-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="heading-font text-base font-bold text-white mb-2">Live Telemetry Feeds</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Streams temperature, humidity, and CO2 indices continuously from ESP32 sensors over secure protocols.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 bg-slate-800/20 border border-slate-800/60 rounded-2xl hover:border-slate-700/60 transition-all duration-300">
                        <div class="w-10 h-10 bg-cyan-500/10 rounded-xl text-cyan-400 flex items-center justify-center mb-4 border border-cyan-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h3 class="heading-font text-base font-bold text-white mb-2">Appliance Controls</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Instantly toggle relay modules to control light fixtures, fans, projectors, and air conditioning units remotely.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 bg-slate-800/20 border border-slate-800/60 rounded-2xl hover:border-slate-700/60 transition-all duration-300">
                        <div class="w-10 h-10 bg-emerald-500/10 rounded-xl text-emerald-400 flex items-center justify-center mb-4 border border-emerald-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="heading-font text-base font-bold text-white mb-2">Automated Savings</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Integrated motion sensors trigger automatic power saving rules when classrooms remain empty for a preset time.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-6 bg-slate-800/20 border border-slate-800/60 rounded-2xl hover:border-slate-700/60 transition-all duration-300">
                        <div class="w-10 h-10 bg-amber-500/10 rounded-xl text-amber-400 flex items-center justify-center mb-4 border border-amber-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="heading-font text-base font-bold text-white mb-2">Historical Logs</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Logs telemetry metrics and device commands persistently for tracking classroom utilization and power charts.</p>
                    </div>

                </div>
            </div>

            <!-- Tech Stack Footer -->
            <div class="mt-20 pt-10 border-t border-slate-800/80 flex flex-wrap justify-between items-center gap-6">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Supported Hardware Stack</h4>
                    <div class="flex flex-wrap gap-4 mt-2 text-slate-400 text-xs font-semibold">
                        <span class="px-2.5 py-1 bg-slate-800/40 border border-slate-800 rounded">ESP32 NodeMCU</span>
                        <span class="px-2.5 py-1 bg-slate-800/40 border border-slate-800 rounded">DHT22 Temp/Humi Sensor</span>
                        <span class="px-2.5 py-1 bg-slate-800/40 border border-slate-800 rounded">MQ135 CO2 Sensor</span>
                        <span class="px-2.5 py-1 bg-slate-800/40 border border-slate-800 rounded">5V Relay Modules</span>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Built With</h4>
                    <div class="flex flex-wrap gap-4 mt-2 text-slate-400 text-xs font-semibold">
                        <span class="px-2.5 py-1 bg-slate-800/40 border border-slate-800 rounded">Laravel 11</span>
                        <span class="px-2.5 py-1 bg-slate-800/40 border border-slate-800 rounded">Tailwind CSS</span>
                        <span class="px-2.5 py-1 bg-slate-800/40 border border-slate-800 rounded">Vite bundler</span>
                    </div>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto px-6 py-6 border-t border-slate-800/60 flex items-center justify-between text-xs text-slate-500 relative z-10">
            <p>&copy; {{ date('Y') }} Smart Classroom Hub. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-indigo-400 transition-colors">Documentation</a>
                <a href="#" class="hover:text-indigo-400 transition-colors">Privacy Policy</a>
            </div>
        </footer>

    </body>
</html>
