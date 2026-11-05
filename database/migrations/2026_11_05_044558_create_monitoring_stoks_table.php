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
    Schema::create('monitoring_stoks', function (Blueprint $table) {
        $table->id();

        $table->decimal('jarak_sensor', 10, 2)->nullable();
        $table->decimal('stok_gram', 10, 2);
        $table->decimal('persentase_stok', 5, 2)->nullable();

        $table->timestamp('waktu_pembacaan');

        $table->timestamps();

        $table->index('waktu_pembacaan');
    });
}

public function down(): void
{
    Schema::dropIfExists('monitoring_stoks');
}
};
