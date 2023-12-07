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
        Schema::create('test_custom_mismatch', function (Blueprint $table) {
            $table->id('mismatch_id');
            $table->string('mismatch_uuid')->nullable();
            $table->string('mismatch_name')->nullable();
            $table->string('mismatch_slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_models');
    }
};
