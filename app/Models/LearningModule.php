<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'title',
        'slug',
        'file_path',
        'cover_image',
        'file_type',
        'file_size',
        'description',
        'is_featured',
    ];

    /**
     * PENTING: Agar Route Model Binding membaca SLUG, bukan ID.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relasi: Modul milik satu Topik.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}