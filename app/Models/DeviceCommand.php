<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceCommand extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'command', 'status'];

    public function device() { return $this->belongsTo(Device::class); }
}
