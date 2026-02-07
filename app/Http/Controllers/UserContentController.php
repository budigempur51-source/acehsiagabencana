<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\Video;
use App\Models\LearningModule;
use Illuminate\Http\Request;

class UserContentController extends Controller
{
    /**
     * Halaman Utama (Landing Page)
     */
    public function home()
    {
        $categories = Category::where('is_active', true)->take(4)->get();
        
        // Ambil video unggulan
        $featuredVideos = Video::where('is_featured', true)
            ->with(['topic.category']) // Eager load untuk performa
            ->latest()
            ->take(3)
            ->get();

        // Ambil modul unggulan
        $featuredModules = LearningModule::where('is_featured', true)
            ->with(['topic.category'])
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('categories', 'featuredVideos', 'featuredModules'));
    }

    /**
     * Halaman List Kategori (Pusat Belajar)
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount('topics')
            ->get();

        return view('user.index', compact('categories'));
    }

    /**
     * Halaman Detail Topik (Core Page)
     * Menangani logika tampilan materi dan sidebar topik.
     */
    public function topic(Category $category, Topic $topic = null)
    {
        // 1. Load semua topik dalam kategori ini untuk Sidebar Navigasi
        $category->load(['topics' => function($q) {
            $q->orderBy('name', 'asc'); // Urutkan topik A-Z atau sesuai kebutuhan
        }]);

        // 2. Logika Penentuan Topik Aktif
        // Jika di URL tidak ada topik, ambil topik pertama dari kategori tersebut
        if (!$topic) {
            $topic = $category->topics->first();
        }

        // 3. Siapkan Data Materi (Video & Modul)
        $videos = collect();
        $modules = collect();

        // Hanya query materi jika topik DITEMUKAN (tidak null)
        if ($topic) {
            $videos = $topic->videos()->latest()->get();
            $modules = $topic->learningModules()->latest()->get();
        }

        // Kita tidak melempar redirect jika kosong, tapi biarkan View menangani Empty State.
        return view('user.topic', compact('category', 'topic', 'videos', 'modules'));
    }

    /**
     * Halaman Nonton Video
     */
    public function video(Video $video)
    {
        $video->load('topic.category');
        
        // Rekomendasi: Video lain dalam topik yang sama
        $relatedVideos = Video::where('topic_id', $video->topic_id)
            ->where('id', '!=', $video->id)
            ->latest()
            ->take(4)
            ->get();

        return view('user.video', compact('video', 'relatedVideos'));
    }

    /**
     * Halaman Baca Modul
     */
    public function module(LearningModule $module)
    {
        $module->load('topic.category');
        return view('user.module', compact('module'));
    }
}