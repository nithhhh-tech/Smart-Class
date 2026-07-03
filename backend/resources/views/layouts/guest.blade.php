<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Smart Classroom') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Styling & Micro-animations -->
        <style>
            :root {
                --ink: #14181D;
                --panel: #1E252C;
                --copper: #C97A4A;
                --circuit: #4FD1C5;
                --amber: #F2B134;
                --paper: #EDEAE3;
            }
            body {
                font-family: 'IBM Plex Sans', sans-serif;
            }
            .heading-font {
                font-family: 'Space Grotesk', sans-serif;
            }
            .mono {
                font-family: 'JetBrains Mono', monospace;
            }
            .blueprint-grid {
                background-size: 28px 28px;
                background-image:
                    linear-gradient(to right, rgba(79, 209, 197, 0.08) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(79, 209, 197, 0.08) 1px, transparent 1px);
            }
            @keyframes trace-flow {
                to { stroke-dashoffset: -32; }
            }
            .trace-line {
                stroke-dasharray: 5 5;
                animation: trace-flow 2.8s linear infinite;
            }
            @keyframes led-pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.35; }
            }
            .led { animation: led-pulse 2.4s ease-in-out infinite; }
            @keyframes cursor-blink {
                0%, 50% { opacity: 1; }
                51%, 100% { opacity: 0; }
            }
            .cursor-blink { animation: cursor-blink 1s step-end infinite; }
            @media (prefers-reduced-motion: reduce) {
                .trace-line, .led, .cursor-blink { animation: none; }
            }
        </style>
    </head>
    <body class="h-full text-slate-900 dark:text-[#EDEAE3] antialiased bg-slate-50 dark:bg-[#14181D] transition-colors duration-300">
        <div class="min-h-screen flex flex-col lg:flex-row overflow-x-hidden">

            <!-- Left Pane: Access Panel -->
            <div class="w-full lg:w-[44%] xl:w-[40%] flex flex-col justify-between p-6 sm:p-10 lg:p-14 bg-white dark:bg-[#1E252C] shadow-2xl relative z-10 border-r border-slate-100 dark:border-white/5">

                <!-- Header -->
                <div class="flex items-center justify-between mb-10">
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="p-2.5 bg-slate-100 dark:bg-black/30 rounded-lg border border-slate-200 dark:border-[#C97A4A]/30 group-hover:border-[#C97A4A] transition-colors">
                            <x-application-logo class="w-8 h-8" />
                        </div>
                        <div>
                            <span class="heading-font text-lg font-bold tracking-tight text-slate-800 dark:text-[#EDEAE3]">SMART CLASSROOM</span>
                            <span class="mono block text-[10px] font-medium uppercase tracking-[0.15em] text-[#C97A4A]">IoT Control Access</span>
                        </div>
                    </a>
                </div>

                <!-- Main Form Slot -->
                <div class="my-auto py-4">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="mt-10 pt-6 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-400 dark:text-slate-400">
                    <div class="flex items-center gap-2 mono">
                        <span class="relative flex h-2 w-2">
                            <span class="inline-flex h-full w-full rounded-full bg-emerald-400 led"></span>
                        </span>
                        <span>SYSTEM ONLINE</span>
                    </div>
                    <div class="flex gap-4">
                        <a href="#" class="hover:text-[#C97A4A] transition-colors">Privacy</a>
                        <a href="#" class="hover:text-[#C97A4A] transition-colors">Terms</a>
                    </div>
                </div>
            </div>

            <!-- Right Pane: Schematic -->
            <div class="hidden lg:flex flex-1 relative bg-[#14181D] flex-col items-center justify-center p-10 overflow-hidden blueprint-grid">

                <!-- corner frame ticks -->
                <div class="absolute top-6 left-6 w-6 h-6 border-t-2 border-l-2 border-[#4FD1C5]/40"></div>
                <div class="absolute top-6 right-6 w-6 h-6 border-t-2 border-r-2 border-[#4FD1C5]/40"></div>
                <div class="absolute bottom-6 left-6 w-6 h-6 border-b-2 border-l-2 border-[#4FD1C5]/40"></div>
                <div class="absolute bottom-6 right-6 w-6 h-6 border-b-2 border-r-2 border-[#4FD1C5]/40"></div>

                <!-- firmware link line -->
                <div class="absolute top-6 left-16 right-16 flex items-center justify-between mono text-[10px] uppercase tracking-wider text-[#4FD1C5]/70">
                    <span>FIRMWARE LINK: ESP32-WROOM-32</span>
                    <span>BAUD 115200</span>
                </div>

                <!-- Schematic -->
                <div class="relative w-full max-w-xl">
                    <svg viewBox="0 0 640 420" class="w-full h-auto overflow-visible">
                        <!-- room outline -->
                        <rect x="40" y="30" width="560" height="320" fill="none" stroke="rgba(79,209,197,0.25)" stroke-width="1.5" />
                        <text x="56" y="22" font-family="JetBrains Mono, monospace" font-size="11" fill="rgba(237,234,227,0.5)" letter-spacing="1">ROOM 302 — FLOOR REF SC-IOT-02</text>

                        <!-- trace lines from hub to nodes -->
                        <line x1="320" y1="190" x2="130" y2="90" stroke="var(--circuit)" stroke-width="1.5" class="trace-line" opacity="0.6"/>
                        <line x1="320" y1="190" x2="510" y2="90" stroke="var(--circuit)" stroke-width="1.5" class="trace-line" opacity="0.6"/>
                        <line x1="320" y1="230" x2="130" y2="300" stroke="var(--copper)" stroke-width="1.5" class="trace-line" opacity="0.6"/>
                        <line x1="320" y1="230" x2="510" y2="300" stroke="var(--copper)" stroke-width="1.5" class="trace-line" opacity="0.6"/>

                        <!-- hub: ESP32 -->
                        <rect x="280" y="185" width="80" height="50" rx="4" fill="rgba(30,37,44,0.9)" stroke="var(--circuit)" stroke-width="1.5"/>
                        <text x="320" y="215" text-anchor="middle" font-family="JetBrains Mono, monospace" font-size="11" fill="#EDEAE3" font-weight="600">ESP32</text>
                        <g stroke="var(--circuit)" stroke-width="1.5" opacity="0.7">
                          <line x1="280" y1="195" x2="270" y2="195"/>
                          <line x1="280" y1="210" x2="270" y2="210"/>
                          <line x1="280" y1="225" x2="270" y2="225"/>
                          <line x1="360" y1="195" x2="370" y2="195"/>
                          <line x1="360" y1="210" x2="370" y2="210"/>
                          <line x1="360" y1="225" x2="370" y2="225"/>
                        </g>

                        <!-- node: TEMP/HUMID -->
                        <g>
                          <rect x="123" y="83" width="14" height="14" fill="rgba(30,37,44,0.9)" stroke="var(--circuit)" stroke-width="1.5"/>
                          <circle cx="130" cy="90" r="2.5" fill="#34D399" class="led"/>
                          <text x="148" y="86" font-family="JetBrains Mono, monospace" font-size="10" fill="#EDEAE3">DHT22</text>
                          <text x="148" y="98" font-family="JetBrains Mono, monospace" font-size="9" fill="rgba(237,234,227,0.55)">TEMP / HUMID</text>
                        </g>

                        <!-- node: PIR -->
                        <g>
                          <rect x="503" y="83" width="14" height="14" fill="rgba(30,37,44,0.9)" stroke="var(--circuit)" stroke-width="1.5"/>
                          <circle cx="510" cy="90" r="2.5" fill="#34D399" class="led"/>
                          <text x="468" y="86" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="10" fill="#EDEAE3">PIR-01</text>
                          <text x="468" y="98" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="9" fill="rgba(237,234,227,0.55)">MOTION SENSE</text>
                        </g>

                        <!-- node: RELAY 1 Lights -->
                        <g>
                          <rect x="123" y="293" width="14" height="14" fill="rgba(30,37,44,0.9)" stroke="var(--copper)" stroke-width="1.5"/>
                          <circle cx="130" cy="300" r="2.5" fill="var(--amber)" class="led"/>
                          <text x="148" y="297" font-family="JetBrains Mono, monospace" font-size="10" fill="#EDEAE3">RELAY 1</text>
                          <text x="148" y="309" font-family="JetBrains Mono, monospace" font-size="9" fill="rgba(237,234,227,0.55)">LIGHTING — ON</text>
                        </g>

                        <!-- node: RELAY 2 Fan -->
                        <g>
                          <rect x="503" y="293" width="14" height="14" fill="rgba(30,37,44,0.9)" stroke="var(--copper)" stroke-width="1.5"/>
                          <circle cx="510" cy="300" r="2.5" fill="rgba(148,163,184,0.6)"/>
                          <text x="468" y="297" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="10" fill="#EDEAE3">RELAY 2</text>
                          <text x="468" y="309" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="9" fill="rgba(237,234,227,0.55)">FAN — STANDBY</text>
                        </g>

                        <!-- title block -->
                        <g font-family="JetBrains Mono, monospace" font-size="9">
                          <rect x="430" y="270" width="160" height="64" fill="rgba(20,24,29,0.6)" stroke="rgba(237,234,227,0.2)" stroke-width="1"/>
                          <line x1="430" y1="288" x2="590" y2="288" stroke="rgba(237,234,227,0.2)"/>
                          <line x1="430" y1="306" x2="590" y2="306" stroke="rgba(237,234,227,0.2)"/>
                          <text x="438" y="282" fill="rgba(237,234,227,0.6)">PROJECT: SMART CLASSROOM</text>
                          <text x="438" y="300" fill="rgba(237,234,227,0.6)">SHEET: AUTH-01</text>
                          <text x="438" y="318" fill="rgba(237,234,227,0.6)">REV: A · SCALE NTS</text>
                        </g>
                    </svg>
                </div>

                <!-- Serial monitor strip -->
                <div class="mt-8 w-full max-w-xl rounded-md border border-[#4FD1C5]/20 bg-black/40 px-4 py-3 mono text-[11px] text-[#4FD1C5]/90 overflow-hidden whitespace-nowrap">
                    <span class="text-[#EDEAE3]/40">&gt;</span> TEMP 22.8C&nbsp; HUM 48%&nbsp; CO2 380ppm&nbsp; RELAY1:ON&nbsp; RELAY2:OFF&nbsp; UPTIME 14:22:08<span class="cursor-blink">_</span>
                </div>

                <!-- Caption -->
                <div class="mt-8 text-center max-w-md">
                    <h2 class="heading-font text-xl font-semibold text-[#EDEAE3] mb-2">One ESP32 node. Every relay, sensor, and schedule.</h2>
                    <p class="text-sm text-slate-400 leading-relaxed">Sign in to read live sensor data, switch lights and fans by relay, and manage automation schedules for Room 302.</p>
                </div>

            </div>

        </div>
    </body>
</html>