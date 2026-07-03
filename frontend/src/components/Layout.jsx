import React, { useEffect, useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { authService } from '../services/api';
import { LayoutDashboard, School, Cpu, LogOut, User as UserIcon } from 'lucide-react';

const Layout = ({ children }) => {
  const navigate = useNavigate();
  const location = useLocation();
  const [user, setUser] = useState(null);

  useEffect(() => {
    const storedUser = localStorage.getItem('auth_user');
    if (storedUser) {
      setUser(JSON.parse(storedUser));
    } else {
      navigate('/login');
    }
  }, [navigate]);

  const handleLogout = async () => {
    try {
      await authService.logout();
      navigate('/login');
    } catch (err) {
      console.error('Logout error', err);
    }
  };

  const isActive = (path) => location.pathname === path;

  return (
    <div className="min-h-screen bg-[#030712] text-slate-100 relative overflow-x-hidden flex flex-col justify-between selection:bg-blue-500/30">
      {/* Blueprint backdrop + Gradient Blurs */}
      <div className="fixed inset-0 blueprint-grid pointer-events-none z-0"></div>
      <div className="fixed inset-0 pointer-events-none z-0 bg-[radial-gradient(circle_at_top,rgba(29,78,216,0.12)_0%,#030712_70%)]"></div>
      <div className="fixed top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full pointer-events-none z-0 bg-blue-600/5 blur-[120px]"></div>
      <div className="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full pointer-events-none z-0 bg-cyan-600/5 blur-[120px]"></div>

      {/* Header */}
      <header className="w-full max-w-7xl mx-auto px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-4 border-b border-blue-950/40 backdrop-blur-md bg-[#030712]/30 relative z-10">
        <Link to="/" className="flex items-center gap-3 group">
          <div className="w-10 h-10 flex items-center justify-center border border-blue-500/30 rounded bg-blue-950/40 group-hover:border-cyan-400/60 transition-colors duration-300 shadow-[0_0_15px_rgba(29,78,216,0.1)]">
            <Cpu className="w-5 h-5 text-blue-400 opacity-90 group-hover:text-cyan-400 transition-colors duration-300" />
          </div>
          <div className="leading-tight">
            <span className="block text-sm font-semibold tracking-tight text-white font-display group-hover:text-blue-400 transition-colors duration-300">Smart Classroom</span>
            <span className="block text-[9.5px] font-mono uppercase tracking-[0.18em] text-blue-400/70">Rev. React-SPA &middot; IoT v2</span>
          </div>
        </Link>

        {/* Navigation */}
        <nav className="flex items-center gap-2 font-mono text-xs">
          <Link
            to="/dashboard"
            className={`flex items-center gap-2 px-4 py-2 border transition-all duration-300 ${
              isActive('/dashboard')
                ? 'border-blue-500 text-blue-400 bg-blue-950/30 shadow-[0_0_15px_rgba(59,130,246,0.15)]'
                : 'border-blue-950/40 text-slate-400 hover:text-blue-400 hover:border-blue-500/40 bg-[#091124]/20'
            }`}
          >
            <LayoutDashboard className="w-3.5 h-3.5" />
            DASHBOARD
          </Link>
          <Link
            to="/classrooms"
            className={`flex items-center gap-2 px-4 py-2 border transition-all duration-300 ${
              isActive('/classrooms')
                ? 'border-blue-500 text-blue-400 bg-blue-950/30 shadow-[0_0_15px_rgba(59,130,246,0.15)]'
                : 'border-blue-950/40 text-slate-400 hover:text-blue-400 hover:border-blue-500/40 bg-[#091124]/20'
            }`}
          >
            <School className="w-3.5 h-3.5" />
            CLASSROOMS
          </Link>
          <Link
            to="/devices"
            className={`flex items-center gap-2 px-4 py-2 border transition-all duration-300 ${
              isActive('/devices')
                ? 'border-blue-500 text-blue-400 bg-blue-950/30 shadow-[0_0_15px_rgba(59,130,246,0.15)]'
                : 'border-blue-950/40 text-slate-400 hover:text-blue-400 hover:border-blue-500/40 bg-[#091124]/20'
            }`}
          >
            <Cpu className="w-3.5 h-3.5" />
            DEVICES
          </Link>
        </nav>

        {/* User Info / Logout */}
        {user && (
          <div className="flex items-center gap-4 relative z-10 font-mono text-xs">
            <div className="flex items-center gap-2 text-slate-300 bg-blue-950/20 px-3 py-1.5 border border-blue-950/40">
              <UserIcon className="w-3.5 h-3.5 text-blue-400" />
              <span>{user.name}</span>
            </div>
            <button
              onClick={handleLogout}
              className="px-3 py-1.5 border border-rose-950 text-rose-400 bg-rose-950/10 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-300 flex items-center gap-1 cursor-pointer"
            >
              <LogOut className="w-3.5 h-3.5" />
              EXIT
            </button>
          </div>
        )}
      </header>

      {/* Main Content */}
      <main className="w-full max-w-7xl mx-auto px-6 py-8 flex-grow relative z-10">
        {children}
      </main>

      {/* Footer */}
      <footer className="w-full max-w-7xl mx-auto px-6 py-6 border-t border-blue-950/40 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-blue-400/50 font-mono relative z-10 bg-[#030712]/30 backdrop-blur-md">
        <p>&copy; {new Date().getFullYear()} Smart Classroom Hub. Decoupled Architecture.</p>
        <div className="flex gap-6">
          <a href="#" className="hover:text-cyan-400 transition-colors">Documentation</a>
          <a href="#" className="hover:text-cyan-400 transition-colors">Privacy Architecture</a>
        </div>
      </footer>
    </div>
  );
};

export default Layout;
