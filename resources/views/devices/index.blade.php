<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Device Directory') }}
            </h2>
            <button id="add-device-btn" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-md">
                + Register Device
            </button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Inline Form for Registering Device (Initially Hidden) -->
            <div id="device-form-container" class="hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 rounded-2xl p-6 shadow-sm max-w-lg transition-all duration-300">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Register New Device</h3>
                <form id="device-form" class="space-y-4">
                    <div>
                        <label for="device-room" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Room</label>
                        <select id="device-room" required class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="" disabled selected>Loading classrooms...</option>
                        </select>
                    </div>
                    <div>
                        <label for="device-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device Name</label>
                        <input type="text" id="device-name" placeholder="e.g. Main Light, Window Fan" required class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    </div>
                    <div>
                        <label for="device-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device Type</label>
                        <select id="device-type" required class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="light">Light</option>
                            <option value="fan">Fan</option>
                        </select>
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button type="button" id="cancel-device-btn" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-xs font-semibold uppercase">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-semibold uppercase">Register</button>
                    </div>
                </form>
            </div>

            <!-- Devices List Grid -->
            <div id="devices-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Device cards will load here -->
            </div>
            
        </div>
    </div>

    <!-- Script Block -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addDeviceBtn = document.getElementById('add-device-btn');
            const cancelDeviceBtn = document.getElementById('cancel-device-btn');
            const deviceFormContainer = document.getElementById('device-form-container');
            const deviceForm = document.getElementById('device-form');
            const devicesGrid = document.getElementById('devices-grid');
            const deviceRoomSelect = document.getElementById('device-room');
            const deviceNameInput = document.getElementById('device-name');
            const deviceTypeSelect = document.getElementById('device-type');

            // Show form & fetch rooms for select list
            addDeviceBtn.addEventListener('click', () => {
                deviceFormContainer.classList.remove('hidden');
                
                axios.get('/api/rooms')
                    .then(response => {
                        const rooms = response.data.data;
                        deviceRoomSelect.innerHTML = '<option value="" disabled selected>Choose a room...</option>';
                        rooms.forEach(room => {
                            const opt = document.createElement('option');
                            opt.value = room.id;
                            opt.textContent = room.name;
                            deviceRoomSelect.appendChild(opt);
                        });
                    })
                    .catch(err => console.error(err));
            });

            // Hide form
            cancelDeviceBtn.addEventListener('click', () => {
                deviceFormContainer.classList.add('hidden');
                deviceForm.reset();
            });

            // Load devices
            function loadDevices() {
                devicesGrid.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 py-12">
                        <span class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></span>
                    </div>
                `;

                axios.get('/api/devices')
                    .then(response => {
                        const devices = response.data.data;
                        if (devices.length === 0) {
                            devicesGrid.innerHTML = `
                                <div class="col-span-full bg-white dark:bg-gray-800 border rounded-2xl p-12 text-center text-gray-500 dark:text-gray-400">
                                    No devices registered yet. Click "Register Device" to get started.
                                </div>
                            `;
                            return;
                        }

                        devicesGrid.innerHTML = '';
                        devices.forEach(device => {
                            const card = document.createElement('div');
                            const isChecked = device.status;
                            const switchId = `device-switch-${device.id}`;
                            
                            card.className = 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/60 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between';
                            card.innerHTML = `
                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 rounded-xl ${isChecked ? 'bg-indigo-500/10 text-indigo-500' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500'}">
                                                ${device.type === 'light' 
                                                    ? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>'
                                                    : '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
                                                }
                                            </div>
                                            <div>
                                                <h4 class="font-extrabold text-lg text-gray-800 dark:text-gray-100">${device.name}</h4>
                                                <span class="text-xs text-gray-500 uppercase font-semibold">${device.type}</span>
                                            </div>
                                        </div>
                                        
                                        <button 
                                            id="${switchId}"
                                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 ${isChecked ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-700'}"
                                            type="button"
                                            role="switch"
                                        >
                                            <span 
                                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${isChecked ? 'translate-x-5' : 'translate-x-0'}"
                                            ></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Status: <span class="font-bold ${isChecked ? 'text-emerald-500 animate-pulse' : 'text-gray-400'}">${isChecked ? 'ON' : 'OFF'}</span></span>
                                    <button class="delete-device-btn text-rose-500 hover:text-rose-600 text-xs font-bold uppercase" data-id="${device.id}">Delete</button>
                                </div>
                            `;

                            devicesGrid.appendChild(card);

                            // Bind toggle event
                            const toggleBtn = document.getElementById(switchId);
                            toggleBtn.addEventListener('click', () => {
                                axios.post(`/api/devices/${device.id}/toggle`)
                                    .then(() => {
                                        loadDevices();
                                    })
                                    .catch(err => console.error(err));
                            });

                            // Bind delete event
                            card.querySelector('.delete-device-btn').addEventListener('click', (e) => {
                                const id = e.target.getAttribute('data-id');
                                if (confirm('Are you sure you want to remove this device?')) {
                                    axios.delete(`/api/devices/${id}`)
                                        .then(() => {
                                            loadDevices();
                                        })
                                        .catch(err => console.error(err));
                                }
                            });
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        devicesGrid.innerHTML = `<div class="col-span-full text-center text-rose-500">Failed to load devices.</div>`;
                    });
            }

            // Save new device
            deviceForm.addEventListener('submit', (e) => {
                e.preventDefault();

                axios.post('/api/devices', {
                    room_id: deviceRoomSelect.value,
                    name: deviceNameInput.value,
                    type: deviceTypeSelect.value,
                    status: false
                })
                .then(() => {
                    deviceFormContainer.classList.add('hidden');
                    deviceForm.reset();
                    loadDevices();
                })
                .catch(err => {
                    console.error(err);
                    alert('Error registering device.');
                });
            });

            // Initial load
            loadDevices();
        });
    </script>
</x-app-layout>
