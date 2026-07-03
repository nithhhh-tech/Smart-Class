import axios from 'axios';

const API = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request Interceptor: Attach Sanctum API token if present
API.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response Interceptor: Handle auth errors (e.g. 401 Unauthorized)
API.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      // Clear token and redirect to login if unauthorized
      localStorage.removeItem('auth_token');
      localStorage.removeItem('auth_user');
      if (window.location.pathname !== '/login' && window.location.pathname !== '/register' && window.location.pathname !== '/') {
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  }
);

// Authentication Endpoints
export const authService = {
  register: async (name, email, password) => {
    const res = await API.post('/register', { name, email, password });
    if (res.data.token) {
      localStorage.setItem('auth_token', res.data.token);
      localStorage.setItem('auth_user', JSON.stringify(res.data.user));
    }
    return res.data;
  },
  login: async (email, password) => {
    const res = await API.post('/login', { email, password });
    if (res.data.token) {
      localStorage.setItem('auth_token', res.data.token);
      localStorage.setItem('auth_user', JSON.stringify(res.data.user));
    }
    return res.data;
  },
  logout: async () => {
    try {
      await API.post('/logout');
    } finally {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('auth_user');
    }
  },
  getCurrentUser: async () => {
    const res = await API.get('/user');
    return res.data;
  },
};

// Classroom / Room Endpoints
export const roomService = {
  getRooms: async () => {
    const res = await API.get('/rooms');
    return res.data.data;
  },
  addRoom: async (name, location) => {
    const res = await API.post('/rooms', { name, location });
    return res.data.data;
  },
  deleteRoom: async (id) => {
    await API.delete(`/rooms/${id}`);
  },
};

// Device Endpoints
export const deviceService = {
  getDevices: async (roomId = null) => {
    const url = roomId ? `/devices?room_id=${roomId}` : '/devices';
    const res = await API.get(url);
    return res.data.data;
  },
  addDevice: async (roomId, name, type) => {
    const res = await API.post('/devices', {
      room_id: roomId,
      name,
      type,
      status: false,
    });
    return res.data.data;
  },
  deleteDevice: async (id) => {
    await API.delete(`/devices/${id}`);
  },
  toggleDevice: async (id) => {
    const res = await API.post(`/devices/${id}/toggle`);
    return res.data.data;
  },
};

// Telemetry Dashboard Endpoints
export const telemetryService = {
  getSummary: async (roomId) => {
    const res = await API.get(`/dashboard/summary?room_id=${roomId}`);
    return res.data.data;
  },
  getHistory: async (roomId, hours = 24) => {
    const res = await API.get(`/sensor-logs/history?room_id=${roomId}&hours=${hours}`);
    return res.data.data;
  },
};

export default API;
