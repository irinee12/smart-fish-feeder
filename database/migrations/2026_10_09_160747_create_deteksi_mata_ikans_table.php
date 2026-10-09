```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deteksi_mata_ikan', function (Blueprint $table) {
            $table->id();

            // Lokasi file gambar di storage
            $table->string('image_path');

            // Hasil klasifikasi dari model AI
            $table->string('hasil_klasifikasi')->nullable();

            // Persentase masing-masing kelas
            $table->decimal('persentase_segar', 5, 2)->nullable();
            $table->decimal('persentase_tidak_segar', 5, 2)->nullable();

            // Confidence model AI
            $table->decimal('confidence', 5, 2)->nullable();

            // pending, success, atau failed
            $table->string('status')->default('pending');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deteksi_mata_ikan');
    }
};

