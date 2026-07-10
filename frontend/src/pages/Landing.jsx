import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { Cpu, ArrowRight } from "lucide-react";

const Landing = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem("auth_token");
    setIsLoggedIn(!!token);
  }, []);

  return (
    <div className="min-h-screen bg-[#030712] text-slate-100 relative overflow-x-hidden flex flex-col justify-between selection:bg-blue-500/30">
      {/* Blueprint backdrop + Gradient Blurs */}
      <div className="fixed inset-0 blueprint-grid pointer-events-none z-0"></div>
      <div className="fixed inset-0 pointer-events-none z-0 bg-[radial-gradient(circle_at_top,rgba(29,78,216,0.15)_0%,#030712_70%)]"></div>
      <div className="fixed top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full pointer-events-none z-0 bg-blue-600/5 blur-[120px]"></div>
      <div className="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full pointer-events-none z-0 bg-cyan-600/5 blur-[120px]"></div>

      {/* Header */}
      <header className="w-full max-w-6xl mx-auto px-6 py-6 flex items-center justify-between border-b border-blue-950/40 backdrop-blur-md bg-[#030712]/20 relative z-10">
        <Link to="/" className="flex items-center gap-3 group">
          <div className="w-9 h-9 flex items-center justify-center border border-blue-500/30 rounded bg-blue-950/40 group-hover:border-cyan-400/60 transition-colors duration-300 shadow-[0_0_15px_rgba(29,78,216,0.1)]">
            <Cpu className="w-4.5 h-4.5 text-blue-400 opacity-90 group-hover:text-cyan-400 transition-colors duration-300" />
          </div>
          <div className="leading-tight">
            <span className="block text-sm font-semibold tracking-tight text-white font-display group-hover:text-blue-400 transition-colors duration-300">
              Smart Classroom
            </span>
            <span className="block text-[9.5px] font-mono uppercase tracking-[0.18em] text-blue-400/70">
              By &middot; Team 7 &middot; E5-Y2
            </span>
          </div>
        </Link>

        <nav className="flex items-center gap-2 font-mono text-xs">
          {isLoggedIn ? (
            <Link
              to="/dashboard"
              className="px-4 py-2 border border-blue-500 text-blue-400 bg-blue-950/20 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 tracking-wide shadow-[0_0_15px_rgba(59,130,246,0.15)]"
            >
              [ OPEN DASHBOARD ]
            </Link>
          ) : (
            <>
              <Link
                to="/login"
                className="text-slate-400 hover:text-blue-400 transition-colors py-2 px-3 tracking-wide"
              >
                LOG_IN
              </Link>
              <Link
                to="/register"
                className="px-4 py-2 border border-blue-900 text-slate-200 bg-blue-950/10 hover:border-cyan-500 hover:text-cyan-400 transition-all duration-300 tracking-wide"
              >
                REGISTER &rarr;
              </Link>
            </>
          )}
        </nav>
      </header>

      {/* Main Hero and specs */}
      <main className="w-full max-w-6xl mx-auto px-6 py-12 md:py-16 relative z-10 flex-grow">
        {/* Schematic Container */}
        <div className="tick-corners border border-blue-950/60 px-6 md:px-12 py-12 md:py-16 bg-[#091124]/40 backdrop-blur-md relative overflow-hidden shadow-[2xl] shadow-blue-950/20">
          <div className="radar-scan"></div>
          <div className="max-w-3xl relative z-10">
            <h1 className="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-[-0.02em] text-white leading-[1.12] mb-7">
              A blueprint for
              <br />
              <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-cyan-400">
                automated
              </span>{" "}
              classrooms.
            </h1>

            <p className="text-slate-300 text-base leading-[1.9] max-w-xl font-light mb-10">
              ប្រព័ន្ធបរិស្ថានស្វ័យប្រវត្តិ និងរឹងមាំមួយ
              ដែលតាមដានទិន្នន័យទូរវាស់ចម្ងាយ (telemetry)
              ក្នុងថ្នាក់រៀនតាមពេលវេលាជាក់ស្តែង
              ត្រួតពិនិត្យវត្តមានសកម្មរបស់សិស្ស
              និងបញ្ជាឱ្យឧបករណ៍វៃឆ្លាតដំណើរការផ្អែកលើការកំណត់ទុកជាមុនដើម្បីសន្សំសំចៃថាមពល។
            </p>

            <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
              {isLoggedIn ? (
                <Link
                  to="/dashboard"
                  className="px-7 py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-display font-semibold text-sm hover:from-blue-500 hover:to-cyan-500 transition-all duration-300 text-center shadow-[0_4px_20px_rgba(29,78,216,0.3)] hover:shadow-[0_4px_25px_rgba(6,182,212,0.4)] hover:scale-[1.02]"
                >
                  Open Hub Dashboard
                </Link>
              ) : (
                <>
                  <Link
                    to="/login"
                    className="px-7 py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-display font-semibold text-sm hover:from-blue-500 hover:to-cyan-500 transition-all duration-300 text-center shadow-[0_4px_20px_rgba(29,78,216,0.3)] hover:shadow-[0_4px_25px_rgba(6,182,212,0.4)] hover:scale-[1.02]"
                  >
                    Access Dashboard Portal
                  </Link>
                  <Link
                    to="/register"
                    className="px-7 py-3.5 border border-blue-800/80 bg-blue-950/10 text-slate-200 font-display font-medium text-sm hover:border-cyan-400 hover:text-cyan-400 transition-all duration-300 text-center backdrop-blur-sm"
                  >
                    Create Device Account
                  </Link>
                </>
              )}
            </div>
          </div>
        </div>

        {/* Schematic drawing */}
        <div className="mt-6 border border-blue-950/60 bg-[#091124]/40 backdrop-blur-md p-6 sm:p-10 shadow-xl">
          <div className="flex items-center justify-between mb-6 font-mono text-[10px] uppercase tracking-[0.16em] text-blue-400/70">
            <span>Function &mdash; Node Layout / Smart Classroom</span>
            <span className="hidden sm:inline">By Team 7 &middot; E5-Y2</span>
          </div>

          <svg
            viewBox="0 0 800 300"
            className="w-full h-auto"
            role="img"
            aria-label="Schematic diagram of classroom sensor nodes wired to a central hub"
          >
            {/* Room outline */}
            <rect
              x="40"
              y="30"
              width="720"
              height="240"
              fill="none"
              stroke="#1e3a8a"
              strokeWidth="1.5"
              opacity="0.6"
            />
            <rect
              x="40"
              y="30"
              width="720"
              height="240"
              fill="none"
              stroke="#2563eb"
              strokeWidth="10"
              opacity="0.1"
            />

            {/* Door */}
            <path
              d="M 40 230 A 40 40 0 0 1 80 270"
              fill="none"
              stroke="#2563eb"
              strokeWidth="1"
              strokeDasharray="3 3"
              opacity="0.5"
            />

            {/* Wiring */}
            <g
              stroke="#3b82f6"
              strokeWidth="1.25"
              className="schema-line"
              fill="none"
              opacity="0.8"
            >
              <path d="M 400 150 L 150 80" />
              <path d="M 400 150 L 650 80" />
              <path d="M 400 150 L 150 220" />
              <path d="M 400 150 L 650 220" />
              <path d="M 400 150 L 400 60" />
            </g>

            {/* Central hub */}
            <rect
              x="372"
              y="128"
              width="56"
              height="44"
              rx="2"
              fill="#030712"
              stroke="#2563eb"
              strokeWidth="1.5"
            />
            <text
              x="400"
              y="154"
              textAnchor="middle"
              fill="#60a5fa"
              fontSize="9"
              fontFamily="JetBrains Mono, monospace"
              fontWeight="600"
            >
              HUB
            </text>

            {/* Sensor nodes */}
            <g
              fontFamily="JetBrains Mono, monospace"
              fontSize="9"
              fill="#94a3b8"
            >
              <circle
                cx="150"
                cy="80"
                r="5"
                fill="#030712"
                stroke="#06b6d4"
                strokeWidth="1.5"
              />
              <circle
                cx="150"
                cy="80"
                r="2"
                fill="#06b6d4"
                className="node-pulse"
              />
              <text x="150" y="65" fontWeight="500">
                DHT22
              </text>
              <text x="150" y="98" fill="#06b6d4" fontWeight="500">
                TEMP / HUM
              </text>
              <circle
                cx="650"
                cy="80"
                r="5"
                fill="#030712"
                stroke="#06b6d4"
                strokeWidth="1.5"
              />
              <circle
                cx="650"
                cy="80"
                r="2"
                fill="#06b6d4"
                className="node-pulse"
                style={{ animationDelay: ".4s" }}
              />
              <text x="650" y="65" textAnchor="end" fontWeight="500"></text>
              {/*MQ135*/}
              <text
                x="650"
                y="98"
                fill="#06b6d4"
                textAnchor="end"
                fontWeight="500"
              ></text>
              {/*AIR QUALITY*/}
              <circle
                cx="150"
                cy="220"
                r="5"
                fill="#030712"
                stroke="#3b82f6"
                strokeWidth="1.5"
              />
              <circle
                cx="150"
                cy="220"
                r="2"
                fill="#3b82f6"
                className="node-pulse"
                style={{ animationDelay: ".8s" }}
              />
              <text x="150" y="240" fontWeight="500">
                PIR
              </text>
              <text x="150" y="252" fill="#3b82f6" fontWeight="500">
                MOTION
              </text>
              <circle
                cx="650"
                cy="220"
                r="5"
                fill="#030712"
                stroke="#3b82f6"
                strokeWidth="1.5"
              />
              <circle
                cx="650"
                cy="220"
                r="2"
                fill="#3b82f6"
                className="node-pulse"
                style={{ animationDelay: "1.2s" }}
              />
              <text x="650" y="240" textAnchor="end" fontWeight="500">
                RELAY
              </text>
              <text
                x="650"
                y="252"
                fill="#3b82f6"
                textAnchor="end"
                fontWeight="500"
              >
                LIGHTS / FANS
              </text>
              <circle
                cx="400"
                cy="60"
                r="5"
                fill="#030712"
                stroke="#cbd5e1"
                strokeWidth="1.5"
              />
              <text
                x="400"
                y="45"
                textAnchor="middle"
                fill="#cbd5e1"
                fontWeight="500"
              >
                ESP32
              </text>
            </g>
          </svg>
        </div>

        {/* Spec Sheet Capability Cards */}
        <div className="mt-20">
          <div className="flex items-baseline justify-between mb-8 border-b border-blue-950/60 pb-4">
            <h2 className="font-display text-2xl sm:text-3xl font-semibold tracking-[-0.01em] text-white">
              Ecosystem Spec Sheet ( ប្រព័ន្ធអេកូឡូស៊ី )
            </h2>
            <span className="font-mono text-[10px] uppercase tracking-[0.16em] text-blue-400/70">
              04 modules
            </span>
          </div>

          <div className="divide-y divide-blue-950/40 border-t border-blue-950/60">
            <div className="group grid sm:grid-cols-[80px_1fr_2fr] gap-3 sm:gap-8 py-7 items-start hover:bg-blue-950/10 transition-colors duration-300 px-2 rounded-sm">
              <span className="font-mono text-blue-400 group-hover:text-cyan-400 transition-colors duration-300 text-sm">
                01
              </span>
              <h3 className="font-display text-white group-hover:text-blue-400 transition-colors duration-300 font-medium text-base">
                Live Telemetry Feeds
              </h3>
              <p className="text-[13px] text-slate-400 group-hover:text-slate-300 transition-colors duration-300 leading-relaxed font-light">
                ផ្សាយបន្តផ្ទាល់ទិន្នន័យសីតុណ្ហភាព សំណើម
                និងស្ថានភាពចលនាជាប្រចាំពី ESP32 nodes ទៅកាន់ API backend
                ក្នុងពេលជាក់ស្តែង (Real-time)។
              </p>
            </div>

            <div className="group grid sm:grid-cols-[80px_1fr_2fr] gap-3 sm:gap-8 py-7 items-start hover:bg-blue-950/10 transition-colors duration-300 px-2 rounded-sm">
              <span className="font-mono text-blue-400 group-hover:text-cyan-400 transition-colors duration-300 text-sm">
                02
              </span>
              <h3 className="font-display text-white group-hover:text-blue-400 transition-colors duration-300 font-medium text-base">
                Appliance Controls
              </h3>
              <p className="text-[13px] text-slate-400 group-hover:text-slate-300 transition-colors duration-300 leading-relaxed font-light">
                បញ្ជាបិទបើក (Toggle) រីឡេម៉ូឌុល (Relay modules) ភ្លាមៗ
                ដើម្បីគ្រប់គ្រងឧបករណ៍បំភ្លឺ កង្ហារ និងឧបករណ៍បរិស្ថានផ្សេងៗទៀត
                តាមរយៈម៉ាក្រូកុងត្រូល័ររូបវន្ត (Physical microcontrollers)។
              </p>
            </div>

            <div className="group grid sm:grid-cols-[80px_1fr_2fr] gap-3 sm:gap-8 py-7 items-start hover:bg-blue-950/10 transition-colors duration-300 px-2 rounded-sm">
              <span className="font-mono text-blue-400 group-hover:text-cyan-400 transition-colors duration-300 text-sm">
                03
              </span>
              <h3 className="font-display text-white group-hover:text-blue-400 transition-colors duration-300 font-medium text-base">
                Automated Savings
              </h3>
              <p className="text-[13px] text-slate-400 group-hover:text-slate-300 transition-colors duration-300 leading-relaxed font-light">
                ឧបករណ៍ចាប់សញ្ញាចលនា បើកដំណើរការគោលការណ៍គ្រប់គ្រងថាមពលវៃឆ្លាត
                ភ្លាមៗនៅពេលដែលកន្លែងសិក្សាគ្មានមនុស្សនៅ។
              </p>
            </div>

            <div className="group grid sm:grid-cols-[80px_1fr_2fr] gap-3 sm:gap-8 py-7 items-start hover:bg-blue-950/10 transition-colors duration-300 px-2 rounded-sm">
              <span className="font-mono text-blue-400 group-hover:text-cyan-400 transition-colors duration-300 text-sm">
                04
              </span>
              <h3 className="font-display text-white group-hover:text-blue-400 transition-colors duration-300 font-medium text-base">
                Historical Logs
              </h3>
              <p className="text-[13px] text-slate-400 group-hover:text-slate-300 transition-colors duration-300 leading-relaxed font-light">
                រាល់ទិន្នន័យអានទាំងអស់ត្រូវបានរក្សាទុកជាប្រចាំ
                ដើម្បីផ្គត់ផ្គង់ដល់ក្រាហ្វបង្ហាញការប្រើប្រាស់
                និងតារាងបង្កើនប្រសិទ្ធភាពតាមពេលវេលា។
              </p>
            </div>
          </div>
        </div>

        {/* Specs manifest */}
        <div className="mt-20 pt-8 border-t border-blue-950/60 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
          <div>
            <h4 className="font-mono text-[10px] uppercase tracking-[0.18em] text-blue-400/70 mb-3">
              Hardware Layer
            </h4>
            <div className="flex flex-wrap gap-2 text-slate-300 text-xs font-mono">
              <span className="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">
                ESP32 NodeMCU
              </span>
              <span className="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">
                DHT22 Module
              </span>
              <span className="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">
                PIR Motion Node
              </span>
            </div>
          </div>
          <div>
            <h4 className="font-mono text-[10px] uppercase tracking-[0.18em] text-blue-400/70 mb-3">
              Software Stack
            </h4>
            <div className="flex flex-wrap gap-2 text-slate-300 text-xs font-mono">
              <span className="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">
                Laravel 12 API
              </span>
              <span className="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">
                React v19
              </span>
              <span className="px-3 py-1.5 border border-blue-950/60 bg-blue-950/10 hover:border-blue-500/50 transition-colors duration-300">
                Tailwind CSS v4
              </span>
            </div>
          </div>
        </div>
      </main>

      {/* Footer */}
      <footer className="w-full max-w-6xl mx-auto px-6 py-8 border-t border-blue-950/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-blue-400/60 font-mono relative z-10 bg-[#030712]/40 backdrop-blur-sm">
        <div>
          <ul>
            <li>
              <h3 className="font-bold text-blue-400/90 mb-2">Team Members</h3>
              <p>Van Phanith</p>
              <p>Lon livireakboth</p>
              <p>Lun Lytayhok</p>
              <p>Van Chanvisal</p>
              <p>Rith Chanpanha</p>
              <p>Rim Pharun</p>
            </li>
          </ul>
        </div>
        <p>
          &copy; {new Date().getFullYear()} Smart Classroom Hub.​Create by Team
          7 &middot; E5-Y2.
        </p>
        <div className="flex gap-6">
          <a href="#" className="hover:text-cyan-400 transition-colors">
            Documentation
          </a>
          <a
            href="/Dashboard"
            className="hover:text-cyan-400 transition-colors"
          >
            Dashboard
          </a>
        </div>
      </footer>
    </div>
  );
};

export default Landing;
