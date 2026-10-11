<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('jadwal_pakans', function (Blueprint $table) {
        $table->id();
        $table->time('waktu'); // Untuk input Waktu
        $table->string('sesi'); // Untuk input Sesi (Pagi/Siang/Sore)
        $table->integer('porsi'); // Untuk input Porsi (gram)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pakans');
    }
};
