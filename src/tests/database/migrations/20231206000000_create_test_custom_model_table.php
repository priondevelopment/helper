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
        Schema::create('test_custom_models', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('custom_id')->nullable();
            $table->string('custom_uuid')->nullable();
            $table->string('custom_name')->nullable();
            $table->string('custom_slug')->nullable();
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
