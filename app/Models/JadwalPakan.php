<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JadwalPakan extends Model
{
    protected $fillable = ['waktu', 'sesi', 'porsi'];
}