<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringStok extends Model
{
    protected $fillable = [
        'jarak_sensor',
        'stok_gram',
        'persentase_stok',
        'waktu_pembacaan',
    ];

    protected $casts = [
        'jarak_sensor' => 'decimal:2',
        'stok_gram' => 'decimal:2',
        'persentase_stok' => 'decimal:2',
        'waktu_pembacaan' => 'datetime',
    ];
}