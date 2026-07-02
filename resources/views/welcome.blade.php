<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Smart Classroom') }}</title>

        <!-- Google Fonts: Kantumruy Pro & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Kantumruy Pro', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            }
            
            /* Apple Generative-Fluid Kinetic Background */
            @keyframes fluid-spatial-morph {
                0% { transform: translate(0px, 0px) scale(1) rotate(0deg); }
                33% { transform: translate(4% , -6%) scale(1.12) rotate(120deg); }
                66% { transform: translate(-5%, 4%) scale(0.92) rotate(240deg); }
                100% { transform: translate(0px, 0px) scale(1) rotate(360deg); }
            }
            .animate-fluid-blur {
                animation: fluid-spatial-morph 22s ease-in-out infinite;
            }

            /* Premium Kinetic Radial Shimmer for Primary Interactive Elements */
            @keyframes interactive-shimmer {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .animate-btn-shimmer {
                background-size: 200% auto;
                animation: interactive-shimmer 6s linear infinite;
            }
        </style>
    </head>
    <body class="antialiased bg-[#07090e] text-[#f5f5f7] min-h-screen flex flex-col justify-between overflow-x-hidden relative selection:bg-indigo-500/30">
        
        <!-- Background Layer: Ultra-Diffusion Spatial Light Plates -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-[20%] left-1/4 w-[85vw] h-[85vw] rounded-full bg-gradient-to-tr from-indigo-500/12 via-purple-600/6 to-transparent blur-[160px] animate-fluid-blur"></div>
            <div class="absolute top-[25%] -left-[15%] w-[65vw] h-[65vw] rounded-full bg-gradient-to-br from-cyan-400/10 via-blue-600/6 to-transparent blur-[180px] animate-fluid-blur" style="animation-delay: -7s;"></div>
            <!-- Fine Noise Overlay for Apple Texture Integrity -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff03_1px,transparent_1px)] [background-size:32px_32px] opacity-70"></div>
        </div>

        <!-- Header -->
        <header class="w-full max-w-7xl mx-auto px-6 sm:px-8 py-5 flex items-center justify-between relative z-10">
            <a href="/" class="flex items-center gap-3.5 group transition-all active:scale-[0.97] duration-200">
                <div class="w-10 h-10 flex items-center justify-center bg-white/[0.03] border border-white/[0.08] rounded-xl backdrop-blur-2xl shadow-[inset_0_1px_1px_rgba(255,255,255,0.1)]">
                    <x-application-logo class="w-5 h-5 text-white opacity-90 group-hover:scale-105 transition-transform duration-300" />
                </div>
                <div>
                    <span class="block text-sm font-semibold tracking-tight text-white/90">Smart Classroom</span>
                    <span class="block text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-500 mt-0.5">IoT Ecosystem</span>
                </div>
            </a>

            @if (Route::has('login'))
                <nav class="flex items-center gap-1 bg-white/[0.02] p-1 rounded-full border border-white/[0.04] backdrop-blur-xl">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-full bg-white text-black font-medium text-xs hover:bg-neutral-200 transition-all active:scale-[0.96]">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-medium text-neutral-400 hover:text-white transition-colors py-2 px-4 rounded-full">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-white/[0.05] hover:bg-white/[0.08] text-white border border-white/[0.06] font-medium text-xs transition-all active:scale-[0.96]">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Hero Element -->
        <main class="w-full max-w-7xl mx-auto px-6 sm:px-8 py-16 md:py-28 relative z-10 my-auto">
            <div class="max-w-4xl mx-auto text-center flex flex-col items-center justify-center">
                
                <!-- Modern Minimal Capsule Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-gradient-to-r from-white/[0.04] to-white/[0.01] border border-white/[0.06] rounded-full backdrop-blur-xl mb-10 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-neutral-400">ESP32 + Laravel IoT Core</span>
                </div>

                <!-- Bold Cinematic Typography Style -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-bold tracking-[-0.03em] text-white leading-[1.08] mb-8">
                    Next-Gen Automation for <br>
                    <span class="bg-gradient-to-b from-white via-[#f5f5f7] to-[#86868b] bg-clip-text text-transparent">Smart Classrooms</span>
                </h1>

                <!-- Khmer Paragraph: Tailored Line-Height Metrics -->
                <p class="text-neutral-400 text-base sm:text-lg leading-[1.85] max-w-2xl font-light mb-12 px-4 tracking-normal opacity-95">
                    ប្រព័ន្ធបរិស្ថានស្វ័យប្រវត្តិ និងរឹងមាំមួយ ដែលតាមដានទិន្នន័យទូរវាស់ចម្ងាយ (telemetry) ក្នុងថ្នាក់រៀនតាមពេលវេលាជាក់ស្តែង ត្រួតពិនិត្យវត្តមានសកម្មរបស់សិស្ស និងបញ្ជាឱ្យឧបករណ៍វៃឆ្លាតដំណើរការផ្អែកលើការកំណត់ទុកជាមុនដើម្បីសន្សំសំចៃថាមពល។
                </p>

                <!-- Action Button Cluster with Motion Graphics -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="animate-btn-shimmer w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-white via-neutral-100 to-white text-black font-semibold text-sm shadow-[0_4px_24px_rgba(255,255,255,0.08)] hover:shadow-[0_4px_32px_rgba(255,255,255,0.15)] hover:scale-[1.02] active:scale-[0.97] transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                            Open Hub Dashboard
                        </a>
                    @else
                        <!-- Primary Button: Micro-Gradient Shimmer Fluid Transition -->
                        <a href="{{ route('login') }}" class="animate-btn-shimmer w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-neutral-50 via-white to-neutral-200 text-black font-semibold text-sm shadow-[0_4px_20px_rgba(255,255,255,0.06)] hover:shadow-[0_8px_30px_rgba(255,255,255,0.14)] hover:scale-[1.02] active:scale-[0.97] transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">
                            Access Dashboard Portal
                        </a>
                        
                        <!-- Secondary Button: High-Isolation Glass Morphism with Inner Stroke Glow -->
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white/[0.03] hover:bg-white/[0.06] text-white border border-white/[0.08] font-medium text-sm transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:scale-[1.02] active:scale-[0.97] backdrop-blur-xl shadow-[inset_0_1px_1px_rgba(255,255,255,0.05)] hover:border-white/[0.15]">
                            Create Device Account
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Bento Layout Capabilities System -->
            <div class="mt-40 pt-24 border-t border-white/[0.04]">
                <div class="max-w-2xl mx-auto text-center mb-16">
                    <h2 class="text-2xl sm:text-3xl font-semibold tracking-[-0.02em] text-white">Ecosystem Capabilities</h2>
                    <p class="text-neutral-500 text-sm mt-3 font-light">Seamless orchestration between real-time microcontrollers and software layers.</p>
                </div>

                <!-- Bento Elements Layout -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Box 1 -->
                    <div class="group p-8 bg-gradient-to-b from-white/[0.02] to-transparent border border-white/[0.04] rounded-[24px] hover:border-white/[0.1] transition-all duration-300 backdrop-blur-xl shadow-[inset_0_1px_0_rgba(255,255,255,0.02)]">
                        <div class="w-10 h-10 text-indigo-400 flex items-center justify-center mb-6 bg-white/[0.02] border border-white/[0.06] rounded-xl group-hover:scale-105 group-hover:text-indigo-300 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-white/90 mb-2">Live Telemetry Feeds</h3>
                        <p class="text-[12px] text-neutral-400 leading-relaxed font-light opacity-85">Streams temperature, humidity, and CO2 indices continuously from ESP32 nodes.</p>
                    </div>

                    <!-- Box 2 -->
                    <div class="group p-8 bg-gradient-to-b from-white/[0.02] to-transparent border border-white/[0.04] rounded-[24px] hover:border-white/[0.1] transition-all duration-300 backdrop-blur-xl shadow-[inset_0_1px_0_rgba(255,255,255,0.02)]">
                        <div class="w-10 h-10 text-cyan-400 flex items-center justify-center mb-6 bg-white/[0.02] border border-white/[0.06] rounded-xl group-hover:scale-105 group-hover:text-cyan-300 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-white/90 mb-2">Appliance Controls</h3>
                        <p class="text-[12px] text-neutral-400 leading-relaxed font-light opacity-85">Instantly toggle relay modules to control light fixtures, fans, and environmental configurations.</p>
                    </div>

                    <!-- Box 3 -->
                    <div class="group p-8 bg-gradient-to-b from-white/[0.02] to-transparent border border-white/[0.04] rounded-[24px] hover:border-white/[0.1] transition-all duration-300 backdrop-blur-xl shadow-[inset_0_1px_0_rgba(255,255,255,0.02)]">
                        <div class="w-10 h-10 text-emerald-400 flex items-center justify-center mb-6 bg-white/[0.02] border border-white/[0.06] rounded-xl group-hover:scale-105 group-hover:text-emerald-300 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-white/90 mb-2">Automated Savings</h3>
                        <p class="text-[12px] text-neutral-400 leading-relaxed font-light opacity-85">Integrated motion sensors trigger smart power rules when learning spaces remain empty.</p>
                    </div>

                    <!-- Box 4 -->
                    <div class="group p-8 bg-gradient-to-b from-white/[0.02] to-transparent border border-white/[0.04] rounded-[24px] hover:border-white/[0.1] transition-all duration-300 backdrop-blur-xl shadow-[inset_0_1px_0_rgba(255,255,255,0.02)]">
                        <div class="w-10 h-10 text-amber-400 flex items-center justify-center mb-6 bg-white/[0.02] border border-white/[0.06] rounded-xl group-hover:scale-105 group-hover:text-amber-300 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-white/90 mb-2">Historical Logs</h3>
                        <p class="text-[12px] text-neutral-400 leading-relaxed font-light opacity-85">Logs telemetry metrics persistently for usage graphs and optimization charts.</p>
                    </div>
                </div>
            </div>

            <!-- Specifications Node Panel -->
            <div class="mt-20 pt-10 border-t border-white/[0.04] flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h4 class="text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-500">Hardware Layer</h4>
                    <div class="flex flex-wrap gap-2 mt-3 text-neutral-400 text-xs">
                        <span class="px-3 py-1.5 bg-white/[0.01] border border-white/[0.04] rounded-xl backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.01)]">ESP32 NodeMCU</span>
                        <span class="px-3 py-1.5 bg-white/[0.01] border border-white/[0.04] rounded-xl backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.01)]">DHT22 Module</span>
                        <span class="px-3 py-1.5 bg-white/[0.01] border border-white/[0.04] rounded-xl backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.01)]">MQ135 Node</span>
                    </div>
                </div>
                <div>
                    <h4 class="text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-500">Software Core</h4>
                    <div class="flex flex-wrap gap-2 mt-3 text-neutral-400 text-xs">
                        <span class="px-3 py-1.5 bg-white/[0.01] border border-white/[0.04] rounded-xl backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.01)]">Laravel 12 Structure</span>
                        <span class="px-3 py-1.5 bg-white/[0.01] border border-white/[0.04] rounded-xl backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.01)]">Tailwind Engine</span>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto px-6 sm:px-8 py-8 border-t border-white/[0.04] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500 relative z-10">
            <p>&copy; {{ date('Y') }} Smart Classroom Hub. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition-colors">Documentation</a>
                <a href="#" class="hover:text-white transition-colors">Privacy Architecture</a>
            </div>
        </footer>

    </body>
</html>