<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatDeteksi extends Model
{
    protected $table = 'riwayat_deteksi';
    protected $fillable = ['user_id', 'hasil', 'akurasi', 'sumber'];
}