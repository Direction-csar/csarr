<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // denrées, carburant, matériel
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->string('unit'); // kg, litres, pièces
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_types');
    }
}; 