<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // about, institution, minister_speech, dg_speech
            $table->string('key_name'); // title, description, content, numbers
            $table->text('value');
            $table->string('type')->default('text'); // text, number, html
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_contents');
    }
}; 