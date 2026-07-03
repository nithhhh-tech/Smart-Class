<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DeviceCommand extends Model
{
    protected $fillable = ['device_id', 'command', 'status'];

    public function device() { return $this->belongsTo(Device::class); }
}
