<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'device_id',
        'cloud_id',
        'device_name',
        'employee_name',
        'pin',
       'scan_at',
       'scan_verify',
       'scan_status',
    ];

    public function device() {
        return $this->belongsTo(Devices::class, 'cloud_id', 'cloud_id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function scopeStartsBetween(Builder $query, $dateStart, $dateEnd): Builder
    {
        return $query->whereBetween('scan_at', [$dateStart, $dateEnd]);
    }
}
