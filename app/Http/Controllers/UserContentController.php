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
        // Ambil data untuk ditampilkan di homepage
        $categories = Category::where('is_active', true)->take(3)->get();
        $featuredVideos = Video::where('is_featured', true)->latest()->take(3)->get();
        $featuredModules = LearningModule::where('is_featured', true)->latest()->take(3)->get();

        return view('welcome', compact('categories', 'featuredVideos', 'featuredModules'));
    }

    /**
     * Halaman List Kategori (Pusat Belajar)
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount('topics') // Hitung jumlah topik
            ->get();

        return view('user.index', compact('categories'));
    }

    /**
     * Halaman Detail Topik (Isi Materi)
     */
    public function topic(Category $category, Topic $topic = null)
    {
        // Jika user hanya akses /belajar/pra-bencana (tanpa topik), 
        // kita arahkan ke topik pertama di kategori itu atau tampilkan list topik.
        
        // Load topik-topik dalam kategori ini
        $category->load(['topics' => function($q) {
            $q->withCount(['videos', 'learningModules']);
        }]);

        // Jika topik tidak dipilih di URL, ambil topik pertama
        if (!$topic) {
            $topic = $category->topics->first();
        }

        // Jika masih tidak ada topik (kategori kosong), handle error/empty state
        if (!$topic) {
            return redirect()->route('content.index')->with('error', 'Belum ada materi di kategori ini.');
        }

        // Ambil Video & Modul milik Topik yang dipilih
        $videos = $topic->videos()->latest()->get();
        $modules = $topic->learningModules()->latest()->get();

        return view('user.topic', compact('category', 'topic', 'videos', 'modules'));
    }

    /**
     * Halaman Nonton Video
     */
    public function video(Video $video)
    {
        // Load relasi untuk breadcrumb navigasi (Video -> Topik -> Kategori)
        $video->load('topic.category');
        
        // Rekomendasi video lain di topik yang sama
        $relatedVideos = Video::where('topic_id', $video->topic_id)
            ->where('id', '!=', $video->id)
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