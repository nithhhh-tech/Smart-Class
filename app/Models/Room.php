<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name', 'location'];

    public function devices()    { return $this->hasMany(Device::class); }
    public function sensorLogs() { return $this->hasMany(SensorLog::class); }
}
