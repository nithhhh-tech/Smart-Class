import React, { useEffect, useState } from 'react';
import { roomService, deviceService, scheduleService, holidayService } from '../services/api';
import { CalendarRange, Plus, Trash2, Clock, CheckSquare, Square, RefreshCw, CalendarDays } from 'lucide-react';

const DAYS_OF_WEEK = [
  'Monday',
  'Tuesday',
  'Wednesday',
  'Thursday',
  'Friday',
  'Saturday',
  'Sunday',
];

const Schedules = () => {
  const [activeTab, setActiveTab] = useState('rules'); // 'rules' or 'holidays'
  const [schedules, setSchedules] = useState([]);
  const [rooms, setRooms] = useState([]);
  const [devices, setDevices] = useState([]);
  const [holidays, setHolidays] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  // Form State - Schedules
  const [selectedRoom, setSelectedRoom] = useState('');
  const [selectedDevice, setSelectedDevice] = useState('');
  const [command, setCommand] = useState('on');
  const [time, setTime] = useState('');
  const [selectedDays, setSelectedDays] = useState([]);

  // Form State - Holidays
  const [holidayName, setHolidayName] = useState('');
  const [holidayDate, setHolidayDate] = useState('');

  useEffect(() => {
    fetchInitialData();
  }, []);

  // Fetch devices when room selection changes
  useEffect(() => {
    if (selectedRoom) {
      deviceService.getDevices(selectedRoom)
        .then(data => setDevices(data))
        .catch(err => console.error('Error fetching devices', err));
    } else {
      setDevices([]);
    }
    setSelectedDevice('');
  }, [selectedRoom]);

  const fetchInitialData = async () => {
    setLoading(true);
    setError('');
    try {
      const [roomsData, schedulesData, holidaysData] = await Promise.all([
        roomService.getRooms(),
        scheduleService.getSchedules(),
        holidayService.getHolidays(),
      ]);
      setRooms(roomsData);
      setSchedules(schedulesData);
      setHolidays(holidaysData);
    } catch (err) {
      console.error(err);
      setError('Failed to load schedules, holidays, or classrooms.');
    } finally {
      setLoading(false);
    }
  };

  const handleDayToggle = (day) => {
    if (selectedDays.includes(day)) {
      setSelectedDays(selectedDays.filter(d => d !== day));
    } else {
      setSelectedDays([...selectedDays, day]);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    if (!selectedRoom || !selectedDevice || !time || selectedDays.length === 0) {
      setError('Please fill in all fields and select at least one day.');
      return;
    }

    try {
      await scheduleService.addSchedule(
        selectedRoom,
        selectedDevice,
        command,
        time,
        selectedDays
      );
      setSuccess('Schedule created successfully!');
      
      // Reset Form
      setSelectedRoom('');
      setSelectedDevice('');
      setCommand('on');
      setTime('');
      setSelectedDays([]);

      // Reload list
      const updatedSchedules = await scheduleService.getSchedules();
      setSchedules(updatedSchedules);
    } catch (err) {
      console.error(err);
      setError('Failed to create schedule.');
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to delete this schedule?')) return;
    setError('');
    setSuccess('');
    try {
      await scheduleService.deleteSchedule(id);
      setSuccess('Schedule deleted successfully.');
      setSchedules(schedules.filter(s => s.id !== id));
    } catch (err) {
      console.error(err);
      setError('Failed to delete schedule.');
    }
  };

  const handleHolidaySubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    if (!holidayName || !holidayDate) {
      setError('Please fill in both the holiday name and date.');
      return;
    }

    try {
      await holidayService.addHoliday(holidayName, holidayDate);
      setSuccess('Holiday exception registered successfully!');
      setHolidayName('');
      setHolidayDate('');

      // Reload holidays
      const updatedHolidays = await holidayService.getHolidays();
      setHolidays(updatedHolidays);
    } catch (err) {
      console.error(err);
      setError(err.response?.data?.message || 'Failed to create holiday exception.');
    }
  };

  const handleHolidayDelete = async (id) => {
    if (!window.confirm('Are you sure you want to remove this holiday exception?')) return;
    setError('');
    setSuccess('');
    try {
      await holidayService.deleteHoliday(id);
      setSuccess('Holiday exception removed.');
      setHolidays(holidays.filter(h => h.id !== id));
    } catch (err) {
      console.error(err);
      setError('Failed to delete holiday.');
    }
  };

  return (
    <div className="space-y-6">
      {/* Page Title */}
      <div className="flex items-center justify-between border-b border-blue-950/40 pb-4">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 flex items-center justify-center border border-blue-500/30 rounded bg-blue-950/40 shadow-[0_0_15px_rgba(29,78,216,0.1)]">
            <CalendarRange className="w-5 h-5 text-blue-400" />
          </div>
          <div>
            <h1 className="text-xl font-bold tracking-tight text-white font-display">Automation Schedules</h1>
            <p className="text-xs text-blue-400/70 font-mono">Define automated rules for devices</p>
          </div>
        </div>
        <button 
          onClick={fetchInitialData}
          className="p-2 border border-blue-950 text-blue-400 hover:text-cyan-400 hover:border-cyan-500/40 bg-blue-950/10 transition-all font-mono text-xs flex items-center gap-1 cursor-pointer"
        >
          <RefreshCw className="w-3.5 h-3.5" />
          REFRESH
        </button>
      </div>

      {/* Messages */}
      {error && (
        <div className="p-3 border border-rose-900 bg-rose-950/20 text-rose-400 font-mono text-xs rounded">
          [ERROR] {error}
        </div>
      )}
      {success && (
        <div className="p-3 border border-emerald-900 bg-emerald-950/20 text-emerald-400 font-mono text-xs rounded">
          [SUCCESS] {success}
        </div>
      )}

      {/* Tabs */}
      <div className="flex border-b border-blue-950/60 pb-px font-mono text-xs gap-4 mb-4">
        <button
          onClick={() => setActiveTab('rules')}
          className={`flex items-center gap-2 px-6 py-2.5 border-b-2 font-bold cursor-pointer transition ${
            activeTab === 'rules'
              ? 'border-blue-500 text-blue-400 font-semibold'
              : 'border-transparent text-slate-400 hover:text-slate-200'
          }`}
        >
          <Clock className="w-4 h-4" />
          AUTOMATION RULES
        </button>
        <button
          onClick={() => setActiveTab('holidays')}
          className={`flex items-center gap-2 px-6 py-2.5 border-b-2 font-bold cursor-pointer transition ${
            activeTab === 'holidays'
              ? 'border-blue-500 text-blue-400 font-semibold'
              : 'border-transparent text-slate-400 hover:text-slate-200'
          }`}
        >
          <CalendarDays className="w-4 h-4" />
          HOLIDAY EXCEPTIONS
        </button>
      </div>

      {activeTab === 'rules' ? (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Create Schedule Form */}
          <div className="bg-[#091124]/40 border border-blue-950/60 rounded-xl p-5 backdrop-blur-md shadow-sm h-fit">
            <h3 className="text-sm font-bold text-white font-mono mb-4 flex items-center gap-2 border-b border-blue-950/40 pb-2">
              <Plus className="w-4 h-4 text-blue-400" />
              CREATE NEW RULE
            </h3>
            <form onSubmit={handleSubmit} className="space-y-4 font-mono text-xs">
              {/* Room Select */}
              <div className="space-y-1.5">
                <label className="text-blue-400/80">CLASSROOM</label>
                <select
                  value={selectedRoom}
                  onChange={(e) => setSelectedRoom(e.target.value)}
                  className="w-full bg-[#030712] border border-blue-950/80 rounded px-3 py-2 text-slate-100 focus:border-blue-500 focus:outline-none"
                >
                  <option value="">Select a classroom...</option>
                  {rooms.map(room => (
                    <option key={room.id} value={room.id}>{room.name}</option>
                  ))}
                </select>
              </div>

              {/* Device Select */}
              <div className="space-y-1.5">
                <label className="text-blue-400/80">DEVICE</label>
                <select
                  value={selectedDevice}
                  onChange={(e) => setSelectedDevice(e.target.value)}
                  disabled={!selectedRoom}
                  className="w-full bg-[#030712] border border-blue-950/80 rounded px-3 py-2 text-slate-100 focus:border-blue-500 focus:outline-none disabled:opacity-50"
                >
                  <option value="">Select device...</option>
                  {devices.map(device => (
                    <option key={device.id} value={device.id}>
                      {device.name} ({device.type})
                    </option>
                  ))}
                </select>
              </div>

              {/* Command & Time Grid */}
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-1.5">
                  <label className="text-blue-400/80">ACTION</label>
                  <select
                    value={command}
                    onChange={(e) => setCommand(e.target.value)}
                    className="w-full bg-[#030712] border border-blue-950/80 rounded px-3 py-2 text-slate-100 focus:border-blue-500 focus:outline-none"
                  >
                    <option value="on">TURN ON</option>
                    <option value="off">TURN OFF</option>
                  </select>
                </div>

                <div className="space-y-1.5">
                  <label className="text-blue-400/80">TIME</label>
                  <input
                    type="time"
                    value={time}
                    onChange={(e) => setTime(e.target.value)}
                    className="w-full bg-[#030712] border border-blue-950/80 rounded px-3 py-2 text-slate-100 focus:border-blue-500 focus:outline-none"
                  />
                </div>
              </div>

              {/* Days Selection */}
              <div className="space-y-2">
                <label className="text-blue-400/80 block">ACTIVE DAYS</label>
                <div className="grid grid-cols-2 gap-2">
                  {DAYS_OF_WEEK.map(day => {
                    const isChecked = selectedDays.includes(day);
                    return (
                      <button
                        type="button"
                        key={day}
                        onClick={() => handleDayToggle(day)}
                        className={`flex items-center gap-2 px-2.5 py-1.5 border rounded text-left transition ${
                          isChecked
                            ? 'border-blue-500 text-blue-400 bg-blue-950/20'
                            : 'border-blue-950/50 text-slate-500 hover:text-slate-300'
                        }`}
                      >
                        {isChecked ? (
                          <CheckSquare className="w-3.5 h-3.5 text-blue-400" />
                        ) : (
                          <Square className="w-3.5 h-3.5" />
                        )}
                        <span>{day}</span>
                      </button>
                    );
                  })}
                </div>
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                className="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded tracking-wide transition active:scale-[0.99] cursor-pointer text-center"
              >
                SAVE SCHEDULE
              </button>
            </form>
          </div>

          {/* Schedules List */}
          <div className="lg:col-span-2 bg-[#091124]/40 border border-blue-950/60 rounded-xl p-5 backdrop-blur-md shadow-sm">
            <h3 className="text-sm font-bold text-white font-mono mb-4 flex items-center gap-2 border-b border-blue-950/40 pb-2">
              <Clock className="w-4 h-4 text-blue-400" />
              ACTIVE AUTOMATION RULES
            </h3>

            {loading ? (
              <div className="text-center py-8 font-mono text-xs text-blue-400 animate-pulse">
                SYNCING WITH BACKEND DATABASE...
              </div>
            ) : schedules.length === 0 ? (
              <div className="text-center py-8 font-mono text-xs text-slate-500">
                No automation rules configured. Use the form on the left to add one.
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left font-mono text-xs border-collapse">
                  <thead>
                    <tr className="border-b border-blue-950/80 text-blue-400/80">
                      <th className="py-2.5 px-3">CLASSROOM</th>
                      <th className="py-2.5 px-3">DEVICE</th>
                      <th className="py-2.5 px-3">ACTION</th>
                      <th className="py-2.5 px-3">TIME</th>
                      <th className="py-2.5 px-3">DAYS</th>
                      <th className="py-2.5 px-3 text-right">ACTION</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-blue-950/30">
                    {schedules.map(schedule => (
                      <tr key={schedule.id} className="hover:bg-blue-950/10 text-slate-300">
                        <td className="py-3 px-3 font-semibold text-white">
                          {schedule.room?.name || `Room #${schedule.room_id}`}
                        </td>
                        <td className="py-3 px-3">
                          {schedule.device?.name || `Device #${schedule.device_id}`}
                        </td>
                        <td className="py-3 px-3">
                          <span className={`px-2 py-0.5 rounded text-[10px] font-bold ${
                            schedule.action === 'on' 
                              ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' 
                              : 'bg-rose-500/10 text-rose-400 border border-rose-500/20'
                          }`}>
                            {schedule.action?.toUpperCase()}
                          </span>
                        </td>
                        <td className="py-3 px-3 text-white flex items-center gap-1.5 font-sans">
                          <Clock className="w-3.5 h-3.5 text-blue-400" />
                          {(schedule.run_at || '').substring(0, 5)}
                        </td>
                        <td className="py-3 px-3 text-slate-400 max-w-[150px] truncate">
                          {Array.isArray(schedule.days) ? schedule.days.join(', ') : schedule.days}
                        </td>
                        <td className="py-3 px-3 text-right">
                          <button
                            onClick={() => handleDelete(schedule.id)}
                            className="p-1.5 text-rose-400 hover:text-rose-300 border border-transparent hover:border-rose-900/40 hover:bg-rose-950/20 transition rounded cursor-pointer"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>
      ) : (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Create Holiday Form */}
          <div className="bg-[#091124]/40 border border-blue-950/60 rounded-xl p-5 backdrop-blur-md shadow-sm h-fit">
            <h3 className="text-sm font-bold text-white font-mono mb-4 flex items-center gap-2 border-b border-blue-950/40 pb-2">
              <Plus className="w-4 h-4 text-blue-400" />
              ADD NEW EXCEPTION
            </h3>
            <form onSubmit={handleHolidaySubmit} className="space-y-4 font-mono text-xs">
              <div className="space-y-1.5">
                <label className="text-blue-400/80">HOLIDAY NAME</label>
                <input
                  type="text"
                  placeholder="e.g. Water Festival"
                  value={holidayName}
                  onChange={(e) => setHolidayName(e.target.value)}
                  className="w-full bg-[#030712] border border-blue-950/80 rounded px-3 py-2 text-slate-100 focus:border-blue-500 focus:outline-none"
                />
              </div>

              <div className="space-y-1.5">
                <label className="text-blue-400/80">DATE</label>
                <input
                  type="date"
                  value={holidayDate}
                  onChange={(e) => setHolidayDate(e.target.value)}
                  className="w-full bg-[#030712] border border-blue-950/80 rounded px-3 py-2 text-slate-100 focus:border-blue-500 focus:outline-none"
                />
              </div>

              <button
                type="submit"
                className="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded tracking-wide transition active:scale-[0.99] cursor-pointer text-center"
              >
                SAVE EXCEPTION
              </button>
            </form>
          </div>

          {/* Holiday List */}
          <div className="lg:col-span-2 bg-[#091124]/40 border border-blue-950/60 rounded-xl p-5 backdrop-blur-md shadow-sm">
            <h3 className="text-sm font-bold text-white font-mono mb-4 flex items-center gap-2 border-b border-blue-950/40 pb-2">
              <CalendarDays className="w-4 h-4 text-blue-400" />
              REGISTERED CALENDAR EXCEPTIONS
            </h3>

            {loading ? (
              <div className="text-center py-8 font-mono text-xs text-blue-400 animate-pulse">
                SYNCING WITH DATABASE...
              </div>
            ) : holidays.length === 0 ? (
              <div className="text-center py-8 font-mono text-xs text-slate-500">
                No exceptions configured. Automation schedules will run normally every day.
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left font-mono text-xs border-collapse">
                  <thead>
                    <tr className="border-b border-blue-950/80 text-blue-400/80">
                      <th className="py-2.5 px-3">HOLIDAY NAME</th>
                      <th className="py-2.5 px-3">DATE</th>
                      <th className="py-2.5 px-3 text-right">ACTION</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-blue-950/30">
                    {holidays.map(holiday => (
                      <tr key={holiday.id} className="hover:bg-blue-950/10 text-slate-300">
                        <td className="py-3 px-3 font-semibold text-white">
                          {holiday.name}
                        </td>
                        <td className="py-3 px-3 text-white">
                          {new Date(holiday.holiday_date).toLocaleDateString(undefined, {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                          })}
                        </td>
                        <td className="py-3 px-3 text-right">
                          <button
                            onClick={() => handleHolidayDelete(holiday.id)}
                            className="p-1.5 text-rose-400 hover:text-rose-300 border border-transparent hover:border-rose-900/40 hover:bg-rose-950/20 transition rounded cursor-pointer"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default Schedules;
