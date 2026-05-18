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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->smallInteger('year');
            $table->string('plate_number')->unique();
            $table->string('color');
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid']);
            $table->enum('transmission', ['manual', 'automatic']);
            $table->tinyInteger('seats');
            $table->integer('mileage');
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->enum('status', ['available', 'rented', 'maintenance', 'reserved'])->default('available');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
