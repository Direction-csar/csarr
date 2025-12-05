<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_requests', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->string('type'); // aide, audience, partenariat
            $table->string('status')->default('pending'); // pending, approved, rejected, completed
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('region');
            $table->text('description');
            $table->text('admin_comment')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('request_date');
            $table->date('processed_date')->nullable();
            $table->boolean('sms_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_requests');
    }
}; 