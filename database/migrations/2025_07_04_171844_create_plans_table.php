<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_plan_id');
            $table->integer('external_id');
            $table->integer('external_base_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
            $table->index(['external_id' . 'external_base_id']);
            $table->index('base_plan_id');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
