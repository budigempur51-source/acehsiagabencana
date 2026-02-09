<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
    ];

    /**
     * PENTING: Agar Route Model Binding membaca SLUG, bukan ID.
     * Ini yang memperbaiki error 404 saat membuka Kategori.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relasi: Satu Kategori punya banyak Topik.
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }
}