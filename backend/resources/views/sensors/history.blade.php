<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Telemetry History') }}
            </h2>
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label for="room-select" class="text-sm font-medium text-gray-600 dark:text-gray-400">Room:</label>
                    <select id="room-select" class="rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="" disabled selected>Loading classrooms...</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label for="range-select" class="text-sm font-medium text-gray-600 dark:text-gray-400">Range:</label>
                    <select id="range-select" class="rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="1">Last Hour</option>
                        <option value="6">Last 6 Hours</option>
                        <option value="24" selected>Last 24 Hours</option>
                        <option value="168">Last 7 Days</option>
                    </select>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Temperature History Chart -->
                <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        Temperature Trend (°C)
                    </h3>
                    <div class="relative h-80">
                        <canvas id="tempChart"></canvas>
                    </div>
                </div>

                <!-- Humidity History Chart -->
                <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        Humidity Trend (%)
                    </h3>
                    <div class="relative h-80">
                        <canvas id="humidityChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- Telemetry Logs Table -->
            <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6">Historical Log Records</h3>
                <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/60 text-gray-500 dark:text-gray-400 font-semibold text-left">
                            <tr>
                                <th class="px-6 py-4">Timestamp</th>
                                <th class="px-6 py-4">Temperature (°C)</th>
                                <th class="px-6 py-4">Humidity (%)</th>
                                <th class="px-6 py-4">Motion Status</th>
                            </tr>
                        </thead>
                        <tbody id="logs-table-body" class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">Select a classroom to display log history.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Block -->
    <!-- Include Chart.js via CDN or script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roomSelect = document.getElementById('room-select');
            const rangeSelect = document.getElementById('range-select');
            const logsTableBody = document.getElementById('logs-table-body');

            let tempChart = null;
            let humidityChart = null;

            // Load rooms
            axios.get('/api/rooms')
                .then(response => {
                    const rooms = response.data.data;
                    roomSelect.innerHTML = '<option value="" disabled selected>Choose a room...</option>';
                    rooms.forEach(room => {
                        const opt = document.createElement('option');
                        opt.value = room.id;
                        opt.textContent = room.name;
                        roomSelect.appendChild(opt);
                    });
                })
                .catch(err => console.error(err));

            // Room / Range change handler
            const handleFilterChange = () => {
                const roomId = roomSelect.value;
                const hours = rangeSelect.value;
                if (roomId) {
                    loadHistory(roomId, hours);
                }
            };

            roomSelect.addEventListener('change', handleFilterChange);
            rangeSelect.addEventListener('change', handleFilterChange);

            // Load History telemetry
            function loadHistory(roomId, hours) {
                axios.get(`/api/sensor-logs/history?room_id=${roomId}&hours=${hours}`)
                    .then(response => {
                        const logs = response.data.data;
                        updateCharts(logs);
                        updateTable(logs);
                    })
                    .catch(err => console.error(err));
            }

            // Render/Update trend lines
            function updateCharts(logs) {
                const labels = logs.map(l => {
                    const d = new Date(l.recorded_at);
                    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                });
                const temps = logs.map(l => parseFloat(l.temperature));
                const humidities = logs.map(l => parseFloat(l.humidity));

                // Temperature Chart
                if (tempChart) tempChart.destroy();
                const tempCtx = document.getElementById('tempChart').getContext('2d');
                
                // Color Gradient
                const tempGradient = tempCtx.createLinearGradient(0, 0, 0, 300);
                tempGradient.addColorStop(0, 'rgba(245, 158, 11, 0.4)');
                tempGradient.addColorStop(1, 'rgba(245, 158, 11, 0)');

                tempChart = new Chart(tempCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Temp (°C)',
                            data: temps,
                            borderColor: '#f59e0b',
                            borderWidth: 3,
                            backgroundColor: tempGradient,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 2,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { grid: { color: 'rgba(156, 163, 175, 0.1)' } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // Humidity Chart
                if (humidityChart) humidityChart.destroy();
                const humCtx = document.getElementById('humidityChart').getContext('2d');
                
                const humGradient = humCtx.createLinearGradient(0, 0, 0, 300);
                humGradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
                humGradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

                humidityChart = new Chart(humCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Humidity (%)',
                            data: humidities,
                            borderColor: '#3b82f6',
                            borderWidth: 3,
                            backgroundColor: humGradient,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 2,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { grid: { color: 'rgba(156, 163, 175, 0.1)' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // Update Logs Table
            function updateTable(logs) {
                if (logs.length === 0) {
                    logsTableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No telemetry logged in this room for the selected range.</td>
                        </tr>
                    `;
                    return;
                }

                logsTableBody.innerHTML = '';
                // Display latest records first
                const reversedLogs = [...logs].reverse();
                reversedLogs.forEach(log => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition duration-100';
                    row.innerHTML = `
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">${new Date(log.recorded_at).toLocaleString()}</td>
                        <td class="px-6 py-4">${parseFloat(log.temperature).toFixed(1)} °C</td>
                        <td class="px-6 py-4">${parseFloat(log.humidity).toFixed(1)} %</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold ${log.motion ? 'bg-rose-100 dark:bg-rose-900/20 text-rose-800 dark:text-rose-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-400'}">
                                <span class="h-1.5 w-1.5 rounded-full ${log.motion ? 'bg-rose-600' : 'bg-gray-500'}"></span>
                                ${log.motion ? 'Active' : 'No Motion'}
                            </span>
                        </td>
                    `;
                    logsTableBody.appendChild(row);
                });
            }
        });
    </script>
</x-app-layout>
