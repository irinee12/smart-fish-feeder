
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_stoks', function (Blueprint $table) {
            $table->foreignId('perangkat_iot_id')
                ->nullable()
                ->after('id')
                ->constrained('perangkat_iots')
                ->nullOnDelete();
        });

        Schema::table('riwayat_pakans', function (Blueprint $table) {
            $table->foreignId('perangkat_iot_id')
                ->nullable()
                ->after('id')
                ->constrained('perangkat_iots')
                ->nullOnDelete();

            $table->string('transaction_id')
                ->nullable()
                ->unique()
                ->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_pakans', function (Blueprint $table) {
            $table->dropForeign(['perangkat_iot_id']);
            $table->dropUnique(['transaction_id']);
            $table->dropColumn([
                'perangkat_iot_id',
                'transaction_id'
            ]);
        });

        Schema::table('monitoring_stoks', function (Blueprint $table) {
            $table->dropForeign(['perangkat_iot_id']);
            $table->dropColumn('perangkat_iot_id');
        });
    }
};
