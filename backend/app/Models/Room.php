<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'temp_threshold', 'motion_timeout'];

    public function devices()    { return $this->hasMany(Device::class); }
    public function sensorLogs() { return $this->hasMany(SensorLog::class); }
}
