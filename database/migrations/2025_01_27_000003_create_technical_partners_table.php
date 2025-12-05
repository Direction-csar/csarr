<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('organization');
            $table->enum('type', ['ong', 'agency', 'institution', 'private', 'government'])->default('institution');
            $table->string('role')->nullable();
            $table->json('intervention_zone')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->date('partnership_start_date')->nullable();
            $table->date('partnership_end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->json('activities')->nullable();
            $table->text('notes')->nullable();
            $table->string('logo')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_partners');
    }
}; 
 
 
 
 
 
 