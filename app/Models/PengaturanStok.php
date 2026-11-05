<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanStok extends Model
{
    protected $fillable = [
        'kapasitas_maksimum',
        'batas_minimum',
        'satuan',
        'is_active',
    ];

    protected $casts = [
        'kapasitas_maksimum' => 'decimal:2',
        'batas_minimum' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}