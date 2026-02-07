@extends('layouts.user')

@section('content')
    <div class="bg-gray-50 min-h-screen pb-12">
        
        <div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('content.index') }}" class="text-gray-500 hover:text-red-600 transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center space-x-2">
                        @if($category->icon)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($category->icon) }}" class="h-6 w-6 object-contain">
                        @endif
                        <h1 class="text-lg font-bold text-gray-900">{{ $category->name }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                        <div class="p-4 bg-gray-50 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-700">Topik Pembahasan</h3>
                        </div>
                        <nav class="flex flex-col p-2 space-y-1 max-h-[70vh] overflow-y-auto">
                            @forelse($category->topics as $t)
                                <a href="{{ route('content.topic', ['category' => $category->slug, 'topic' => $t->slug]) }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   {{ isset($topic) && $topic->id === $t->id ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    
                                    <span class="truncate flex-1">{{ $t->name }}</span>
                                    
                                    @if(isset($topic) && $topic->id === $t->id)
                                        <i class="fas fa-chevron-right text-xs text-red-400"></i>
                                    @endif
                                </a>
                            @empty
                                <div class="p-4 text-center text-sm text-gray-500">
                                    Belum ada topik.
                                </div>
                            @endforelse
                        </nav>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    @if($topic)
                        <div class="mb-8">
                            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $topic->name }}</h2>
                            @if($topic->description)
                                <div class="prose text-gray-600 max-w-none">
                                    {!! $topic->description !!}
                                </div>
                            @endif
                        </div>

                        @if($videos->count() > 0)
                            <div class="mb-10">
                                <div class="flex items-center space-x-2 mb-4">
                                    <div class="h-8 w-1 bg-red-600 rounded-full"></div>
                                    <h3 class="text-xl font-bold text-gray-800">Video Pembelajaran</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    @foreach($videos as $video)
                                        <a href="{{ route('content.video', $video->slug) }}" class="group block bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">
                                            <div class="relative aspect-video bg-gray-200">
                                                @if($video->thumbnail)
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($video->thumbnail) }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-play-circle text-4xl"></i>
                                                    </div>
                                                @endif
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition duration-300 flex items-center justify-center">
                                                    <div class="bg-white/90 rounded-full p-3 opacity-0 group-hover:opacity-100 transition transform scale-75 group-hover:scale-100">
                                                        <i class="fas fa-play text-red-600 ml-1"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4">
                                                <h4 class="font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 transition">{{ $video->title }}</h4>
                                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                                    <i class="far fa-clock mr-1"></i> {{ $video->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($modules->count() > 0)
                            <div class="mb-10">
                                <div class="flex items-center space-x-2 mb-4">
                                    <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                                    <h3 class="text-xl font-bold text-gray-800">Modul & Buku Saku</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($modules as $module)
                                        <div class="flex bg-white rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition shadow-sm hover:shadow-md">
                                            <div class="flex-shrink-0 mr-4">
                                                <div class="w-12 h-16 bg-blue-50 rounded flex items-center justify-center text-blue-500">
                                                    @if($module->cover_image)
                                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($module->cover_image) }}" class="w-full h-full object-cover rounded">
                                                    @else
                                                        <i class="fas fa-book text-2xl"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-base font-semibold text-gray-900 truncate mb-1">{{ $module->title }}</h4>
                                                <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ strip_tags($module->description) }}</p>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('content.module', $module->slug) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800 uppercase tracking-wide">
                                                        Baca Sekarang &rarr;
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($videos->isEmpty() && $modules->isEmpty())
                            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
                                <div class="mx-auto h-24 w-24 text-gray-300 mb-4">
                                    <i class="fas fa-box-open text-6xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Materi Belum Tersedia</h3>
                                <p class="mt-1 text-gray-500">Konten untuk topik ini sedang disiapkan oleh tim kami.</p>
                                <div class="mt-6">
                                    <a href="{{ route('content.index') }}" class="text-red-600 hover:text-red-700 font-medium">Cari topik lain</a>
                                </div>
                            </div>
                        @endif

                    @else
                        <div class="text-center py-20">
                            <img src="https://illustrations.popsy.co/amber/surr-searching.svg" alt="Empty" class="h-48 mx-auto mb-6 opacity-75">
                            <h2 class="text-2xl font-bold text-gray-800">Kategori Ini Masih Kosong</h2>
                            <p class="text-gray-600 mt-2 max-w-md mx-auto">Kami sedang menyusun kurikulum terbaik untuk kategori bencana ini. Silakan cek kembali nanti!</p>
                            <div class="mt-8">
                                <a href="{{ route('content.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 transition">
                                    Jelajahi Kategori Lain
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection