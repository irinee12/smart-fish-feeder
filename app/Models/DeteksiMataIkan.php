<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeteksiMataIkan extends Model
{
    protected $table = 'deteksi_mata_ikan';

    protected $fillable = [
        'image_path',
        'hasil_klasifikasi',
        'persentase_segar',
        'persentase_tidak_segar',
        'confidence',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'persentase_segar' => 'decimal:2',
            'persentase_tidak_segar' => 'decimal:2',
            'confidence' => 'decimal:2',
        ];
    }
}