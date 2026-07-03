<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    // Disabling default Eloquent timestamps since we only use triggered_at
    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'type',
        'message',
        'triggered_at',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->triggered_at = $m->triggered_at ?? now());
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
