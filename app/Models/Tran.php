<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tran extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'hits',
        'results',
    ];
}
