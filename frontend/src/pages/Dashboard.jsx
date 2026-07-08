import React, { useState, useEffect, useRef } from 'react';
import { roomService, deviceService, telemetryService, alertService } from '../services/api';
import { Thermometer, Droplets, Activity, RefreshCw, Terminal, Power, Zap, Calendar, Sliders, AlertTriangle } from 'lucide-react';
import { Line } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const Dashboard = () => {
  const [rooms, setRooms] = useState([]);
  const [selectedRoomId, setSelectedRoomId] = useState('');
  const [alerts, setAlerts] = useState([]);
  
  // Telemetry state
  const [telemetry, setTelemetry] = useState({
    temperature: '--',
    humidity: '--',
    motion: false,
    motion_status: 'Retrieving data...',
    last_update: 'Never',
  });

  // Devices in the selected room
  const [devices, setDevices] = useState([]);
  
  // Historical trend logs
  const [historyLogs, setHistoryLogs] = useState([]);
  const [historyHours, setHistoryHours] = useState('24');

  // Console Logs
  const [consoleLogs, setConsoleLogs] = useState([
    { time: new Date().toLocaleTimeString(), text: 'Central System Ready. Select a classroom to monitor.', type: 'system' }
  ]);

  const pollingRef = useRef(null);
  const terminalContainerRef = useRef(null);

  // Auto-scroll console
  useEffect(() => {
    if (terminalContainerRef.current) {
      terminalContainerRef.current.scrollTo({
        top: terminalContainerRef.current.scrollHeight,
        behavior: 'smooth'
      });
    }
  }, [consoleLogs]);

  // Load Rooms initially
  useEffect(() => {
    const fetchRooms = async () => {
      try {
        const roomsData = await roomService.getRooms();
        setRooms(roomsData);
        if (roomsData.length > 0) {
          setSelectedRoomId(roomsData[0].id);
          addLog(`Loaded ${roomsData.length} classrooms successfully.`, 'system');
        } else {
          addLog('No classrooms registered yet.', 'warn');
        }
      } catch (err) {
        console.error(err);
        addLog('Failed to retrieve classrooms from API.', 'error');
      }
    };
    fetchRooms();

    return () => {
      if (pollingRef.current) clearInterval(pollingRef.current);
    };
  }, []);

  // Poll telemetry and devices when selected room changes
  useEffect(() => {
    if (!selectedRoomId) return;

    // Clear existing poll
    if (pollingRef.current) {
      clearInterval(pollingRef.current);
    }

    addLog(`Switched monitoring to classroom ID: ${selectedRoomId}.`, 'success');
    fetchDashboardData(selectedRoomId);

    // Set up 5s polling
    pollingRef.current = setInterval(() => {
      fetchDashboardData(selectedRoomId, true);
    }, 5000);

    return () => {
      if (pollingRef.current) clearInterval(pollingRef.current);
    };
  }, [selectedRoomId, historyHours]);

  const addLog = (text, type = 'info') => {
    setConsoleLogs(prev => [
      ...prev,
      { time: new Date().toLocaleTimeString(), text, type }
    ]);
  };

  const fetchDashboardData = async (roomId, isPoll = false) => {
    try {
      // 1. Fetch current telemetry summary
      const telemetryData = await telemetryService.getSummary(roomId);
      setTelemetry({
        temperature: telemetryData.temperature !== null ? parseFloat(telemetryData.temperature).toFixed(1) : '--',
        humidity: telemetryData.humidity !== null ? parseFloat(telemetryData.humidity).toFixed(1) : '--',
        motion: !!telemetryData.motion,
        motion_status: telemetryData.motion ? 'Active movement detected' : 'Room inactive / Empty space',
        last_update: new Date().toLocaleTimeString(),
      });

      // 2. Fetch Devices Status
      const devicesData = await deviceService.getDevices(roomId);
      setDevices(devicesData);

      // 3. Fetch Historical Data (only if not a routine poll to avoid heavy loads, or occasionally)
      if (!isPoll || historyLogs.length === 0) {
        const historyData = await telemetryService.getHistory(roomId, historyHours);
        setHistoryLogs(historyData);
      }

      // 4. Fetch Active Alerts
      const alertsData = await alertService.getAlerts(roomId);
      setAlerts(alertsData);

      if (!isPoll) {
        addLog(`Synchronized active room telemetry and ${devicesData.length} device nodes.`, 'info');
      }
    } catch (err) {
      console.error(err);
      if (!isPoll) {
        addLog('Error reading endpoint telemetry.', 'error');
      }
    }
  };

  const handleDismissAlert = async (alertId) => {
    try {
      await alertService.dismissAlert(alertId);
      setAlerts(prev => prev.filter(a => a.id !== alertId));
      addLog('Alert dismissed successfully.', 'success');
    } catch (err) {
      console.error(err);
      addLog('Failed to dismiss anomaly warning.', 'error');
    }
  };

  const handleDeviceToggle = async (deviceId, deviceName) => {
    addLog(`Sending power toggle signal to device: ${deviceName}...`, 'info');
    try {
      const updatedDevice = await deviceService.toggleDevice(deviceId);
      // Immediately refresh device list locally
      setDevices(prev => prev.map(d => d.id === deviceId ? { ...d, status: updatedDevice.status } : d));
      addLog(
        `Command execution registered. Status: Turn ${updatedDevice.status ? 'ON' : 'OFF'} (via Sanctum/ESP32)`,
        'success'
      );
    } catch (err) {
      console.error(err);
      addLog(`Failed to communicate with device: ${deviceName}.`, 'error');
    }
  };

  const handleManualRefresh = () => {
    if (selectedRoomId) {
      fetchDashboardData(selectedRoomId);
      addLog('Manual hardware scan requested.', 'success');
    } else {
      addLog('No active classroom to scan.', 'warn');
    }
  };

  // Prepare chart datasets
  const chartLabels = historyLogs.map(l => {
    const d = new Date(l.recorded_at);
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  });

  const chartTemps = historyLogs.map(l => parseFloat(l.temperature));
  const chartHumidities = historyLogs.map(l => parseFloat(l.humidity));

  const tempChartData = {
    labels: chartLabels,
    datasets: [
      {
        label: 'Temperature (°C)',
        data: chartTemps,
        borderColor: '#f59e0b',
        borderWidth: 2,
        backgroundColor: 'rgba(245, 158, 11, 0.05)',
        fill: true,
        tension: 0.3,
        pointRadius: 2,
        pointHoverRadius: 6,
      }
    ]
  };

  const humidityChartData = {
    labels: chartLabels,
    datasets: [
      {
        label: 'Humidity (%)',
        data: chartHumidities,
        borderColor: '#3b82f6',
        borderWidth: 2,
        backgroundColor: 'rgba(59, 130, 246, 0.05)',
        fill: true,
        tension: 0.3,
        pointRadius: 2,
        pointHoverRadius: 6,
      }
    ]
  };

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
    },
    scales: {
      y: { grid: { color: 'rgba(59, 130, 246, 0.05)' }, ticks: { color: '#94a3b8', font: { family: 'JetBrains Mono' } } },
      x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { family: 'JetBrains Mono' } } }
    }
  };

  return (
    <div className="space-y-6">
      {/* Dashboard Top bar / Controls */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold tracking-tight text-white font-display flex items-center gap-2">
            <span className="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
            Control Room Dashboard
          </h1>
          <p className="text-xs text-slate-400 font-mono">LIVE ENVIRONMENTAL MONITORING SYSTEM</p>
        </div>

        <div className="flex flex-wrap items-center gap-4">
          {/* Room Selector */}
          <div className="flex items-center gap-2 bg-[#091124]/40 border border-blue-950 p-2 rounded-lg backdrop-blur-sm">
            <label className="text-xs font-mono text-blue-400 font-semibold">CLASSROOM:</label>
            <select
              value={selectedRoomId}
              onChange={(e) => setSelectedRoomId(e.target.value)}
              className="bg-transparent text-sm text-slate-100 focus:outline-none pr-4 cursor-pointer"
            >
              {rooms.length === 0 ? (
                <option value="" disabled>Loading classrooms...</option>
              ) : (
                rooms.map(room => (
                  <option key={room.id} value={room.id} className="bg-[#091124]">
                    {room.name}
                  </option>
                ))
              )}
            </select>
          </div>

          {/* Time range selector */}
          <div className="flex items-center gap-2 bg-[#091124]/40 border border-blue-950 p-2 rounded-lg backdrop-blur-sm">
            <label className="text-xs font-mono text-blue-400 font-semibold">RANGE:</label>
            <select
              value={historyHours}
              onChange={(e) => setHistoryHours(e.target.value)}
              className="bg-transparent text-sm text-slate-100 focus:outline-none pr-4 cursor-pointer"
            >
              <option value="1" className="bg-[#091124]">Last Hour</option>
              <option value="6" className="bg-[#091124]">Last 6 Hours</option>
              <option value="24" className="bg-[#091124]">Last 24 Hours</option>
              <option value="168" className="bg-[#091124]">Last 7 Days</option>
            </select>
          </div>

          {/* Refresh button */}
          <button
            onClick={handleManualRefresh}
            className="flex items-center gap-2 px-4 py-2 border border-blue-500/30 text-blue-400 bg-blue-950/20 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 font-mono text-xs cursor-pointer rounded-lg shadow-sm"
          >
            <RefreshCw className="w-3.5 h-3.5" />
            REFRESH
          </button>
        </div>
      </div>

      {/* Live Telemetry cards grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {/* Temp Card */}
        <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/10 to-orange-500/10 border border-amber-500/20 p-6 shadow-md tick-corners">
          <div className="absolute top-0 right-0 p-3 opacity-10">
            <Thermometer className="w-16 h-16 text-amber-500" />
          </div>
          <div className="flex justify-between items-center mb-2">
            <span className="text-xs font-semibold tracking-wider text-amber-500 uppercase font-mono">Temperature</span>
            <span className="flex h-2 w-2 relative">
              <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
              <span className="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
            </span>
          </div>
          <div className="flex items-baseline">
            <span className="text-4xl font-bold text-slate-100 font-display">{telemetry.temperature}</span>
            <span className="text-xl font-medium text-slate-400 ml-1">°C</span>
          </div>
          <p className="text-[11px] text-slate-400 mt-3 font-mono">DHT22 environmental sensor node</p>
        </div>

        {/* Humidity Card */}
        <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 border border-blue-500/20 p-6 shadow-md tick-corners">
          <div className="absolute top-0 right-0 p-3 opacity-10">
            <Droplets className="w-16 h-16 text-blue-500" />
          </div>
          <div className="flex justify-between items-center mb-2">
            <span className="text-xs font-semibold tracking-wider text-blue-400 uppercase font-mono">Humidity</span>
            <span className="flex h-2 w-2 relative">
              <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
              <span className="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
            </span>
          </div>
          <div className="flex items-baseline">
            <span className="text-4xl font-bold text-slate-100 font-display">{telemetry.humidity}</span>
            <span className="text-xl font-medium text-slate-400 ml-1">%</span>
          </div>
          <p className="text-[11px] text-slate-400 mt-3 font-mono">Relative atmospheric moisture index</p>
        </div>

        {/* Motion Card */}
        <div className={`relative overflow-hidden rounded-2xl border p-6 shadow-md transition-all duration-500 tick-corners ${
          telemetry.motion
            ? 'bg-gradient-to-br from-rose-500/10 to-pink-500/10 border-rose-500/20'
            : 'bg-[#091124]/40 border-blue-950'
        }`}>
          <div className="absolute top-0 right-0 p-3 opacity-10">
            <Activity className="w-16 h-16 text-blue-500" />
          </div>
          <div className="flex justify-between items-center mb-2">
            <span className={`text-xs font-semibold tracking-wider uppercase font-mono ${telemetry.motion ? 'text-rose-400' : 'text-slate-400'}`}>Motion Status</span>
            <span className="flex h-2 w-2 relative">
              <span className={`animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 ${telemetry.motion ? 'bg-rose-400' : 'bg-slate-400'}`}></span>
              <span className={`relative inline-flex rounded-full h-2 w-2 ${telemetry.motion ? 'bg-rose-500' : 'bg-slate-500'}`}></span>
            </span>
          </div>
          <div className="flex items-center gap-3">
            <span className={`text-2xl font-bold text-slate-100 font-display`}>
              {telemetry.motion ? 'Active' : 'No Motion'}
            </span>
          </div>
          <p className="text-[11px] text-slate-400 mt-3 font-mono">{telemetry.motion_status}</p>
        </div>
      </div>

      {/* Middle Grid: Devices controls & Console Logs */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Device Quick Controls */}
        <div className="bg-[#091124]/40 border border-blue-950 rounded-2xl p-6 backdrop-blur-md relative overflow-hidden">
          <div className="flex items-center gap-2 text-blue-400 font-semibold mb-4 text-sm font-mono border-b border-blue-950 pb-2">
            <Zap className="w-4 h-4 text-yellow-500" />
            DEVICE POWER CONSOLE
          </div>

          <div className="space-y-4 max-h-[300px] overflow-y-auto custom-scrollbar">
            {devices.length === 0 ? (
              <div className="text-slate-500 text-xs py-8 text-center font-mono">
                No active devices found in this classroom.
              </div>
            ) : (
              devices.map(device => {
                const isChecked = !!device.status;
                return (
                  <div
                    key={device.id}
                    className="flex items-center justify-between p-3.5 bg-[#030712]/40 border border-blue-950/60 rounded-xl hover:border-blue-950 transition duration-150"
                  >
                    <div className="flex items-center gap-3">
                      <div className={`p-2 rounded-lg ${isChecked ? 'bg-indigo-500/10 text-indigo-400' : 'bg-blue-950/20 text-slate-500'}`}>
                        <Power className="w-4 h-4" />
                      </div>
                      <div>
                        <div className="text-sm font-bold text-slate-200">{device.name}</div>
                        <div className="text-[10px] text-slate-500 uppercase font-mono">{device.type}</div>
                      </div>
                    </div>

                    <button
                      onClick={() => handleDeviceToggle(device.id, device.name)}
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
                );
              })
            )}
          </div>
        </div>
        {/* Classroom Anomaly Alerts */}
        <div className="bg-[#091124]/40 border border-blue-950 rounded-2xl p-6 backdrop-blur-md flex flex-col justify-between">
          <div>
            <div className="flex items-center gap-2 text-rose-400 font-semibold mb-4 text-sm font-mono border-b border-blue-950 pb-2">
              <AlertTriangle className="w-4 h-4 text-rose-500 animate-pulse" />
              CLASSROOM ANOMALY ALERTS
            </div>
            
            <div className="space-y-3 max-h-56 overflow-y-auto custom-scrollbar pr-1">
              {alerts.length === 0 ? (
                <div className="text-slate-500 text-xs py-16 text-center font-mono">
                  No active anomalies detected. All systems operating normally.
                </div>
              ) : (
                alerts.map(alert => (
                  <div 
                    key={alert.id}
                    className="flex items-center justify-between p-3 bg-rose-950/10 border border-rose-950/30 rounded-xl gap-3 animate-fade-in"
                  >
                    <div className="flex items-center gap-3">
                      <div className="p-2 rounded-lg bg-rose-500/10 text-rose-500 shrink-0">
                        <AlertTriangle className="w-4 h-4" />
                      </div>
                      <div>
                        <div className="text-xs font-bold text-slate-200">{alert.message}</div>
                        <div className="text-[9px] text-slate-500 uppercase font-mono mt-0.5">
                          {alert.type} &bull; {new Date(alert.triggered_at).toLocaleTimeString()}
                        </div>
                      </div>
                    </div>
                    <button
                      onClick={() => handleDismissAlert(alert.id)}
                      className="px-2.5 py-1 border border-rose-900/60 text-rose-400 bg-rose-950/20 hover:bg-rose-600 hover:text-white hover:border-rose-600 text-[9px] font-bold rounded transition cursor-pointer shrink-0"
                    >
                      DISMISS
                    </button>
                  </div>
                ))
              )}
            </div>
          </div>

          <div className="mt-3 pt-3 border-t border-blue-950/40 flex justify-between items-center text-[10px] text-slate-500 font-mono">
            <span>HEALTH STATE: ONLINE</span>
            <span>LAST SYNC: {telemetry.last_update}</span>
          </div>
        </div>
      </div>

      {/* Historical Telemetry Charts (from history.blade.php) */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Temperature Trend */}
        <div className="bg-[#091124]/40 border border-blue-950 rounded-2xl p-6 backdrop-blur-md">
          <h3 className="text-sm font-bold text-slate-200 mb-6 flex items-center gap-2 font-mono">
            <span className="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            TEMPERATURE TREND LINE (°C)
          </h3>
          <div className="h-64 relative">
            {historyLogs.length === 0 ? (
              <div className="absolute inset-0 flex items-center justify-center text-xs text-slate-500 font-mono">
                No telemetry recorded for this timeframe.
              </div>
            ) : (
              <Line data={tempChartData} options={chartOptions} />
            )}
          </div>
        </div>

        {/* Humidity Trend */}
        <div className="bg-[#091124]/40 border border-blue-950 rounded-2xl p-6 backdrop-blur-md">
          <h3 className="text-sm font-bold text-slate-200 mb-6 flex items-center gap-2 font-mono">
            <span className="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            RELATIVE HUMIDITY TREND LINE (%)
          </h3>
          <div className="h-64 relative">
            {historyLogs.length === 0 ? (
              <div className="absolute inset-0 flex items-center justify-center text-xs text-slate-500 font-mono">
                No telemetry recorded for this timeframe.
              </div>
            ) : (
              <Line data={humidityChartData} options={chartOptions} />
            )}
          </div>
        </div>
      </div>

      {/* Historical Logs Table Records */}
      <div className="bg-[#091124]/40 border border-blue-950 rounded-2xl p-6 backdrop-blur-md">
        <div className="flex items-center gap-2 text-blue-400 font-semibold mb-6 text-sm font-mono border-b border-blue-950 pb-2">
          <Calendar className="w-4 h-4 text-blue-400" />
          HISTORICAL LOG RECORDS
        </div>

        <div className="overflow-x-auto rounded-xl border border-blue-950 custom-scrollbar">
          <table className="min-w-full divide-y divide-blue-950 text-xs font-mono">
            <thead className="bg-[#030712]/60 text-slate-400 font-semibold text-left">
              <tr>
                <th className="px-6 py-4">TIMESTAMP</th>
                <th className="px-6 py-4">TEMPERATURE (°C)</th>
                <th className="px-6 py-4">HUMIDITY (%)</th>
                <th className="px-6 py-4">MOTION SIGNAL</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-blue-950 text-slate-300">
              {historyLogs.length === 0 ? (
                <tr>
                  <td colSpan="4" className="px-6 py-8 text-center text-slate-500">
                    Select a classroom to display historical logs.
                  </td>
                </tr>
              ) : (
                [...historyLogs].reverse().slice(0, 10).map((log, index) => (
                  <tr key={index} className="hover:bg-blue-950/20 transition duration-100">
                    <td className="px-6 py-3.5 font-medium text-slate-200">
                      {new Date(log.recorded_at).toLocaleString()}
                    </td>
                    <td className="px-6 py-3.5">
                      {parseFloat(log.temperature).toFixed(1)} °C
                    </td>
                    <td className="px-6 py-3.5">
                      {parseFloat(log.humidity).toFixed(1)} %
                    </td>
                    <td className="px-6 py-3.5">
                      <span className={`inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold ${
                        log.motion
                          ? 'bg-rose-950/30 text-rose-400 border border-rose-900/40'
                          : 'bg-slate-900/60 text-slate-400 border border-slate-800'
                      }`}>
                        <span className={`h-1.5 w-1.5 rounded-full ${log.motion ? 'bg-rose-500' : 'bg-slate-500'}`}></span>
                        {log.motion ? 'Active' : 'No Motion'}
                      </span>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
        {historyLogs.length > 10 && (
          <div className="mt-3 text-right text-[10px] text-slate-500">
            Showing latest 10 records. Total logged in range: {historyLogs.length}
          </div>
        )}
      </div>
    </div>
  );
};

export default Dashboard;
