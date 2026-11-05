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
    Schema::create('riwayat_pakans', function (Blueprint $table) {
        $table->id();

        $table->foreignId('jadwal_pakan_id')
            ->nullable()
            ->constrained('jadwal_pakans')
            ->nullOnDelete();

        $table->date('tanggal');
        $table->time('waktu');

        $table->decimal('porsi_target', 10, 2);
        $table->decimal('porsi_aktual', 10, 2)->nullable();

        $table->string('status')->default('menunggu');

        $table->timestamps();

        $table->index('tanggal');
    });
}

public function down(): void
{
    Schema::dropIfExists('riwayat_pakans');
}
};
