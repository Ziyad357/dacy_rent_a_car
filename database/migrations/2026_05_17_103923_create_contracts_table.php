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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('contract_number')->unique();
            $table->datetime('signed_at');
            $table->enum('fuel_level_out', ['full', 'three_quarters', 'half', 'quarter', 'empty']);
            $table->enum('fuel_level_in', ['full', 'three_quarters', 'half', 'quarter', 'empty'])->nullable();
            $table->integer('mileage_out');
            $table->integer('mileage_in')->nullable();
            $table->text('condition_out');
            $table->text('condition_in')->nullable();
            $table->datetime('returned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
