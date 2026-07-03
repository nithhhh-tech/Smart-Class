<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Classroom Management') }}
            </h2>
            <button id="add-room-btn" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-md">
                + Add Classroom
            </button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Inline Form for Adding Room (Initially Hidden) -->
            <div id="room-form-container" class="hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 rounded-2xl p-6 shadow-sm max-w-lg transition-all duration-300">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Add New Classroom</h3>
                <form id="room-form" class="space-y-4">
                    <div>
                        <label for="room-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Room Name</label>
                        <input type="text" id="room-name" placeholder="e.g. Class A, Lab 1" required class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    </div>
                    <div>
                        <label for="room-location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                        <input type="text" id="room-location" placeholder="e.g. Block A, Floor 2" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button type="button" id="cancel-room-btn" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-xs font-semibold uppercase">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-semibold uppercase">Save Room</button>
                    </div>
                </form>
            </div>

            <!-- Rooms List Grid -->
            <div id="rooms-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Room cards will load here -->
            </div>
            
        </div>
    </div>

    <!-- Script Block -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addRoomBtn = document.getElementById('add-room-btn');
            const cancelRoomBtn = document.getElementById('cancel-room-btn');
            const roomFormContainer = document.getElementById('room-form-container');
            const roomForm = document.getElementById('room-form');
            const roomsGrid = document.getElementById('rooms-grid');
            const roomNameInput = document.getElementById('room-name');
            const roomLocationInput = document.getElementById('room-location');

            // Show form
            addRoomBtn.addEventListener('click', () => {
                roomFormContainer.classList.remove('hidden');
                roomNameInput.focus();
            });

            // Hide form
            cancelRoomBtn.addEventListener('click', () => {
                roomFormContainer.classList.add('hidden');
                roomForm.reset();
            });

            // Load rooms
            function loadRooms() {
                roomsGrid.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 py-12">
                        <span class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></span>
                    </div>
                `;

                axios.get('/api/rooms')
                    .then(response => {
                        const rooms = response.data.data;
                        if (rooms.length === 0) {
                            roomsGrid.innerHTML = `
                                <div class="col-span-full bg-white dark:bg-gray-800 border rounded-2xl p-12 text-center text-gray-500 dark:text-gray-400">
                                    No rooms registered. Click "+ Add Classroom" to get started.
                                </div>
                            `;
                            return;
                        }

                        roomsGrid.innerHTML = '';
                        rooms.forEach(room => {
                            const card = document.createElement('div');
                            card.className = 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/60 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden flex flex-col justify-between';
                            card.innerHTML = `
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="p-2 bg-indigo-500/10 text-indigo-600 rounded-xl">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-xl text-gray-800 dark:text-gray-100">${room.name}</h4>
                                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                ${room.location || 'No location set'}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400">
                                        Active Devices: <span class="font-bold text-gray-800 dark:text-gray-200">${room.devices_count || 0}</span>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end gap-2">
                                    <button class="delete-room-btn text-rose-500 hover:text-rose-600 text-xs font-bold uppercase" data-id="${room.id}">Delete</button>
                                </div>
                            `;

                            roomsGrid.appendChild(card);

                            // Bind delete event
                            card.querySelector('.delete-room-btn').addEventListener('click', (e) => {
                                const id = e.target.getAttribute('data-id');
                                if (confirm('Are you sure you want to delete this room? All devices and data logs will be removed.')) {
                                    axios.delete(`/api/rooms/${id}`)
                                        .then(() => {
                                            loadRooms();
                                        })
                                        .catch(err => console.error(err));
                                }
                            });
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        roomsGrid.innerHTML = `<div class="col-span-full text-center text-rose-500">Failed to load classrooms.</div>`;
                    });
            }

            // Save new room
            roomForm.addEventListener('submit', (e) => {
                e.preventDefault();

                axios.post('/api/rooms', {
                    name: roomNameInput.value,
                    location: roomLocationInput.value
                })
                .then(() => {
                    roomFormContainer.classList.add('hidden');
                    roomForm.reset();
                    loadRooms();
                })
                .catch(err => {
                    console.error(err);
                    alert('Error creating room.');
                });
            });

            // Initial load
            loadRooms();
        });
    </script>
</x-app-layout>
