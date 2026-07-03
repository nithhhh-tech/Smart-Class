<?php
// ── SensorLog.php ──────────────────────────────────────────
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SensorLog extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'temperature', 'humidity', 'motion', 'recorded_at'];
    protected $casts    = ['recorded_at' => 'datetime', 'motion' => 'boolean'];

    public $timestamps  = false;

    protected static function boot() {
        parent::boot();
        static::creating(fn($m) => $m->recorded_at = $m->recorded_at ?? now());
    }

    public function room() { return $this->belongsTo(Room::class); }
}
