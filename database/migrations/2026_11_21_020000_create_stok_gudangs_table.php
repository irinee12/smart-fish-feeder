<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Setiap penambahan stok gudang dicatat sebagai satu transaksi.
        Schema::create('stok_gudangs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->decimal('jumlah_kg', 10, 2);
            $table->timestamps();

            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_gudangs');
    }
};
