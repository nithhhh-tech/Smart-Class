<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'device_id',
        'action',
        'run_at',
        'days',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
