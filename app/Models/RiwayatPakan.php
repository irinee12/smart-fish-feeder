<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPakan extends Model
{
    protected $fillable = [
        'jadwal_pakan_id',
        'tanggal',
        'waktu',
        'porsi_target',
        'porsi_aktual',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'porsi_target' => 'decimal:2',
        'porsi_aktual' => 'decimal:2',
    ];
}