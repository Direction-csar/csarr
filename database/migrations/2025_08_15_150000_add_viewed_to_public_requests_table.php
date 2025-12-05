<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('public_requests', 'is_viewed')) {
                $table->boolean('is_viewed')->default(false)->after('sms_sent');
            }
            if (!Schema::hasColumn('public_requests', 'viewed_at')) {
                $table->timestamp('viewed_at')->nullable()->after('is_viewed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('public_requests', function (Blueprint $table) {
            if (Schema::hasColumn('public_requests', 'is_viewed')) {
                $table->dropColumn('is_viewed');
            }
            if (Schema::hasColumn('public_requests', 'viewed_at')) {
                $table->dropColumn('viewed_at');
            }
        });
    }
};



