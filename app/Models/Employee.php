<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'name',
        'pin'
    ];

    public function device() {
        return $this->belongsTo(Devices::class, 'client_id', 'cloud_id');
    }

}
