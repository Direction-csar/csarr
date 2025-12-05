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
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->enum('alert_type', ['low_stock', 'expired', 'expiring_soon']);
            $table->enum('severity', ['low', 'medium', 'high', 'critical']);
            $table->text('message');
            $table->decimal('current_quantity', 10, 2);
            $table->decimal('threshold_quantity', 10, 2);
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_resolved', 'severity']);
            $table->index(['alert_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};
