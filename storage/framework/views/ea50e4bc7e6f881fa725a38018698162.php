<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                <span class="inline-block w-3.5 h-3.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <?php echo e(__('Smart Classroom Control Center')); ?>

            </h2>
            <div class="flex items-center gap-3">
                <label for="room-select" class="text-sm font-medium text-gray-600 dark:text-gray-400">Selected Room:</label>
                <select id="room-select" class="rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 transition duration-150">
                    <option value="" disabled selected>Loading rooms...</option>
                </select>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Live Telemetry Panel -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Temperature Card -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/10 to-orange-500/10 dark:from-amber-500/5 dark:to-orange-500/5 border border-amber-500/20 dark:border-amber-500/10 p-6 shadow-md transition duration-300 hover:shadow-lg">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2a3 3 0 0 0-3 3v8.17a5 5 0 1 0 6 0V5a3 3 0 0 0-3-3m0 2a1 1 0 0 1 1 1v8h-2V5a1 1 0 0 1 1-1z"/>
                        </svg>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-semibold tracking-wider text-amber-600 dark:text-amber-400 uppercase">Temperature</span>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                    </div>
                    <div class="flex items-baseline">
                        <span id="temp-value" class="text-5xl font-black text-gray-800 dark:text-gray-100 transition-all duration-500">--</span>
                        <span class="text-2xl font-medium text-gray-500 ml-1">°C</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-4" id="temp-status">Retrieving live temperature...</p>
                </div>

                <!-- Humidity Card -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 dark:from-blue-500/5 dark:to-indigo-500/5 border border-blue-500/20 dark:border-blue-500/10 p-6 shadow-md transition duration-300 hover:shadow-lg">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                        </svg>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-semibold tracking-wider text-blue-600 dark:text-blue-400 uppercase">Humidity</span>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                    </div>
                    <div class="flex items-baseline">
                        <span id="humidity-value" class="text-5xl font-black text-gray-800 dark:text-gray-100 transition-all duration-500">--</span>
                        <span class="text-2xl font-medium text-gray-500 ml-1">%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-4" id="humidity-status">Retrieving live humidity...</p>
                </div>

                <!-- Motion Card -->
                <div id="motion-card" class="relative overflow-hidden rounded-2xl bg-slate-100 dark:bg-slate-800/40 border border-gray-200 dark:border-gray-700/60 p-6 shadow-md transition-all duration-500 hover:shadow-lg">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                        </svg>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-semibold tracking-wider text-gray-600 dark:text-gray-400 uppercase">Motion Status</span>
                        <span class="flex h-3 w-3 relative">
                            <span id="motion-ping" class="absolute inline-flex h-full w-full rounded-full opacity-75 bg-slate-400"></span>
                            <span id="motion-dot" class="relative inline-flex rounded-full h-3 w-3 bg-slate-500"></span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="motion-value" class="text-4xl font-extrabold text-gray-800 dark:text-gray-100 transition-all duration-500">No Motion</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-5" id="motion-status">Room empty or inactive.</p>
                </div>
            </div>

            <!-- Device Controls and Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Quick Device Controls -->
                <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Device Quick Controls
                    </h3>
                    <div id="devices-container" class="space-y-4">
                        <div class="text-gray-500 dark:text-gray-400 text-sm py-4 text-center">Select a room to list devices.</div>
                    </div>
                </div>

                <!-- Live Stream Console / Status Logs -->
                <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Classroom Console Log
                        </h3>
                        <div id="logs-container" class="bg-gray-950 rounded-xl p-4 font-mono text-xs text-emerald-400 border border-gray-800 h-48 overflow-y-auto space-y-1.5 scrollbar-thin scrollbar-thumb-gray-800">
                            <div>[SYSTEM] Ready. Select a room to start monitoring.</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs text-gray-500">
                        <span>Last Update: <span id="last-update-time">Never</span></span>
                        <button id="refresh-btn" class="text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Active Alerts & Notifications -->
            <div class="bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/60 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Classroom Anomaly Alerts
                </h3>
                <div id="alerts-container" class="space-y-3">
                    <div class="text-gray-500 dark:text-gray-400 text-sm py-4 text-center">Select a classroom to view active alerts.</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Block -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roomSelect = document.getElementById('room-select');
            const tempValue = document.getElementById('temp-value');
            const tempStatus = document.getElementById('temp-status');
            const humidityValue = document.getElementById('humidity-value');
            const humidityStatus = document.getElementById('humidity-status');
            const motionCard = document.getElementById('motion-card');
            const motionValue = document.getElementById('motion-value');
            const motionStatus = document.getElementById('motion-status');
            const motionPing = document.getElementById('motion-ping');
            const motionDot = document.getElementById('motion-dot');
            const devicesContainer = document.getElementById('devices-container');
            const logsContainer = document.getElementById('logs-container');
            const alertsContainer = document.getElementById('alerts-container');
            const lastUpdateTime = document.getElementById('last-update-time');
            const refreshBtn = document.getElementById('refresh-btn');

            let pollingInterval = null;

            // Log output function
            function logMessage(text, type = 'info') {
                const date = new Date().toLocaleTimeString();
                const logEl = document.createElement('div');
                let colorClass = 'text-emerald-400';
                if (type === 'error') colorClass = 'text-rose-400';
                if (type === 'warn') colorClass = 'text-amber-400';
                if (type === 'success') colorClass = 'text-indigo-400';

                logEl.className = `${colorClass} py-0.5`;
                logEl.innerHTML = `<span>[${date}]</span> <span class="font-bold">${text}</span>`;
                logsContainer.appendChild(logEl);
                logsContainer.scrollTop = logsContainer.scrollHeight;
            }

            // Fetch rooms list
            axios.get('/api/rooms')
                .then(response => {
                    const rooms = response.data.data;
                    roomSelect.innerHTML = '<option value="" disabled selected>Choose a room...</option>';
                    rooms.forEach(room => {
                        const opt = document.createElement('option');
                        opt.value = room.id;
                        opt.textContent = `${room.name} (${room.location || 'No Location'})`;
                        roomSelect.appendChild(opt);
                    });
                    logMessage('Loaded available classrooms successfully.');
                })
                .catch(err => {
                    console.error(err);
                    logMessage('Failed to load classrooms.', 'error');
                });

            // Handle room selection change
            roomSelect.addEventListener('change', () => {
                const roomId = roomSelect.value;
                logMessage(`Switched classroom monitoring to Room ID ${roomId}.`, 'success');
                
                // Clear existing interval
                if (pollingInterval) clearInterval(pollingInterval);

                // Fetch immediately, then poll every 5 seconds
                fetchData(roomId);
                pollingInterval = setInterval(() => fetchData(roomId), 5000);
            });

            // Fetch data function
            function fetchData(roomId) {
                // Fetch summary telemetry
                axios.get(`/api/dashboard/summary?room_id=${roomId}`)
                    .then(response => {
                        const summary = response.data.data;
                        updateTelemetry(summary);
                    })
                    .catch(err => {
                        console.error(err);
                        logMessage(`Error fetching telemetry for room ${roomId}.`, 'error');
                    });

                // Fetch devices list
                axios.get(`/api/devices?room_id=${roomId}`)
                    .then(response => {
                        const devices = response.data.data;
                        updateDevicesList(devices);
                    })
                    .catch(err => {
                        console.error(err);
                        logMessage(`Error loading device controls.`, 'error');
                    });

                // Fetch alerts
                fetchAlerts(roomId);
            }

            // Fetch alerts list
            function fetchAlerts(roomId) {
                axios.get(`/api/alerts?room_id=${roomId}`)
                    .then(response => {
                        const alerts = response.data.data;
                        updateAlertsList(alerts, roomId);
                    })
                    .catch(err => {
                        console.error(err);
                        logMessage(`Error loading active alerts.`, 'error');
                    });
            }

            // Update UI alerts list
            function updateAlertsList(alerts, roomId) {
                if (alerts.length === 0) {
                    alertsContainer.innerHTML = '<div class="text-gray-500 dark:text-gray-400 text-sm py-4 text-center">No active anomalies detected. All systems operating normally.</div>';
                    return;
                }

                alertsContainer.innerHTML = '';
                alerts.forEach(alert => {
                    const row = document.createElement('div');
                    row.className = 'flex justify-between items-center p-4 bg-red-50/50 dark:bg-rose-950/10 border border-red-100 dark:border-rose-950/20 rounded-xl transition duration-150';
                    
                    const triggerDate = new Date(alert.triggered_at).toLocaleTimeString();
                    
                    row.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-rose-500/10 text-rose-500 dark:text-rose-400">
                                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-gray-200">${alert.message}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">${alert.type} • Triggered at ${triggerDate}</p>
                            </div>
                        </div>
                        <div>
                            <button 
                                onclick="dismissAlert(${alert.id}, ${roomId})"
                                class="px-3 py-1 bg-red-100 dark:bg-rose-950/30 text-red-700 dark:text-rose-400 rounded-lg hover:bg-red-200 dark:hover:bg-rose-950/50 text-xs font-semibold uppercase tracking-wider transition"
                            >
                                Dismiss
                            </button>
                        </div>
                    `;
                    alertsContainer.appendChild(row);
                });
            }

            // Dismiss active alert
            window.dismissAlert = function(id, roomId) {
                axios.delete(`/api/alerts/${id}`)
                    .then(() => {
                        fetchAlerts(roomId);
                        logMessage('Alert dismissed successfully.', 'success');
                    })
                    .catch(err => {
                        console.error(err);
                        logMessage('Failed to dismiss alert.', 'error');
                    });
            };

            // Update UI widgets
            function updateTelemetry(data) {
                const now = new Date();
                lastUpdateTime.textContent = now.toLocaleTimeString();

                if (data.temperature !== null && data.temperature !== undefined) {
                    tempValue.textContent = parseFloat(data.temperature).toFixed(1);
                    tempStatus.textContent = `Vents active. Healthy environment.`;
                } else {
                    tempValue.textContent = '--';
                    tempStatus.textContent = 'No records reported yet.';
                }

                if (data.humidity !== null && data.humidity !== undefined) {
                    humidityValue.textContent = parseFloat(data.humidity).toFixed(1);
                    humidityStatus.textContent = `Comfortable relative humidity index.`;
                } else {
                    humidityValue.textContent = '--';
                    humidityStatus.textContent = 'No records reported yet.';
                }

                if (data.motion !== null && data.motion !== undefined) {
                    const hasMotion = !!data.motion;
                    if (hasMotion) {
                        motionValue.textContent = 'Motion Detected';
                        motionStatus.textContent = 'Sensor active. Classroom occupied.';
                        
                        // Set red pulsing classes
                        motionCard.className = 'relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500/15 to-red-500/15 dark:from-rose-500/10 dark:to-red-500/10 border border-rose-500/30 dark:border-rose-500/20 p-6 shadow-md transition-all duration-500 hover:shadow-lg';
                        motionPing.className = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-500 opacity-75';
                        motionDot.className = 'relative inline-flex rounded-full h-3 w-3 bg-rose-600';
                        
                        // Log motion if active
                        logMessage('Motion trigger warning: Occupants detected in classroom.', 'warn');
                    } else {
                        motionValue.textContent = 'No Motion';
                        motionStatus.textContent = 'Classroom empty or inactive.';
                        
                        // Reset classes
                        motionCard.className = 'relative overflow-hidden rounded-2xl bg-slate-100 dark:bg-slate-800/40 border border-gray-200 dark:border-gray-700/60 p-6 shadow-md transition-all duration-500 hover:shadow-lg';
                        motionPing.className = 'absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75';
                        motionDot.className = 'relative inline-flex rounded-full h-3 w-3 bg-slate-500';
                    }
                } else {
                    motionValue.textContent = 'No Data';
                    motionStatus.textContent = 'No motion telemetry reported.';
                    
                    motionCard.className = 'relative overflow-hidden rounded-2xl bg-slate-100 dark:bg-slate-800/40 border border-gray-200 dark:border-gray-700/60 p-6 shadow-md transition-all duration-500 hover:shadow-lg';
                    motionPing.className = 'absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75';
                    motionDot.className = 'relative inline-flex rounded-full h-3 w-3 bg-slate-500';
                }
            }

            // Update Device Controller Cards
            function updateDevicesList(devices) {
                if (devices.length === 0) {
                    devicesContainer.innerHTML = '<div class="text-gray-500 dark:text-gray-400 text-sm py-4 text-center">No devices configured in this room.</div>';
                    return;
                }

                devicesContainer.innerHTML = '';
                devices.forEach(device => {
                    const row = document.createElement('div');
                    row.className = 'flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800/60 rounded-xl transition duration-150 hover:bg-gray-100 dark:hover:bg-gray-900/60';
                    
                    const isChecked = device.status;
                    const switchId = `device-${device.id}`;
                    
                    row.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg ${isChecked ? 'bg-indigo-500/10 text-indigo-500' : 'bg-gray-200 dark:bg-gray-800 text-gray-400 dark:text-gray-500'}">
                                ${device.type === 'light' 
                                    ? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>'
                                    : '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
                                }
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-gray-200">${device.name}</h4>
                                <p class="text-xs text-gray-500 uppercase">${device.type}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <button 
                                id="${switchId}"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 ${isChecked ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-700'}"
                                type="button"
                                role="switch"
                                aria-checked="${isChecked ? 'true' : 'false'}"
                            >
                                <span 
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${isChecked ? 'translate-x-5' : 'translate-x-0'}"
                                ></span>
                            </button>
                        </div>
                    `;

                    devicesContainer.appendChild(row);

                    // Add click event for toggle
                    const toggleBtn = document.getElementById(switchId);
                    toggleBtn.addEventListener('click', () => {
                        toggleDeviceState(device.id, device.name);
                    });
                });
            }

            // Action to Toggle device
            function toggleDeviceState(id, name) {
                logMessage(`Issuing command queue request for device: ${name}...`);
                axios.post(`/api/devices/${id}/toggle`)
                    .then(response => {
                        const cmd = response.data.data;
                        logMessage(`Command queued: Turn ${cmd.command.toUpperCase()} (${cmd.status}). Waiting for ESP32 sync.`, 'success');
                    })
                    .catch(err => {
                        console.error(err);
                        logMessage(`Failed to send toggle request to ${name}.`, 'error');
                    });
            }

            // Refresh button
            refreshBtn.addEventListener('click', () => {
                const roomId = roomSelect.value;
                if (roomId) {
                    fetchData(roomId);
                    logMessage('Telemetry data refreshed manually.', 'success');
                } else {
                    logMessage('Please select a classroom first.', 'warn');
                }
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH E:\MyCODE\RUPP Lesson and Assignment\Year2\Second Semester\Computer Architecture\IOT Project\files\smart-classroom-project\smart-classroom\laravel-app\resources\views/dashboard.blade.php ENDPATH**/ ?>