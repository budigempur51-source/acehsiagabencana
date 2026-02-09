<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'thumbnail',
    ];

    /**
     * PENTING: Agar Route Model Binding membaca SLUG, bukan ID.
     * Ini yang memperbaiki error 404 saat klik topik.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relasi: Topik milik satu Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Topik punya banyak Video.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Relasi: Topik punya banyak Modul Pembelajaran.
     */
    public function learningModules(): HasMany
    {
        return $this->hasMany(LearningModule::class);
    }
}