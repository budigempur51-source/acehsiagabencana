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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Topic
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            
            // PERBAIKAN: Ganti 'youtube_id' menjadi 'url' agar sesuai dengan Seeder & Form Filament
            $table->string('url'); 
            
            // TAMBAHAN: Kolom thumbnail (nullable karena bisa kosong)
            $table->string('thumbnail')->nullable();
            
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false); // Untuk highlight di home
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};