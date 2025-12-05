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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->integer('capacity')->default(0)->after('is_active');
            $table->integer('current_stock')->default(0)->after('capacity');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('current_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'current_stock', 'status']);
        });
    }
};
