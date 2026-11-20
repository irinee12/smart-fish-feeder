
Schema::create('sensor_data', function (Blueprint $table) {
    $table->id();
    $table->decimal('distance_cm', 8, 2);
    $table->decimal('fuzzy_score', 8, 2);
    $table->string('stock_status');
    $table->boolean('empty_detected')->default(false);
    $table->timestamps();
});
