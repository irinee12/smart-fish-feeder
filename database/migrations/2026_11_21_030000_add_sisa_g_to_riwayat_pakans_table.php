<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_pakans', function (Blueprint $table) {
            // Sisa pakan yang tertahan di wadah load cell setelah pakan keluar (gram).
            $table->decimal('sisa_g', 10, 2)->nullable()->after('porsi_aktual');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_pakans', function (Blueprint $table) {
            $table->dropColumn('sisa_g');
        });
    }
};
