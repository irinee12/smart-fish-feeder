<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('riwayat_deteksi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable();
    $table->string('hasil', 20);              // Segar / Tidak Segar
    $table->decimal('akurasi', 5, 2);         // 0 - 100
    $table->string('sumber', 20)->default('upload'); // upload / kamera
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_deteksis');
    }
};
