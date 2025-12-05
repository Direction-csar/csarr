<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technical_partners', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('status');
            $table->integer('position')->nullable()->after('is_featured');
            $table->string('slug')->nullable()->unique()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('technical_partners', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'position', 'slug']);
        });
    }
};




