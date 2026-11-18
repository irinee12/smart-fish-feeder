<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    // Arahkan ke nama tabel yang benar
    protected $table = 'sensor_data';
    
    // Matikan timestamps bawaan Laravel
    public $timestamps = false; 

    // Kolom yang diizinkan untuk diakses
    protected $fillable = ['jarak', 'waktu'];
}