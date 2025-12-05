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
        // First, let's check if the table exists and what it contains
        if (Schema::hasTable('newsletters')) {
            // If the table exists but doesn't have the expected columns, we need to restructure it
            if (!Schema::hasColumn('newsletters', 'open_rate')) {
                // Drop the existing table and recreate it with the proper structure
                Schema::dropIfExists('newsletters');
            }
        }
        
        // Create the newsletters table with the proper structure for newsletter campaigns
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subject');
            $table->text('content');
            $table->string('template')->default('default');
            $table->string('status')->default('pending'); // pending, scheduled, sending, sent, failed
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->integer('recipients_count')->default(0);
            $table->integer('delivered_count')->default(0);
            $table->integer('opened_count')->default(0);
            $table->integer('clicked_count')->default(0);
            $table->integer('bounced_count')->default(0);
            $table->integer('unsubscribed_count')->default(0);
            $table->decimal('open_rate', 5, 2)->default(0);
            $table->decimal('click_rate', 5, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('sent_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
