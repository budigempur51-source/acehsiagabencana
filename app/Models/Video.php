<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'title',
        'slug',
        'youtube_id',
        'description',
        'duration',
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
     * Relasi: Video milik satu Topik.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}