import React, { useState, useEffect } from "react";
import { roomService } from "../services/api";
import {
  School,
  MapPin,
  Trash2,
  Plus,
  X,
  Loader,
  FolderPlus,
} from "lucide-react";

const Classrooms = () => {
  const [rooms, setRooms] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);

  // Form states
  const [name, setName] = useState("");
  const [location, setLocation] = useState("");
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState("");

  const fetchRooms = async () => {
    setLoading(true);
    try {
      const data = await roomService.getRooms();
      setRooms(data);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchRooms();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    if (!name.trim()) return;
    setSaving(true);

    try {
      await roomService.addRoom(name, location);
      setName("");
      setLocation("");
      setShowForm(false);
      fetchRooms();
    } catch (err) {
      console.error(err);
      setError("Failed to create classroom. Please try again.");
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id, roomName) => {
    if (
      window.confirm(
        `Are you sure you want to delete "${roomName}"? This will remove all associated telemetry logs and device references.`,
      )
    ) {
      try {
        await roomService.deleteRoom(id);
        fetchRooms();
      } catch (err) {
        console.error(err);
        alert("Failed to delete room.");
      }
    }
  };

  return (
    <div className="space-y-6">
      {/* Top Header Controls */}
      <div className="flex justify-between items-center border-b border-blue-950/40 pb-4">
        <div>
          <h1 className="text-2xl font-bold tracking-tight text-white font-display">
            Classroom Management
          </h1>
          <p className="text-xs text-slate-400 font-mono">
            MANAGE LEARNING SPACES{" "}
            <span className="font-sans">( ការគ្រប់គ្រងកន្លែងសិក្សា )</span>
          </p>
        </div>

        <button
          onClick={() => setShowForm(!showForm)}
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
              ADD CLASSROOM
            </>
          )}
        </button>
      </div>

      {/* Inline Form to Add Classroom */}
      {showForm && (
        <div className="max-w-lg bg-[#091124]/40 border border-blue-950/80 rounded-2xl p-6 backdrop-blur-md shadow-lg tick-corners">
          <h3 className="text-sm font-bold text-slate-200 mb-4 font-mono flex items-center gap-2 uppercase">
            <FolderPlus className="w-4 h-4 text-blue-400" />
            Add New Classroom
          </h3>

          {error && (
            <div className="mb-4 p-3 bg-rose-950/20 border border-rose-900/50 text-rose-400 text-xs rounded-lg">
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label
                htmlFor="room-name"
                className="block text-xs font-semibold uppercase tracking-wider text-blue-300/80 mb-2 font-mono"
              >
                Room Name *
              </label>
              <input
                id="room-name"
                type="text"
                required
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="e.g. Room A-101, Science Lab"
                className="w-full px-4 py-2.5 bg-[#030712]/60 border border-blue-950 rounded-lg text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition duration-150 font-mono text-xs"
              />
            </div>

            <div>
              <label
                htmlFor="room-location"
                className="block text-xs font-semibold uppercase tracking-wider text-blue-300/80 mb-2 font-mono"
              >
                Location Reference
              </label>
              <input
                id="room-location"
                type="text"
                value={location}
                onChange={(e) => setLocation(e.target.value)}
                placeholder="e.g. Block B, Floor 2"
                className="w-full px-4 py-2.5 bg-[#030712]/60 border border-blue-950 rounded-lg text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition duration-150 font-mono text-xs"
              />
            </div>

            <div className="flex gap-2 justify-end pt-2">
              <button
                type="button"
                onClick={() => {
                  setShowForm(false);
                  setName("");
                  setLocation("");
                  setError("");
                }}
                className="px-4 py-2 border border-blue-950 text-slate-400 hover:text-slate-200 transition-colors font-mono text-xs cursor-pointer rounded-lg"
              >
                CANCEL
              </button>
              <button
                type="submit"
                disabled={saving}
                className="flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-mono text-xs transition duration-150 cursor-pointer rounded-lg"
              >
                {saving ? (
                  <>
                    <Loader className="w-3.5 h-3.5 animate-spin" />
                    SAVING...
                  </>
                ) : (
                  "SAVE ROOM"
                )}
              </button>
            </div>
          </form>
        </div>
      )}

      {/* Grid of rooms */}
      {loading ? (
        <div className="text-center py-20">
          <Loader className="w-10 h-10 animate-spin text-blue-500 mx-auto" />
          <p className="mt-3 text-xs text-slate-500 font-mono uppercase tracking-wider">
            Loading classroom directory...
          </p>
        </div>
      ) : rooms.length === 0 ? (
        <div className="border border-blue-950 bg-[#091124]/20 rounded-2xl p-12 text-center text-slate-500 font-mono text-xs max-w-xl mx-auto tick-corners">
          No classrooms registered in the schematic. Click "ADD CLASSROOM" to
          register your first environmental space.
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in">
          {rooms.map((room) => (
            <div
              key={room.id}
              className="bg-[#091124]/40 border border-blue-950/80 rounded-2xl p-6 backdrop-blur-md shadow-md hover:shadow-lg hover:border-blue-900 transition-all duration-200 flex flex-col justify-between tick-corners"
            >
              <div>
                <div className="flex items-start gap-4 mb-4">
                  <div className="p-3 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-xl">
                    <School className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="font-bold text-lg text-slate-100 tracking-tight">
                      {room.name}
                    </h3>
                    <p className="text-xs text-slate-400 font-mono flex items-center gap-1 mt-1">
                      <MapPin className="w-3.5 h-3.5 text-blue-500 shrink-0" />
                      {room.location || "No location coordinates"}
                    </p>
                  </div>
                </div>

                <div className="pt-4 border-t border-blue-950/60 flex justify-between items-center text-xs font-mono">
                  <span className="text-slate-500">ACTIVE DEVICE NODES:</span>
                  <span className="font-bold text-blue-400">
                    {room.devices_count || 0}
                  </span>
                </div>
              </div>

              <div className="mt-6 pt-4 border-t border-blue-950/60 flex justify-end">
                <button
                  onClick={() => handleDelete(room.id, room.name)}
                  className="flex items-center gap-1.5 px-3 py-1.5 border border-rose-950 text-rose-400 bg-rose-950/10 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-300 font-mono text-[10px] cursor-pointer rounded"
                >
                  <Trash2 className="w-3.5 h-3.5" />
                  DELETE
                </button>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default Classrooms;
