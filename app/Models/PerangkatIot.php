<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PerangkatIot extends Model
{
    use HasApiTokens;

    protected $table = 'perangkat_iots';

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
