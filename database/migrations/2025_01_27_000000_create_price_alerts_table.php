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
        Schema::create('price_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->decimal('current_price', 10, 2);
            $table->decimal('previous_price', 10, 2);
            $table->decimal('increase_percentage', 8, 2);
            $table->enum('alert_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['active', 'resolved', 'dismissed'])->default('active');
            $table->text('description')->nullable();
            $table->string('region')->nullable();
            $table->string('market_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_alerts');
    }
}; 
 
 
 
 
 
 