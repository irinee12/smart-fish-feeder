<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_stoks', function (Blueprint $table) {
            $table->decimal('berat_g', 10, 2)->nullable()->after('persentase_stok');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_stoks', function (Blueprint $table) {
            $table->dropColumn('berat_g');
        });
    }
};
