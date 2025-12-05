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
        // Drop the old newsletters table which was incorrectly structured
        // (it was created for newsletter subscriptions, not newsletters themselves)
        Schema::dropIfExists('newsletters');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the old structure if needed (for rollback purposes)
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('subscribed_at');
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });
    }
};
