<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="logo-grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#6366f1" />
            <stop offset="50%" stop-color="#4f46e5" />
            <stop offset="100%" stop-color="#06b6d4" />
        </linearGradient>
        <filter id="logo-glow" x="-20%" y="-20%" width="140%" height="140%">
            <feGaussianBlur stdDeviation="2" result="blur" />
            <feComposite in="SourceGraphic" in2="blur" operator="over" />
        </filter>
    </defs>
    
    <!-- Outer hexagonal tech ring -->
    <path d="M32 4L56 18V46L32 60L8 46V18L32 4Z" stroke="url(#logo-grad)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-80" />
    
    <!-- Inner glowing tech pattern -->
    <path d="M32 10L50 20.5V43.5L32 54L14 43.5V20.5L32 10Z" fill="url(#logo-grad)" fill-opacity="0.1" stroke="url(#logo-grad)" stroke-width="1" stroke-dasharray="2 4" />
    
    <!-- Wifi/Signal Waves (Smart IoT connection) -->
    <path d="M22 20C26 16.5 38 16.5 42 20" stroke="url(#logo-grad)" stroke-width="2.5" stroke-linecap="round" class="animate-pulse" />
    <path d="M26 24C28.5 21.5 35.5 21.5 38 24" stroke="url(#logo-grad)" stroke-width="2" stroke-linecap="round" />

    <!-- Modern Classroom Graduation Cap & Board combination -->
    <g filter="url(#logo-glow)">
        <!-- Cap Top (Diamond) -->
        <path d="M32 24L48 31L32 38L16 31L32 24Z" fill="url(#logo-grad)" />
        
        <!-- Cap Under / Board stand -->
        <path d="M23 35.5V41C23 43 27 44.5 32 44.5C37 44.5 41 43 41 41V35.5" stroke="url(#logo-grad)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
        
        <!-- Tassel -->
        <path d="M43 31.5V39M43 39L41 37.5M43 39L45 37.5" stroke="#06b6d4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        
        <!-- Core node / Spark -->
        <circle cx="32" cy="31" r="2.5" fill="#ffffff" />
    </g>
</svg>

