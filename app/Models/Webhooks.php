<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhooks extends Model
{
    use HasFactory;
    protected $fillable = [
        'cloud_id',
        'type_hit',
        'trans_id',
        'data',
    ];
}
