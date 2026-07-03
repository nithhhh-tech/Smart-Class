import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { authService } from "../services/api";
import { Cpu, AlertCircle, Loader } from "lucide-react";

const Register = () => {
  const navigate = useNavigate();
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (localStorage.getItem("auth_token")) {
      navigate("/dashboard");
    }
  }, [navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      await authService.register(name, email, password);
      navigate("/dashboard");
    } catch (err) {
      console.error(err);
      if (err.response && err.response.data && err.response.data.errors) {
        const validationErrors = Object.values(err.response.data.errors)
          .flat()
          .join(" ");
        setError(validationErrors);
      } else if (
        err.response &&
        err.response.data &&
        err.response.data.message
      ) {
        setError(err.response.data.message);
      } else {
        setError("Registration failed. Please try again later.");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#030712] text-slate-100 relative overflow-x-hidden flex flex-col justify-center items-center p-6">
      {/* Blueprint backdrop + Gradient Blurs */}
      <div className="fixed inset-0 blueprint-grid pointer-events-none z-0"></div>
      <div className="fixed inset-0 pointer-events-none z-0 bg-[radial-gradient(circle_at_center,rgba(29,78,216,0.15)_0%,#030712_70%)]"></div>

      <div className="w-full max-w-md relative z-10">
        {/* Header/Logo */}
        <div className="flex flex-col items-center mb-8">
          <Link to="/" className="flex items-center gap-2 group">
            <div className="w-12 h-12 flex items-center justify-center border border-blue-500/30 rounded bg-blue-950/40 shadow-[0_0_15px_rgba(29,78,216,0.1)]">
              <Cpu className="w-6 h-6 text-blue-400 opacity-90" />
            </div>
          </Link>
          <h2 className="mt-4 text-2xl font-bold tracking-tight text-white font-display">
            Create Device Account
          </h2>
          <p className="mt-1 text-xs font-mono uppercase tracking-[0.16em] text-blue-400/60">
            REGISTER NEW GATEWAY OPERATOR
          </p>
        </div>

        {/* Card */}
        <div className="tick-corners border border-blue-950/60 bg-[#091124]/40 backdrop-blur-md p-8 shadow-2xl">
          {error && (
            <div className="mb-6 p-4 bg-rose-950/30 border border-rose-800/50 text-rose-400 text-sm flex items-start gap-2 rounded-lg">
              <AlertCircle className="w-5 h-5 shrink-0 mt-0.5" />
              <span>{error}</span>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-5">
            <div>
              <label
                htmlFor="name"
                className="block text-xs font-semibold uppercase tracking-wider text-blue-300/80 mb-2 font-mono"
              >
                Full Name
              </label>
              <input
                id="name"
                type="text"
                required
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="username"
                className="w-full px-4 py-3 bg-[#030712]/60 border border-blue-950 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition duration-150 font-mono text-sm"
              />
            </div>

            <div>
              <label
                htmlFor="email"
                className="block text-xs font-semibold uppercase tracking-wider text-blue-300/80 mb-2 font-mono"
              >
                Email Address
              </label>
              <input
                id="email"
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="user@gmail.com"
                className="w-full px-4 py-3 bg-[#030712]/60 border border-blue-950 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition duration-150 font-mono text-sm"
              />
            </div>

            <div>
              <label
                htmlFor="password"
                className="block text-xs font-semibold uppercase tracking-wider text-blue-300/80 mb-2 font-mono"
              >
                Password
              </label>
              <input
                id="password"
                type="password"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="•••••••• (min 8 characters)"
                className="w-full px-4 py-3 bg-[#030712]/60 border border-blue-950 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition duration-150 font-mono text-sm"
              />
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-display font-semibold text-sm hover:from-blue-500 hover:to-cyan-500 transition-all duration-300 shadow-[0_4px_20px_rgba(29,78,216,0.3)] hover:shadow-[0_4px_25px_rgba(6,182,212,0.4)] disabled:opacity-50 flex items-center justify-center gap-2 cursor-pointer"
            >
              {loading ? (
                <>
                  <Loader className="w-4 h-4 animate-spin" />
                  CREATING ACCOUNT...
                </>
              ) : (
                "REGISTER OPERATOR"
              )}
            </button>
          </form>

          <div className="mt-8 pt-6 border-t border-blue-950/40 text-center text-xs font-mono text-slate-400">
            <span>Already registered? </span>
            <Link to="/login" className="text-cyan-400 hover:underline">
              Log in instead &rarr;
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Register;
