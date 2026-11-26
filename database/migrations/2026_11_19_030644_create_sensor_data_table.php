<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorDataTable extends Migration
{
    public function up(): void
    {
        // Tabel sudah ada (dibuat manual), lewati agar migration berikutnya bisa jalan
        if (Schema::hasTable('sensor_data')) {
            return;
        }

        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->decimal('distance_cm', 8, 2);
            $table->decimal('fuzzy_score', 8, 2);
            $table->string('stock_status');
            $table->boolean('empty_detected')->default(false);
            $table->timestamp('waktu')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
}