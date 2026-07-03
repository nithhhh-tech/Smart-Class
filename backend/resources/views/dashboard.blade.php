<!-- Modal Trigger Button (Put this wherever you want on your main page) -->
<button id="open-control-center-btn" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-sm transition duration-150">
    Open Control Center
</button>

<!-- Main Modal Wrapper (Hidden by default using 'hidden') -->
<div id="control-center-modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6 lg:p-8">
    
    <!-- Dark Backdrop Overlay -->
    <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Centered Form / Dashboard Container -->
    <div class="relative w-full max-w-5xl transform rounded-2xl bg-white dark:bg-gray-900 shadow-2xl transition-all border border-gray-200 dark:border-gray-800 flex flex-col max-h-[90vh]">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50 rounded-t-2xl">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                <span class="inline-block w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ __('Smart Classroom Control Center') }}
            </h2>
            
            <div class="flex items-center gap-4">
                <!-- Room Selector -->
                <div class="flex items-center gap-2">
                    <label for="room-select" class="text-xs font-medium text-gray-600 dark:text-gray-400">Room:</label>
                    <select id="room-select" class="text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 py-1 transition duration-150">
                        <option value="" disabled selected>Loading...</option>
                    </select>
                </div>

                <!-- Close Button -->
                <button id="close-control-center-btn" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body Content (Scrollable if content gets too tall) -->
        <div class="p-6 overflow-y-auto space-y-6 custom-scrollbar">
            
            <!-- Live Telemetry Panel -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Temperature Card -->
                <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-amber-500/10 to-orange-500/10 dark:from-amber-500/5 dark:to-orange-500/5 border border-amber-500/20 dark:border-amber-500/10 p-5 shadow-sm">
                    <div class="absolute top-0 right-0 p-3 opacity-10">
                        <svg class="w-16 h-16 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2a3 3 0 0 0-3 3v8.17a5 5 0 1 0 6 0V5a3 3 0 0 0-3-3m0 2a1 1 0 0 1 1 1v8h-2V5a1 1 0 0 1 1-1z"/>
                        </svg>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold tracking-wider text-amber-600 dark:text-amber-400 uppercase">Temperature</span>
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                        </span>
                    </div>
                    <div class="flex items-baseline">
                        <span id="temp-value" class="text-4xl font-black text-gray-800 dark:text-gray-100">--</span>
                        <span class="text-xl font-medium text-gray-500 ml-1">°C</span>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-2" id="temp-status">Retrieving data...</p>
                </div>

                <!-- Humidity Card -->
                <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 dark:from-blue-500/5 dark:to-indigo-500/5 border border-blue-500/20 dark:border-blue-500/10 p-5 shadow-sm">
                    <div class="absolute top-0 right-0 p-3 opacity-10">
                        <svg class="w-16 h-16 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                        </svg>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold tracking-wider text-blue-600 dark:text-blue-400 uppercase">Humidity</span>
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                        </span>
                    </div>
                    <div class="flex items-baseline">
                        <span id="humidity-value" class="text-4xl font-black text-gray-800 dark:text-gray-100">--</span>
                        <span class="text-xl font-medium text-gray-500 ml-1">%</span>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-2" id="humidity-status">Retrieving data...</p>
                </div>

                <!-- Motion Card -->
                <div id="motion-card" class="relative overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800/40 border border-gray-200 dark:border-gray-700/60 p-5 shadow-sm transition-all duration-500">
                    <div class="absolute top-0 right-0 p-3 opacity-10">
                        <svg class="w-16 h-16 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                        </svg>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold tracking-wider text-gray-600 dark:text-gray-400 uppercase">Motion Status</span>
                        <span class="flex h-2.5 w-2.5 relative">
                            <span id="motion-ping" class="absolute inline-flex h-full w-full rounded-full opacity-75 bg-slate-400"></span>
                            <span id="motion-dot" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="motion-value" class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">No Motion</span>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-3" id="motion-status">Room empty or inactive.</p>
                </div>
            </div>

            <!-- Controls Log Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Device Controls -->
                <div class="bg-gray-50 dark:bg-gray-800/30 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Device Quick Controls
                    </h3>
                    <div id="devices-container" class="space-y-3">
                        <div class="text-gray-500 dark:text-gray-400 text-xs py-4 text-center">Select a room to list devices.</div>
                    </div>
                </div>

                <!-- Console Logs -->
                <div class="bg-gray-50 dark:bg-gray-800/30 rounded-xl border border-gray-200 dark:border-gray-800 p-5 flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Classroom Console Log
                        </h3>
                        <div id="logs-container" class="bg-gray-950 rounded-lg p-3 font-mono text-[11px] text-emerald-400 border border-gray-800 h-36 overflow-y-auto space-y-1 scrollbar-thin scrollbar-thumb-gray-800">
                            <div>[SYSTEM] Ready. Select a room to start monitoring.</div>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center text-[11px] text-gray-500">
                        <span>Last Update: <span id="last-update-time">Never</span></span>
                        <button id="refresh-btn" class="text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Classroom Anomaly Alerts -->
            <div class="mt-6 bg-white dark:bg-gray-800/30 border border-gray-200 dark:border-gray-800 rounded-xl p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Classroom Anomaly Alerts
                </h3>
                <div id="alerts-container" class="space-y-2">
                    <div class="text-gray-500 dark:text-gray-400 text-xs py-4 text-center">Select a classroom to view active alerts.</div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts Configuration -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Modal UI Elements
        const modal = document.getElementById('control-center-modal');
        const openBtn = document.getElementById('open-control-center-btn');
        const closeBtn = document.getElementById('close-control-center-btn');
        const backdrop = document.getElementById('modal-backdrop');

        // Existing Dashboard Core Elements
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

        // Modal Functionality (Open/Close Toggle)
        function toggleModal(show) {
            if (show) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // Lock background page scroll
                
                // If a room is already chosen when opening, start tracking instantly
                if (roomSelect.value) {
                    fetchData(roomSelect.value);
                    pollingInterval = setInterval(() => fetchData(roomSelect.value), 5000);
                }
            } else {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden'); // Unlock page scroll
                
                // Kill polling when closed to optimize backend bandwidth
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }
        }

        openBtn.addEventListener('click', () => toggleModal(true));
        closeBtn.addEventListener('click', () => toggleModal(false));
        backdrop.addEventListener('click', () => toggleModal(false)); // Close on background click

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
                roomSelect.innerHTML = '<option value="" disabled selected>Select...</option>';
                rooms.forEach(room => {
                    const opt = document.createElement('option');
                    opt.value = room.id;
                    opt.textContent = `${room.name}`;
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
            
            if (pollingInterval) clearInterval(pollingInterval);

            fetchData(roomId);
            pollingInterval = setInterval(() => fetchData(roomId), 5000);
        });

        // Fetch data function
        function fetchData(roomId) {
            axios.get(`/api/dashboard/summary?room_id=${roomId}`)
                .then(response => {
                    updateTelemetry(response.data.data);
                })
                .catch(err => {
                    console.error(err);
                    logMessage(`Error fetching telemetry for room ${roomId}.`, 'error');
                });

            axios.get(`/api/devices?room_id=${roomId}`)
                .then(response => {
                    updateDevicesList(response.data.data);
                })
                .catch(err => {
                    console.error(err);
                    logMessage(`Error loading device controls.`, 'error');
                });

            // Fetch active alerts
            fetchAlerts(roomId);
        }

        // Fetch alerts list
        function fetchAlerts(roomId) {
            axios.get(`/api/alerts?room_id=${roomId}`)
                .then(response => {
                    updateAlertsList(response.data.data, roomId);
                })
                .catch(err => {
                    console.error(err);
                    logMessage(`Error loading active alerts.`, 'error');
                });
        }

        // Update UI alerts list
        function updateAlertsList(alerts, roomId) {
            if (alerts.length === 0) {
                alertsContainer.innerHTML = '<div class="text-gray-500 dark:text-gray-400 text-xs py-4 text-center">No active anomalies detected. All systems operating normally.</div>';
                return;
            }

            alertsContainer.innerHTML = '';
            alerts.forEach(alert => {
                const row = document.createElement('div');
                row.className = 'flex justify-between items-center p-3 bg-red-50/50 dark:bg-rose-950/10 border border-red-100 dark:border-rose-950/20 rounded-lg transition duration-150 text-xs';
                
                const triggerDate = new Date(alert.triggered_at).toLocaleTimeString();
                
                row.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-lg bg-rose-500/10 text-rose-500 dark:text-rose-400">
                            <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">${alert.message}</h4>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase">${alert.type} • Triggered at ${triggerDate}</p>
                        </div>
                    </div>
                    <div>
                        <button 
                            onclick="dismissAlert(${alert.id}, ${roomId})"
                            class="px-2.5 py-1 bg-red-100 dark:bg-rose-950/30 text-red-700 dark:text-rose-400 rounded-md hover:bg-red-200 dark:hover:bg-rose-950/50 text-[10px] font-semibold uppercase tracking-wider transition"
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



        // Action to Toggle device
        function toggleDeviceState(id, name) {
            logMessage(`Issuing toggle command for: ${name}...`);
            axios.post(`/api/devices/${id}/toggle`)
                .then(response => {
                    const cmd = response.data.data;
                    logMessage(`Command queued: Turn ${cmd.command.toUpperCase()} (${cmd.status}). Syncing via ESP32.`, 'success');
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