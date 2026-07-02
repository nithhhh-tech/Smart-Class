<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['room_id', 'name', 'type', 'status'];
    protected $casts    = ['status' => 'boolean'];

    public function room()     { return $this->belongsTo(Room::class); }
    public function commands() { return $this->hasMany(DeviceCommand::class); }
}
