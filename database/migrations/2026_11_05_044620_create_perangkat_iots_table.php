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
    Schema::create('perangkat_iots', function (Blueprint $table) {
        $table->id();

        $table->string('nama_perangkat');
        $table->string('device_code')->unique();

        $table->string('status')->default('offline');

        $table->timestamp('last_seen')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('perangkat_iots');
}
};
