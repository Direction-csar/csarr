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
        Schema::table('demandes', function (Blueprint $table) {
            // Champs pour le suivi SMS
            $table->boolean('sms_sent')->default(false)->after('tracking_code');
            $table->string('sms_message_id')->nullable()->after('sms_sent');
            $table->timestamp('sms_sent_at')->nullable()->after('sms_message_id');
            $table->text('sms_error')->nullable()->after('sms_sent_at');
            $table->integer('sms_retry_count')->default(0)->after('sms_error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn([
                'sms_sent',
                'sms_message_id', 
                'sms_sent_at',
                'sms_error',
                'sms_retry_count'
            ]);
        });
    }
};
