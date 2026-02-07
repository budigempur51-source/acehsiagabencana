<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_modules', function (Blueprint $table) {
            $table->id();
            // Relasi ke Topic
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            
            // File Management
            $table->string('file_path'); // Lokasi file PDF
            $table->string('cover_image')->nullable(); // Cover buku
            $table->string('file_type')->default('pdf'); 
            $table->integer('file_size')->default(0); // Dalam KB
            
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_modules');
    }
};