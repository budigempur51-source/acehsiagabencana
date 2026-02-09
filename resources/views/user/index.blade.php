@extends('layouts.user')

@section('content')
<style>
    /* --- 1. ANIMASI BACKGROUND (Blobs) --- */
    @keyframes moveBlob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .blob-cont {
        position: fixed; /* Supaya background diam saat scroll */
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: -1; /* Pastikan di paling belakang */
        overflow: hidden;
        pointer-events: none;
    }
    .blob {
        position: absolute;
        filter: blur(80px);
        opacity: 0.5;
        animation: moveBlob 10s infinite alternate;
    }
    .blob-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #d1fae5; }
    .blob-2 { bottom: -10%; right: -10%; width: 50vw; height: 50vw; background: #cffafe; animation-delay: 2s; }
    .blob-3 { top: 40%; left: 40%; width: 40vw; height: 40vw; background: #ccfbf1; animation-delay: 4s; transform: translate(-50%, -50%); }

    /* --- 2. CARD STYLING --- */
    .glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%; /* Agar tinggi kartu rata */
        border-radius: 1.5rem;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.15);
        border-color: #34d399;
    }

    /* --- 3. GAMBAR & CONTAINER --- */
    .img-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        background-color: #f1f5f9;
        margin-bottom: 1.5rem;
        aspect-ratio: 16/9; /* Memaksa rasio gambar 16:9 agar rapi */
    }
    .img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s;
    }
    .glass-card:hover .img-wrap img {
        transform: scale(1.1);
    }
</style>

<div class="blob-cont">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

<div class="relative min-h-screen" style="padding-top: 140px; padding-bottom: 80px;">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block py-1 px-3 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold tracking-widest uppercase mb-4">
                Platform Edukasi
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-800 tracking-tight mb-4">
                Pusat Pengetahuan <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600">
                    Siaga Bencana
                </span>
            </h1>
            <p class="text-slate-500 text-lg">
                Pilih topik di bawah untuk mulai belajar.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($categories as $category)
                @php
                    // Logika Aman Pemilihan Gambar (Pake Full Namespace biar gak error)
                    $name = strtolower($category->name);
                    $defaultImg = 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=600&auto=format&fit=crop';

                    if (\Illuminate\Support\Str::contains($name, ['sehat', 'medis', 'dokter', 'obat', 'kesehatan'])) {
                        $bgImage = 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=600&auto=format&fit=crop';
                    } elseif (\Illuminate\Support\Str::contains($name, ['umkm', 'bisnis', 'ekonomi', 'pasar', 'uang'])) {
                        $bgImage = 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=600&auto=format&fit=crop';
                    } elseif (\Illuminate\Support\Str::contains($name, ['anak', 'sekolah', 'belajar', 'pendidikan'])) {
                        $bgImage = 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=600&auto=format&fit=crop';
                    } elseif (\Illuminate\Support\Str::contains($name, ['banjir', 'air'])) {
                        $bgImage = 'https://images.unsplash.com/photo-1548232979-6c557ee14752?q=80&w=600&auto=format&fit=crop';
                    } elseif (\Illuminate\Support\Str::contains($name, ['gempa', 'runtuh'])) {
                        $bgImage = 'https://images.unsplash.com/photo-1505232070786-2f34cbdf90f3?q=80&w=600&auto=format&fit=crop';
                    } else {
                        $bgImage = $defaultImg;
                    }

                    // Pakai gambar upload admin jika ada
                    $finalImage = $category->icon ? \Illuminate\Support\Facades\Storage::url($category->icon) : $bgImage;
                @endphp

                <div class="glass-card p-5 group">
                    
                    <div class="img-wrap shadow-sm">
                        <img src="{{ $finalImage }}" alt="{{ $category->name }}" loading="lazy">
                        
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-slate-800 text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                            {{ $category->topics_count }} MATERI
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors">
                        {{ $category->name }}
                    </h3>

                    <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-2 flex-grow">
                        {{ $category->description ?? 'Pelajari modul lengkap tentang topik ini untuk keselamatan Anda.' }}
                    </p>

                    <a href="{{ route('content.topic', ['category' => $category->slug]) }}" 
                       class="mt-auto w-full py-3 bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold rounded-xl text-center hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all duration-300 flex items-center justify-center gap-2">
                        Akses Materi <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20">
                    <div class="inline-block p-6 rounded-full bg-slate-100 mb-4">
                        <i class="fas fa-folder-open text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Materi Belum Tersedia</h3>
                    <p class="text-slate-400">Silakan hubungi admin.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection