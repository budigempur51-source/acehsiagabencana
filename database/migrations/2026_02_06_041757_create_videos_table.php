<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            // Relasi ke Topic
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('youtube_id'); // ID Video Youtube (misal: dQw4w9WgXcQ)
            $table->text('description')->nullable();
            $table->integer('duration')->default(0); // Durasi dalam menit (opsional)
            $table->boolean('is_featured')->default(false); // Untuk highlight di home
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};