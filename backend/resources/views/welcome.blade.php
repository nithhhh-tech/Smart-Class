<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Smart Classroom') }}</title>

        <!-- Google Fonts: Kantumruy Pro (Khmer/body), Space Grotesk (display), JetBrains Mono (technical labels) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Kantumruy Pro', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            }
            .font-display {
                font-family: 'Space Grotesk', 'Kantumruy Pro', sans-serif;
            }
            .font-mono {
                font-family: 'JetBrains Mono', ui-monospace, monospace;
            }

            /* Tech brand blue blueprint grid */
            .blueprint-grid {
                background-image:
                    linear-gradient(rgba(59, 130, 246, 0.04) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(59, 130, 246, 0.04) 1px, transparent 1px),
                    linear-gradient(rgba(59, 130, 246, 0.09) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(59, 130, 246, 0.09) 1px, transparent 1px);
                background-size: 16px 16px, 16px 16px, 96px 96px, 96px 96px;
                background-position: -1px -1px, -1px -1px, -1px -1px, -1px -1px;
            }

            /* Drafting corner ticks - Redesigned to Brand Cyan/Blue */
            .tick-corners { position: relative; }
            .tick-corners::before,
            .tick-corners::after,
            .tick-corners > .tick-tl,
            .tick-corners > .tick-tr {
                content: "";
                position: absolute;
                width: 18px;
                height: 18px;
                border-color: rgba(6, 182, 212, 0.6);
            }
            .tick-corners::before { top: -1px; left: -1px; border-top: 1.5px solid; border-left: 1.5px solid; }
            .tick-corners::after { bottom: -1px; right: -1px; border-bottom: 1.5px solid; border-right: 1.5px solid; }

            /* Grid dash movement motion */
            @keyframes dash-flow {
                to { stroke-dashoffset: -24; }
            }
            .schema-line {
                stroke-dasharray: 4 5;
                animation: dash-flow 2.5s linear infinite;
            }

            /* Enhanced Pulse Motion */
            @keyframes ping-soft {
                0%, 100% { opacity: 1; transform: scale(1); filter: drop-shadow(0 0 2px rgba(6,182,212,0.8)); }
                50% { opacity: 0.4; transform: scale(1.5); filter: drop-shadow(0 0 8px rgba(59,130,246,0.9)); }
            }
            .node-pulse { animation: ping-soft 2s ease-in-out infinite; }

            /* Scanning radar gradient motion line */
            @keyframes scan-line {
                0% { top: 0%; opacity: 0; }
                10% { opacity: 0.5; }
                90% { opacity: 0.5; }
                100% { top: 100%; opacity: 0; }
            }
            .radar-scan {
                position: absolute;
                height: 2px;
                width: 100%;
                background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.4), transparent);
                animation: scan-line 6s linear infinite;
            }

            @media (prefers-reduced-motion: reduce) {
                .schema-line, .node-pulse, .radar-scan { animation: none !important; }
            }
        </style>
    </head>
    <body class="antialiased bg-[#030712] text-[#002879] min-h-screen flex flex-col justify-between overflow-x-hidden relative selection:bg-blue-500/30">

        <!-- Blueprint backdrop + Gradient Blurs -->
        <div class="fixed inset-0 blueprint-grid pointer-events-none z-0"></div>
        <div class="fixed inset-0 pointer-events-none z-0 bg-[radial-gradient(circle_at_top,rgba(29,78,216,0.15)_0%,#030712_70%)]"></div>
        <div class="fixed top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full pointer-events-none z-0 bg-blue-600/5 blur-[120px]"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full pointer-events-none z-0 bg-cyan-600/5 blur-[120px]"></div>

        <!-- Header -->
        <header class="w-full max-w-6xl mx-auto px-6 sm:px-8 py-6 flex items-center justify-between relative z-10 border-b border-blue-950/40 backdrop-blur-sm bg-[#030712]/20">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-9 h-9 flex items-center justify-center border border-blue-500/30 rounded-sm bg-blue-950/40 group-hover:border-cyan-400/60 transition-colors duration-300 shadow-[0_0_15px_rgba(29,78,216,0.1)]">
                    <x-application-logo class="w-4.5 h-4.5 text-blue-400 opacity-90 group-hover:text-cyan-400 transition-colors duration-300" />
                </div>
                <div class="leading-tight">
                    <span class="block text-sm font-semibold tracking-tight text-white font-display group-hover:text-blue-400 transition-colors duration-300">Smart Classroom</span>
                    <span class="block text-[9.5px] font-mono uppercase tracking-[0.18em] text-blue-400/70">Rev. IoT-04 &middot; Laravel Core</span>
                </div>
            </a>

            @if (Route::has('login'))
                <nav class="flex items-center gap-2 font-mono text-xs">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 border border-blue-500 text-blue-400 bg-blue-950/20 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 tracking-wide shadow-[0_0_15px_rgba(59,130,246,0.1)]">
                            [ OPEN DASHBOARD ]
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-400 hover:text-blue-400 transition-colors py-2 px-3 tracking-wide">
                            LOG_IN
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 border border-blue-900 text-slate-200 bg-blue-950/10 hover:border-cyan-500 hover:text-cyan-400 transition-all duration-300 tracking-wide">
                                REGISTER →
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Hero -->
        <main class="w-full max-w-6xl mx-auto px-6 sm:px-8 py-16 md:py-20 relative z-10">

            <div class="tick-corners border border-blue-950/60 px-6 sm:px-12 py-14 sm:py-16 bg-[#091124]/40 backdrop-blur-md relative overflow-hidden shadow-[2xl] shadow-blue-950/20">
                <div class="radar-scan"></div>
                <div class="max-w-3xl relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 border border-blue-800/60 bg-blue-950/30 font-mono text-[10px] uppercase tracking-[0.16em] text-blue-300/90 mb-8 rounded-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 node-pulse"></span>
                        ESP32 &rarr; MQTT &rarr; Laravel &middot; Schematic v2
                    </div>

                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-[-0.02em] text-white leading-[1.12] mb-7">
                        A blueprint for<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-cyan-400">automated</span> classrooms.
                    </h1>

                    <p class="text-slate-300 text-base sm:text-lg leading-[1.9] max-w-xl font-light mb-10">
                        ប្រប្រព័ន្ធបរិស្ថានស្វ័យប្រវត្តិ និងរឹងមាំមួយ ដែលតាមដានទិន្នន័យទូរវាស់ចម្ងាយ (telemetry) ក្នុងថ្នាក់រៀនតាមពេលវេលាជាក់ស្តែង ត្រួតពិនិត្យវត្តមានសកម្មរបស់សិស្ស និងបញ្ជាឱ្យឧបករណ៍វៃឆ្លាតដំណើរការផ្អែកលើការកំណត់ទុកជាមុនដើម្បីសន្សំសំចៃថាមពល។
                    </p>

                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-7 py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-display font-semibold text-sm hover:from-blue-500 hover:to-cyan-500 transition-all duration-300 shadow-[0_4px_20px_rgba(29,78,216,0.3)] hover:shadow-[0_4px_25px_rgba(6,182,212,0.4)] hover:scale-[1.02]">
                                Open Hub Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-7 py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-display font-semibold text-sm hover:from-blue-500 hover:to-cyan-500 transition-all duration-300 shadow-[0_4px_20px_rgba(29,78,216,0.3)] hover:shadow-[0_4px_25px_rgba(6,182,212,0.4)] hover:scale-[1.02]">
                                Access Dashboard Portal
                            </a>
                            <a href="{{ route('register') }}" class="px-7 py-3.5 border border-blue-800/80 bg-blue-950/10 text-slate-200 font-display font-medium text-sm hover:border-cyan-400 hover:text-cyan-400 transition-all duration-300 backdrop-blur-sm">
                                Create Device Account
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Signature element: classroom sensor schematic -->
            <div class="mt-6 border border-blue-950/60 bg-[#091124]/40 backdrop-blur-md p-6 sm:p-10 shadow-xl">
                <div class="flex items-center justify-between mb-6 font-mono text-[10px] uppercase tracking-[0.16em] text-blue-400/70">
                    <span>Fig. 01 &mdash; Node Layout / Classroom A-101</span>
                    <span class="hidden sm:inline">Scale N.T.S.</span>
                </div>

                <svg viewBox="0 0 800 300" class="w-full h-auto" role="img" aria-label="Schematic diagram of classroom sensor nodes wired to a central hub">
                    <!-- room outline -->
                    <rect x="40" y="30" width="720" height="240" fill="none" stroke="#1e3a8a" stroke-width="1.5" opacity="0.6" />
                    <rect x="40" y="30" width="720" height="240" fill="none" stroke="#2563eb" stroke-width="10" opacity="0.1" />

                    <!-- door -->
                    <path d="M 40 230 A 40 40 0 0 1 80 270" fill="none" stroke="#2563eb" stroke-width="1" stroke-dasharray="3 3" opacity="0.5" />

                    <!-- wiring to central hub -->
                    <g stroke="#3b82f6" stroke-width="1.25" class="schema-line" fill="none" opacity="0.8">
                        <path d="M 400 150 L 150 80" />
                        <path d="M 400 150 L 650 80" />
                        <path d="M 400 150 L 150 220" />
                        <path d="M 400 150 L 650 220" />
                        <path d="M 400 150 L 400 60" />
                    </g>

                    <!-- hub -->
                    <rect x="372" y="128" width="56" height="44" rx="2" fill="#030712" stroke="#2563eb" stroke-width="1.5" />
                    <text x="400" y="154" text-anchor="middle" fill="#60a5fa" font-size="9" font-family="JetBrains Mono, monospace" font-weight="600">HUB</text>

                    <!-- sensor nodes -->
                    <g font-family="JetBrains Mono, monospace" font-size="9" fill="#94a3b8">
                        <circle cx="150" cy="80" r="5" fill="#030712" stroke="#06b6d4" stroke-width="1.5" />
                        <circle cx="150" cy="80" r="2" fill="#06b6d4" class="node-pulse" />
                        <text x="150" y="65" font-weight="500">DHT22</text>
                        <text x="150" y="98" fill="#06b6d4" font-weight="500">TEMP / HUM</text>

                        <circle cx="650" cy="80" r="5" fill="#030712" stroke="#06b6d4" stroke-width="1.5" />
                        <circle cx="650" cy="80" r="2" fill="#06b6d4" class="node-pulse" style="animation-delay:.4s" />
                        <text x="650" y="65" text-anchor="end" font-weight="500">MQ135</text>
                        <text x="650" y="98" fill="#06b6d4" text-anchor="end" font-weight="500">AIR QUALITY</text>

                        <circle cx="150" cy="220" r="5" fill="#030712" stroke="#3b82f6" stroke-width="1.5" />
                        <circle cx="150" cy="220" r="2" fill="#3b82f6" class="node-pulse" style="animation-delay:.8s" />
                        <text x="150" y="240" font-weight="500">PIR</text>
                        <text x="150" y="252" fill="#3b82f6" font-weight="500">MOTION</text>

                        <circle cx="650" cy="220" r="5" fill="#030712" stroke="#3b82f6" stroke-width="1.5" />
                        <circle cx="650" cy="220" r="2" fill="#3b82f6" class="node-pulse" style="animation-delay:1.2s" />
                        <text x="650" y="240" text-anchor="end" font-weight="500">RELAY</text>
                        <text x="650" y="252" fill="#3b82f6" text-anchor="end" font-weight="500">LIGHTS / FANS</text>

                        <circle cx="400" cy="60" r="5" fill="#030712" stroke="#cbd5e1" stroke-width="1.5" />
                        <text x="400" y="45" text-anchor="middle" fill="#cbd5e1" font-weight="500">ESP32</text>
                    </g>
                </svg>
            </div>

            <!-- Spec sheet: capabilities -->
            <div class="mt-24">
                <div class="flex items-baseline justify-between mb-8 border-b border-blue-950/60 pb-4">
                    <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-[-0.01em] text-white">Ecosystem Spec Sheet</h2>
                    <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-blue-400/70 hidden sm:inline">04 modules</span>
                </div>

                <div class="divide-y divide-blue-950/40 border-t border-blue-950/60">

                    <div class="group grid sm:grid-cols-[80px_1fr_2fr] gap-3 sm:gap-8 py-7 items-start hover:bg-blue-950/10 transition-colors duration-300 px-2 rounded-sm">
                        <span class="font-mono text-blue-400 group-hover:text-cyan-400 transition-colors duration-300 text-sm">01</span>
                        <h3 class="font-display text-white group-hover:text-blue-400 transition-colors duration-300 font-medium text-base">Live Telemetry Feeds</h3>
                        <p class="text-[13px] text-slate-400 group-hover:text-slate-300 transition-colors duration-300 leading-relaxed font-light">Streams temperature, humidity, and CO2 indices continuously from ESP32 nodes to the hub in real time.</p>
                    </div>

                    <div class="group grid sm:grid-cols-[80px_1fr_2fr] gap-3 sm:gap-8 py-7 items-start hover:bg-blue-950/10 transition-colors duration-300 px-2 rounded-sm">
                        <span class="font-mono text-blue-400 group-hover:text-cyan-400 transition-colors duration-300 text-sm">02</span>
                        <h3 class="font-display text-white group-hover:text-blue-400 transition-colors duration-300 font-medium text-base">Appliance Controls</h3>
                        <p class="text-[13px] text-slate-400 group-hover:text-slate-300 transition-colors duration-300 leading-relaxed font-light">Toggle relay modules instantly to control light fixtures, fans, and other environmental hardware.</p>
                    </div>

                    <div class="group grid sm:grid-cols-[80px_1fr_2fr] gap-3 sm:gap-8 py-7 items-start hover:bg-blue-950/10 transition-colors duration-300 px-2 rounded-sm">
                        <span class="font-mono text-blue-400 group-hover:text-cyan-400 transition-colors duration-300 text-sm">03</span>
                        <h3 class="font-display text-white group-hover:text-blue-400 transition-colors duration-300 font-medium text-base">Automated Savings</h3>
                        <p class="text-[13px] text-slate-400 group-hover:text-slate-300 transition-colors duration-300 leading-relaxed font-light">Motion sensors trigger smart power rules the moment a learning space is left empty.</p>
                    </div>

                    <div class="group grid sm:grid-cols-[80px_1fr_2fr] gap-3 sm:gap-8 py-7 items-start hover:bg-blue-950/10 transition-colors duration-300 px-2 rounded-sm">
                        <span class="font-mono text-blue-400 group-hover:text-cyan-400 transition-colors duration-300 text-sm">04</span>
                        <h3 class="font-display text-white group-hover:text-blue-400 transition-colors duration-300 font-medium text-base">Historical Logs</h3>
                        <p class="text-[13px] text-slate-400 group-hover:text-slate-300 transition-colors duration-300 leading-relaxed font-light">Every reading is logged persistently, feeding usage graphs and optimization charts over time.</p>
                    </div>

                </div>
            </div>

            <!-- Hardware / software manifest -->
            <div class="mt-20 pt-8 border-t border-blue-950/60 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div>
                    <h4 class="font-mono text-[10px] uppercase tracking-[0.18em] text-blue-400/70 mb-3">Hardware Layer</h4>
                    <div class="flex flex-wrap gap-2 text-slate-300 text-xs font-mono">
                        <span class="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">ESP32 NodeMCU</span>
                        <span class="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">DHT22 Module</span>
                        <span class="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">MQ135 Node</span>
                    </div>
                </div>
                <div>
                    <h4 class="font-mono text-[10px] uppercase tracking-[0.18em] text-blue-400/70 mb-3">Software Core</h4>
                    <div class="flex flex-wrap gap-2 text-slate-300 text-xs font-mono">
                        <span class="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">Laravel 12</span>
                        <span class="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">Tailwind Engine</span>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-6xl mx-auto px-6 sm:px-8 py-8 border-t border-blue-950/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-blue-400/60 font-mono relative z-10 bg-[#030712]/40 backdrop-blur-sm">
            <p>&copy; {{ date('Y') }} Smart Classroom Hub. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-cyan-400 transition-colors">Documentation</a>
                <a href="#" class="hover:text-cyan-400 transition-colors">Privacy Architecture</a>
            </div>
        </footer>

    </body>
</html>