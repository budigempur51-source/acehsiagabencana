@extends('layouts.user')

@section('content')
<style>
    /* Animasi Blob Background */
    @keyframes moveBlob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .blob-cont {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        z-index: -1; overflow: hidden; pointer-events: none;
    }
    .blob {
        position: absolute; filter: blur(80px); opacity: 0.5;
        animation: moveBlob 10s infinite alternate;
    }
    .blob-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #d1fae5; }
    .blob-2 { bottom: -10%; right: -10%; width: 50vw; height: 50vw; background: #cffafe; animation-delay: 2s; }
    .blob-3 { top: 40%; left: 40%; width: 40vw; height: 40vw; background: #ccfbf1; animation-delay: 4s; transform: translate(-50%, -50%); }

    /* Glass Effect */
    .glass-panel {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.9);
        border-color: #34d399;
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.15);
    }
</style>

<div class="blob-cont">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

<div class="relative min-h-screen pb-12" style="padding-top: 120px;">
    
    <div class="glass-panel sticky top-24 z-20 mb-8 rounded-2xl mx-4 sm:mx-6 lg:mx-8">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('content.index') }}" class="text-slate-500 hover:text-emerald-600 transition flex items-center gap-2 font-bold text-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <span class="text-slate-300">|</span>
                <div class="flex items-center space-x-3">
                    @if($category->icon)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($category->icon) }}" class="h-8 w-8 object-contain drop-shadow-sm">
                    @endif
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">{{ $category->name }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1">
                <div class="glass-panel rounded-2xl overflow-hidden sticky top-40">
                    <div class="p-4 bg-emerald-50/50 border-b border-emerald-100">
                        <h3 class="font-bold text-emerald-800 text-sm uppercase tracking-wider">Topik Pembahasan</h3>
                    </div>
                    <nav class="flex flex-col p-2 space-y-1 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        @forelse($category->topics as $t)
                            <a href="{{ route('content.topic', ['category' => $category->slug, 'topic' => $t->slug]) }}" 
                               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200
                               {{ isset($topic) && $topic->id === $t->id 
                                   ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' 
                                   : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                                
                                <span class="truncate flex-1">{{ $t->name }}</span>
                                
                                @if(isset($topic) && $topic->id === $t->id)
                                    <i class="fas fa-check-circle text-white"></i>
                                @else
                                    <i class="fas fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                @endif
                            </a>
                        @empty
                            <div class="p-6 text-center text-sm text-slate-400">
                                <i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i>
                                Belum ada topik.
                            </div>
                        @endforelse
                    </nav>
                </div>
            </div>

            <div class="lg:col-span-3">
                @if(isset($topic) && $topic)
                    <div class="mb-8 glass-panel p-8 rounded-3xl">
                        <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-4 leading-tight">{{ $topic->name }}</h2>
                        @if($topic->description)
                            <div class="prose prose-emerald text-slate-600 max-w-none leading-relaxed">
                                {!! $topic->description !!}
                            </div>
                        @endif
                    </div>

                    @if($videos->count() > 0)
                        <div class="mb-12">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="h-8 w-1.5 bg-red-500 rounded-full shadow-[0_0_10px_rgba(239,68,68,0.5)]"></div>
                                <h3 class="text-2xl font-bold text-slate-800">Video Pembelajaran</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach($videos as $video)
                                    <a href="{{ route('content.video', $video->slug) }}" class="group block glass-card rounded-2xl overflow-hidden">
                                        <div class="relative aspect-video bg-slate-200 overflow-hidden">
                                            @if($video->thumbnail)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($video->thumbnail) }}" alt="{{ $video->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                                    <i class="fas fa-play-circle text-5xl opacity-50"></i>
                                                </div>
                                            @endif
                                            
                                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition duration-300 flex items-center justify-center backdrop-blur-[2px] group-hover:backdrop-blur-none">
                                                <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg transform scale-90 group-hover:scale-110 transition duration-300">
                                                    <i class="fas fa-play text-red-600 ml-1 text-xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-5">
                                            <h4 class="font-bold text-slate-800 line-clamp-2 group-hover:text-red-600 transition mb-2 text-lg">{{ $video->title }}</h4>
                                            <div class="flex items-center text-xs text-slate-500 font-medium">
                                                <i class="far fa-clock mr-1.5 text-red-400"></i> 
                                                {{ $video->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($modules->count() > 0)
                        <div class="mb-10">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="h-8 w-1.5 bg-blue-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                                <h3 class="text-2xl font-bold text-slate-800">Modul & Buku Saku</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                @foreach($modules as $module)
                                    <div class="glass-card rounded-2xl p-4 flex gap-4 group hover:bg-white/80">
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-20 bg-blue-50 rounded-lg flex items-center justify-center text-blue-400 shadow-inner overflow-hidden border border-blue-100 group-hover:border-blue-300 transition">
                                                @if($module->cover_image)
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($module->cover_image) }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-book-open text-3xl"></i>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                                            <h4 class="text-base font-bold text-slate-800 truncate mb-1 group-hover:text-blue-600 transition">{{ $module->title }}</h4>
                                            <p class="text-sm text-slate-500 line-clamp-2 mb-3 leading-relaxed">{{ strip_tags($module->description) }}</p>
                                            
                                            <a href="{{ route('content.module', ['module' => $module->slug]) }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 uppercase tracking-wider group-hover:translate-x-1 transition-transform">
                                                Baca Sekarang <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($videos->isEmpty() && $modules->isEmpty())
                        <div class="text-center py-20 glass-panel rounded-3xl border-dashed border-2 border-slate-300">
                            <div class="mx-auto h-24 w-24 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-wind text-4xl text-slate-400"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-700">Materi Belum Tersedia</h3>
                            <p class="mt-2 text-slate-500">Konten untuk topik ini sedang disiapkan.</p>
                        </div>
                    @endif

                @else
                    <div class="text-center py-32 glass-panel rounded-3xl">
                        <div class="inline-block p-8 bg-emerald-50 rounded-full mb-6 animate-pulse">
                            <i class="fas fa-search-location text-5xl text-emerald-400"></i>
                        </div>
                        <h2 class="text-3xl font-black text-slate-800 mb-2">Pilih Topik di Sebelah Kiri</h2>
                        <p class="text-slate-500 max-w-md mx-auto mb-8">Silakan klik salah satu topik di sidebar kiri untuk mulai belajar materi yang tersedia.</p>
                        <a href="{{ route('content.index') }}" class="px-8 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-700 transition shadow-lg">
                            Kembali ke Menu Utama
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection