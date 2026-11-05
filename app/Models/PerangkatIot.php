<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatIot extends Model
{
    protected $fillable = [
        'nama_perangkat',
        'device_code',
        'status',
        'last_seen',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];
}