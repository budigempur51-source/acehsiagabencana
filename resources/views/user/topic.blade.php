@extends('layouts.user')

@section('content')
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <nav class="flex text-sm font-medium text-gray-500 mb-4" aria-label="Breadcrumb">
                <a href="{{ route('content.index') }}" class="hover:text-gray-900">Pusat Belajar</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ $category->name }}</span>
            </nav>
            
            <div class="md:flex md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $topic->name }}</h1>
                    <p class="mt-2 text-lg text-gray-600">{{ $topic->description }}</p>
                </div>
                <div class="mt-4 md:mt-0 relative">
                    <button id="topicMenuButton" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md shadow-sm text-sm font-medium flex items-center">
                        <span>Ganti Topik</span>
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div id="topicDropdown" class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                        <div class="py-1" role="menu">
                            @foreach($category->topics as $t)
                                <a href="{{ route('content.topic', ['category' => $category->slug, 'topic' => $t->slug]) }}" 
                                   class="block px-4 py-2 text-sm {{ $t->id == $topic->id ? 'bg-red-50 text-red-700 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                                    {{ $t->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="mb-16">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-red-100 rounded-lg text-red-600 mr-3">
                    <i class="fas fa-video text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Video Edukasi</h2>
            </div>

            @if($videos->count() > 0)
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($videos as $video)
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                            <div class="relative aspect-video">
                                <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                <a href="{{ route('content.video', $video->slug) }}" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center text-white shadow-lg transform group-hover:scale-110 transition-transform">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </a>
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                    {{ $video->duration }} min
                                </span>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 line-clamp-2 mb-2 group-hover:text-red-600 transition-colors">
                                    <a href="{{ route('content.video', $video->slug) }}">{{ $video->title }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $video->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-8 text-center text-gray-500">
                    Belum ada video untuk topik ini.
                </div>
            @endif
        </div>

        <div>
            <div class="flex items-center mb-6">
                <div class="p-2 bg-blue-100 rounded-lg text-blue-600 mr-3">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Buku Saku & Modul</h2>
            </div>

            @if($modules->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($modules as $module)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-start hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0 w-12 h-16 bg-red-50 border border-red-100 rounded flex items-center justify-center text-red-500 mr-4">
                                <i class="fas fa-file-pdf text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $module->title }}</h3>
                                <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $module->description }}</p>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('content.module', $module->slug) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        Baca Sekarang
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-xs text-gray-400">PDF File</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-8 text-center text-gray-500">
                    Belum ada modul untuk topik ini.
                </div>
            @endif
        </div>
    </div>

    <script>
        const btn = document.getElementById('topicMenuButton');
        const dropdown = document.getElementById('topicDropdown');
        
        btn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });
        
        // Klik di luar untuk menutup
        window.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
@endsection