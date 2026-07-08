import React, { useState, useEffect } from 'react';
import { deviceService, roomService } from '../services/api';
import { Cpu, Lightbulb, Wind, Plus, X, Loader, Power, Trash2, FolderPlus, Edit2 } from 'lucide-react';

const Devices = () => {
  const [devices, setDevices] = useState([]);
  const [rooms, setRooms] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [roomsLoading, setRoomsLoading] = useState(false);

  // Form states
  const [editingDevice, setEditingDevice] = useState(null);
  const [selectedRoomId, setSelectedRoomId] = useState('');
  const [deviceName, setDeviceName] = useState('');
  const [deviceType, setDeviceType] = useState('light');
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');

  const fetchDevices = async () => {
    setLoading(true);
    try {
      const data = await deviceService.getDevices();
      setDevices(data);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDevices();
  }, []);

  const handleOpenForm = async () => {
    setShowForm(true);
    setEditingDevice(null);
    setDeviceName('');
    setDeviceType('light');
    setRoomsLoading(true);
    setError('');
    try {
      const data = await roomService.getRooms();
      setRooms(data);
      if (data.length > 0) {
        setSelectedRoomId(data[0].id);
      }
    } catch (err) {
      console.error(err);
      setError('Could not retrieve classrooms list.');
    } finally {
      setRoomsLoading(false);
    }
  };

  const handleEdit = async (device) => {
    setEditingDevice(device);
    setShowForm(true);
    setDeviceName(device.name);
    setDeviceType(device.type);
    setSelectedRoomId(device.room_id);
    setRoomsLoading(true);
    setError('');
    try {
      const data = await roomService.getRooms();
      setRooms(data);
    } catch (err) {
      console.error(err);
      setError('Could not retrieve classrooms list.');
    } finally {
      setRoomsLoading(false);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    if (!selectedRoomId || !deviceName.trim()) return;
    setSaving(true);

    try {
      if (editingDevice) {
        await deviceService.updateDevice(editingDevice.id, selectedRoomId, deviceName, deviceType);
        setEditingDevice(null);
      } else {
        await deviceService.addDevice(selectedRoomId, deviceName, deviceType);
      }
      setDeviceName('');
      setShowForm(false);
      fetchDevices();
    } catch (err) {
      console.error(err);
      setError(editingDevice ? 'Failed to update device.' : 'Failed to register device. Please try again.');
    } finally {
      setSaving(false);
    }
  };

  const handleToggle = async (id) => {
    try {
      const updated = await deviceService.toggleDevice(id);
      setDevices(prev => prev.map(d => d.id === id ? { ...d, status: updated.status } : d));
    } catch (err) {
      console.error(err);
      alert('Failed to send toggle switch command.');
    }
  };

  const handleDelete = async (id, name) => {
    if (window.confirm(`Are you sure you want to remove device "${name}" from directory database?`)) {
      try {
        await deviceService.deleteDevice(id);
        fetchDevices();
      } catch (err) {
        console.error(err);
        alert('Failed to remove device.');
      }
    }
  };

  return (
    <div className="space-y-6">
      {/* Top Header controls */}
      <div className="flex justify-between items-center border-b border-blue-950/40 pb-4">
        <div>
          <h1 className="text-2xl font-bold tracking-tight text-white font-display">Device Directory</h1>
          <p className="text-xs text-slate-400 font-mono">REGISTER AND CALIBRATE HARDWARE NODES</p>
        </div>

        <button
          onClick={() => {
            if (showForm) {
              setShowForm(false);
            } else {
              handleOpenForm();
            }
          }}
          className="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-display font-semibold text-xs transition-all duration-300 rounded-lg cursor-pointer shadow-md"
        >
          {showForm ? (
            <>
              <X className="w-4 h-4" />
              CANCEL
            </>
          ) : (
            <>
              <Plus className="w-4 h-4" />
              REGISTER DEVICE
            </>
          )}
        </button>
      </div>

      {/* Inline Form to Add/Edit Device */}
      {showForm && (
        <div className="max-w-lg bg-[#091124]/40 border border-blue-950/80 rounded-2xl p-6 backdrop-blur-md shadow-lg tick-corners">
          <h3 className="text-sm font-bold text-slate-200 mb-4 font-mono flex items-center gap-2 uppercase">
            <FolderPlus className="w-4 h-4 text-blue-400" />
            {editingDevice ? `Edit Device Node #${editingDevice.id}` : 'Register New Device Node'}
          </h3>

          {error && (
            <div className="mb-4 p-3 bg-rose-950/20 border border-rose-900/50 text-rose-400 text-xs rounded-lg">
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label htmlFor="device-room" className="block text-xs font-semibold uppercase tracking-wider text-blue-300/80 mb-2 font-mono">
                Select Classroom Space
              </label>
              {roomsLoading ? (
                <div className="flex items-center gap-2 py-2 text-xs text-slate-500 font-mono">
                  <Loader className="w-3.5 h-3.5 animate-spin" />
                  Loading classrooms...
                </div>
              ) : (
                <select
                  id="device-room"
                  required
                  value={selectedRoomId}
                  onChange={(e) => setSelectedRoomId(e.target.value)}
                  className="w-full px-4 py-2.5 bg-[#030712]/60 border border-blue-950 rounded-lg text-slate-100 focus:outline-none focus:border-cyan-500 transition duration-150 font-mono text-xs cursor-pointer"
                >
                  {rooms.length === 0 ? (
                    <option value="" disabled>No classrooms available</option>
                  ) : (
                    rooms.map(room => (
                      <option key={room.id} value={room.id} className="bg-[#091124]">
                        {room.name}
                      </option>
                    ))
                  )}
                </select>
              )}
            </div>

            <div>
              <label htmlFor="device-name" className="block text-xs font-semibold uppercase tracking-wider text-blue-300/80 mb-2 font-mono">
                Device Name *
              </label>
              <input
                id="device-name"
                type="text"
                required
                value={deviceName}
                onChange={(e) => setDeviceName(e.target.value)}
                placeholder="e.g. Ceiling Fan 2, Stage Floodlight"
                className="w-full px-4 py-2.5 bg-[#030712]/60 border border-blue-950 rounded-lg text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition duration-150 font-mono text-xs"
              />
            </div>

            <div>
              <label htmlFor="device-type" className="block text-xs font-semibold uppercase tracking-wider text-blue-300/80 mb-2 font-mono">
                Hardware Relay Type
              </label>
              <select
                id="device-type"
                required
                value={deviceType}
                onChange={(e) => setDeviceType(e.target.value)}
                className="w-full px-4 py-2.5 bg-[#030712]/60 border border-blue-950 rounded-lg text-slate-100 focus:outline-none focus:border-cyan-500 transition duration-150 font-mono text-xs cursor-pointer"
              >
                <option value="light" className="bg-[#091124]">Light (AC Relay Switch)</option>
                <option value="fan" className="bg-[#091124]">Fan (DC Inductive Load)</option>
              </select>
            </div>

            <div className="flex gap-2 justify-end pt-2">
              <button
                type="button"
                onClick={() => {
                  setShowForm(false);
                  setEditingDevice(null);
                  setDeviceName('');
                  setError('');
                }}
                className="px-4 py-2 border border-blue-950 text-slate-400 hover:text-slate-200 transition-colors font-mono text-xs cursor-pointer rounded-lg"
              >
                CANCEL
              </button>
              <button
                type="submit"
                disabled={saving || rooms.length === 0}
                className="flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-mono text-xs transition duration-150 cursor-pointer rounded-lg disabled:opacity-50"
              >
                {saving ? (
                  <>
                    <Loader className="w-3.5 h-3.5 animate-spin" />
                    {editingDevice ? 'SAVING...' : 'REGISTERING...'}
                  </>
                ) : (
                  editingDevice ? 'SAVE CHANGES' : 'REGISTER'
                )}
              </button>
            </div>
          </form>
        </div>
      )}

      {/* Grid of registered devices */}
      {loading ? (
        <div className="text-center py-20">
          <Loader className="w-10 h-10 animate-spin text-blue-500 mx-auto" />
          <p className="mt-3 text-xs text-slate-500 font-mono uppercase tracking-wider">Loading device index...</p>
        </div>
      ) : devices.length === 0 ? (
        <div className="border border-blue-950 bg-[#091124]/20 rounded-2xl p-12 text-center text-slate-500 font-mono text-xs max-w-xl mx-auto tick-corners">
          No hardware relays cataloged. Click "REGISTER DEVICE" to map an ESP32 output pin.
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {devices.map(device => {
            const isChecked = !!device.status;
            return (
              <div
                key={device.id}
                className="bg-[#091124]/40 border border-blue-950/80 rounded-2xl p-6 backdrop-blur-md shadow-md hover:shadow-lg hover:border-blue-900 transition-all duration-200 flex flex-col justify-between tick-corners"
              >
                <div>
                  <div className="flex justify-between items-start mb-4">
                    <div className="flex items-center gap-3">
                      <div className={`p-2.5 rounded-xl border ${
                        isChecked
                          ? 'bg-blue-500/10 text-blue-400 border-blue-500/20'
                          : 'bg-blue-950/20 text-slate-500 border-blue-950/50'
                      }`}>
                        {device.type === 'light' ? (
                          <Lightbulb className={`w-5 h-5 ${isChecked ? 'animate-pulse' : ''}`} />
                        ) : (
                          <Wind className={`w-5 h-5 ${isChecked ? 'animate-spin [animation-duration:3s]' : ''}`} />
                        )}
                      </div>
                      <div>
                        <h3 className="font-bold text-base text-slate-100 tracking-tight">{device.name}</h3>
                        <p className="text-[10px] text-slate-500 font-mono uppercase tracking-wider mt-0.5">{device.type}</p>
                      </div>
                    </div>

                    <button
                      onClick={() => handleToggle(device.id)}
                      className={`relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none ${
                        isChecked ? 'bg-blue-600' : 'bg-slate-800'
                      }`}
                    >
                      <span
                        className={`pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out ${
                          isChecked ? 'translate-x-5' : 'translate-x-0'
                        }`}
                      />
                    </button>
                  </div>

                  <div className="mt-4 pt-4 border-t border-blue-950/60 space-y-2 text-[11px] font-mono">
                    <div className="flex justify-between">
                      <span className="text-slate-500">CLASSROOM ID:</span>
                      <span className="text-slate-300 font-semibold">{device.room?.name || `ID: ${device.room_id}`}</span>
                    </div>
                    <div className="flex justify-between items-center">
                      <span className="text-slate-500">RELAY STATE:</span>
                      <span className={`font-semibold flex items-center gap-1.5 ${
                        isChecked ? 'text-emerald-400' : 'text-slate-400'
                      }`}>
                        <span className={`h-1.5 w-1.5 rounded-full ${isChecked ? 'bg-emerald-500 animate-pulse' : 'bg-slate-500'}`}></span>
                        {isChecked ? 'CONNECTED / ON' : 'IDLE / OFF'}
                      </span>
                    </div>
                  </div>
                </div>

                <div className="mt-6 pt-4 border-t border-blue-950/60 flex justify-between gap-2">
                  <button
                    onClick={() => handleEdit(device)}
                    className="flex items-center gap-1.5 px-3 py-1.5 border border-blue-900 text-blue-400 bg-blue-950/10 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 font-mono text-[10px] cursor-pointer rounded"
                  >
                    <Edit2 className="w-3.5 h-3.5" />
                    EDIT
                  </button>
                  <button
                    onClick={() => handleDelete(device.id, device.name)}
                    className="flex items-center gap-1.5 px-3 py-1.5 border border-rose-950 text-rose-400 bg-rose-950/10 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-300 font-mono text-[10px] cursor-pointer rounded"
                  >
                    <Trash2 className="w-3.5 h-3.5" />
                    REMOVE
                  </button>
                </div>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
};

export default Devices;
