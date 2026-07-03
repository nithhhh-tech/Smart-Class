<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Automation Schedules') }}
            </h2>
            <button id="add-schedule-btn" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-md">
                + Add Schedule Rule
            </button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Inline Form for Creating Schedule (Initially Hidden) -->
            <div id="schedule-form-container" class="hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 rounded-2xl p-6 shadow-sm max-w-xl transition-all duration-300">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Create Automation Rule</h3>
                <form id="schedule-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="schedule-room" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Classroom</label>
                            <select id="schedule-room" required class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                <option value="" disabled selected>Choose room...</option>
                            </select>
                        </div>
                        <div>
                            <label for="schedule-device" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device</label>
                            <select id="schedule-device" required disabled class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 disabled:opacity-50">
                                <option value="" disabled selected>Select room first...</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="schedule-action" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Action</label>
                            <select id="schedule-action" required class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                <option value="on">Turn ON</option>
                                <option value="off">Turn OFF</option>
                            </select>
                        </div>
                        <div>
                            <label for="schedule-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trigger Time (HH:MM)</label>
                            <input type="time" id="schedule-time" required class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        </div>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Days of Week</span>
                        <div class="flex flex-wrap gap-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="days" value="mon" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-1.5 text-sm text-gray-600 dark:text-gray-400">Mon</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="checkbox" name="days" value="tue" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-1.5 text-sm text-gray-600 dark:text-gray-400">Tue</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="checkbox" name="days" value="wed" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-1.5 text-sm text-gray-600 dark:text-gray-400">Wed</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="checkbox" name="days" value="thu" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-1.5 text-sm text-gray-600 dark:text-gray-400">Thu</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="checkbox" name="days" value="fri" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-1.5 text-sm text-gray-600 dark:text-gray-400">Fri</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="checkbox" name="days" value="sat" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-1.5 text-sm text-gray-600 dark:text-gray-400">Sat</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="checkbox" name="days" value="sun" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-1.5 text-sm text-gray-600 dark:text-gray-400">Sun</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-2 justify-end pt-2">
                        <button type="button" id="cancel-schedule-btn" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-xs font-semibold uppercase">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-semibold uppercase">Save Rule</button>
                    </div>
                </form>
            </div>

            <!-- Schedules Grid/Layout -->
            <div class="bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/60 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Active Schedules
                    </h3>
                    <div class="flex items-center gap-2">
                        <label for="filter-room" class="text-sm font-medium text-gray-600 dark:text-gray-400">Classroom Filter:</label>
                        <select id="filter-room" class="rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 text-sm">
                            <option value="">All Classrooms</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/40">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Device</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Trigger Time</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Days</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="schedules-list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Javascript values load here -->
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Script Block -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addScheduleBtn = document.getElementById('add-schedule-btn');
            const cancelScheduleBtn = document.getElementById('cancel-schedule-btn');
            const scheduleFormContainer = document.getElementById('schedule-form-container');
            const scheduleForm = document.getElementById('schedule-form');
            const schedulesList = document.getElementById('schedules-list');
            
            const scheduleRoomSelect = document.getElementById('schedule-room');
            const scheduleDeviceSelect = document.getElementById('schedule-device');
            const scheduleActionSelect = document.getElementById('schedule-action');
            const scheduleTimeInput = document.getElementById('schedule-time');
            const filterRoomSelect = document.getElementById('filter-room');

            // Open Form
            addScheduleBtn.addEventListener('click', () => {
                scheduleFormContainer.classList.remove('hidden');
                loadRoomsList();
            });

            // Close Form
            cancelScheduleBtn.addEventListener('click', () => {
                scheduleFormContainer.classList.add('hidden');
                scheduleForm.reset();
                scheduleDeviceSelect.innerHTML = '<option value="" disabled selected>Select room first...</option>';
                scheduleDeviceSelect.disabled = true;
            });

            // Populate rooms dropdowns
            function loadRoomsList() {
                axios.get('/api/rooms')
                    .then(response => {
                        const rooms = response.data.data;
                        
                        // Populate Form Room Select
                        scheduleRoomSelect.innerHTML = '<option value="" disabled selected>Choose room...</option>';
                        rooms.forEach(room => {
                            const opt = document.createElement('option');
                            opt.value = room.id;
                            opt.textContent = room.name;
                            scheduleRoomSelect.appendChild(opt);
                        });
                    })
                    .catch(err => console.error(err));
            }

            // Populate filter room dropdown on load
            axios.get('/api/rooms')
                .then(response => {
                    const rooms = response.data.data;
                    filterRoomSelect.innerHTML = '<option value="">All Classrooms</option>';
                    rooms.forEach(room => {
                        const opt = document.createElement('option');
                        opt.value = room.id;
                        opt.textContent = room.name;
                        filterRoomSelect.appendChild(opt);
                    });
                });

            // Handle Room selection change in form to load corresponding devices
            scheduleRoomSelect.addEventListener('change', () => {
                const roomId = scheduleRoomSelect.value;
                if (!roomId) return;

                scheduleDeviceSelect.innerHTML = '<option value="" disabled selected>Loading devices...</option>';
                scheduleDeviceSelect.disabled = true;

                axios.get(`/api/devices?room_id=${roomId}`)
                    .then(response => {
                        const devices = response.data.data;
                        scheduleDeviceSelect.innerHTML = '<option value="" disabled selected>Select device...</option>';
                        devices.forEach(device => {
                            const opt = document.createElement('option');
                            opt.value = device.id;
                            opt.textContent = `${device.name} (${device.type.toUpperCase()})`;
                            scheduleDeviceSelect.appendChild(opt);
                        });
                        scheduleDeviceSelect.disabled = false;
                    })
                    .catch(err => {
                        console.error(err);
                        scheduleDeviceSelect.innerHTML = '<option value="" disabled>Error loading devices</option>';
                    });
            });

            // Handle filter change
            filterRoomSelect.addEventListener('change', () => {
                loadSchedules(filterRoomSelect.value);
            });

            // Load schedules table
            function loadSchedules(roomId = '') {
                schedulesList.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <span class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-500"></span>
                        </td>
                    </tr>
                `;

                const url = roomId ? `/api/schedules?room_id=${roomId}` : '/api/schedules';

                axios.get(url)
                    .then(response => {
                        const schedules = response.data.data;
                        if (schedules.length === 0) {
                            schedulesList.innerHTML = `
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No schedules configured.
                                    </td>
                                </tr>
                            `;
                            return;
                        }

                        schedulesList.innerHTML = '';
                        schedules.forEach(schedule => {
                            const tr = document.createElement('tr');
                            tr.className = 'hover:bg-gray-50/50 dark:hover:bg-gray-900/20';

                            // Format run_at to HH:MM
                            const timeParts = schedule.run_at.split(':');
                            const formattedTime = `${timeParts[0]}:${timeParts[1]}`;

                            // Days visual list
                            const allDays = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
                            const activeDays = schedule.days.split(',').map(d => d.trim().toLowerCase());
                            
                            let daysHtml = '<div class="flex gap-1">';
                            allDays.forEach(day => {
                                const isActive = activeDays.includes(day);
                                daysHtml += `<span class="px-2 py-0.5 text-xs font-bold rounded-md ${
                                    isActive 
                                        ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400' 
                                        : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600'
                                }">${day.toUpperCase()}</span>`;
                            });
                            daysHtml += '</div>';

                            // Action badge
                            const actionBadge = schedule.action === 'on'
                                ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-400">ON</span>'
                                : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 dark:bg-rose-950/40 text-rose-800 dark:text-rose-400">OFF</span>';

                            // Status toggle switch
                            const switchId = `status-switch-${schedule.id}`;
                            const isChecked = schedule.is_active;

                            tr.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700 dark:text-gray-300">${schedule.room?.name || 'Deleted Room'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    <div class="font-semibold">${schedule.device?.name || 'Deleted Device'}</div>
                                    <div class="text-xs text-gray-500 uppercase">${schedule.device?.type || ''}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${actionBadge}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5 mt-2.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    ${formattedTime}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${daysHtml}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button 
                                        id="${switchId}"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 ${isChecked ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-700'}"
                                        type="button"
                                        role="switch"
                                        aria-checked="${isChecked ? 'true' : 'false'}"
                                    >
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${isChecked ? 'translate-x-5' : 'translate-x-0'}"></span>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="deleteSchedule(${schedule.id})" class="text-rose-500 hover:text-rose-700 font-semibold text-xs uppercase tracking-wider">Delete</button>
                                </td>
                            `;

                            schedulesList.appendChild(tr);

                            // Handle active toggle click
                            const toggleBtn = document.getElementById(switchId);
                            toggleBtn.addEventListener('click', () => {
                                toggleScheduleActive(schedule.id, !isChecked);
                            });
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        schedulesList.innerHTML = `
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-red-500">
                                    Failed to load schedules list.
                                </td>
                            </tr>
                        `;
                    });
            }

            // Toggle active status
            function toggleScheduleActive(id, newStatus) {
                axios.put(`/api/schedules/${id}`, { is_active: newStatus })
                    .then(() => {
                        loadSchedules(filterRoomSelect.value);
                    })
                    .catch(err => console.error(err));
            }

            // Submit new schedule form
            scheduleForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const roomId = scheduleRoomSelect.value;
                const deviceId = scheduleDeviceSelect.value;
                const action = scheduleActionSelect.value;
                const runAt = scheduleTimeInput.value; // format HH:MM
                
                // Get checked days
                const checkedBoxes = document.querySelectorAll('input[name="days"]:checked');
                const days = Array.from(checkedBoxes).map(cb => cb.value).join(',');

                if (!days) {
                    alert('Please select at least one day.');
                    return;
                }

                axios.post('/api/schedules', {
                    room_id: roomId,
                    device_id: deviceId,
                    action: action,
                    run_at: runAt,
                    days: days,
                    is_active: true
                })
                .then(() => {
                    scheduleFormContainer.classList.add('hidden');
                    scheduleForm.reset();
                    scheduleDeviceSelect.innerHTML = '<option value="" disabled selected>Select room first...</option>';
                    scheduleDeviceSelect.disabled = true;
                    loadSchedules(filterRoomSelect.value);
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to save schedule. Check all inputs.');
                });
            });

            // Global delete function hook
            window.deleteSchedule = function(id) {
                if (confirm('Are you sure you want to delete this schedule rule?')) {
                    axios.delete(`/api/schedules/${id}`)
                        .then(() => {
                            loadSchedules(filterRoomSelect.value);
                        })
                        .catch(err => console.error(err));
                }
            };

            // Initial load
            loadSchedules();
        });
    </script>
</x-app-layout>
