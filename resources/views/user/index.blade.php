@extends('layouts.user')

@section('content')
    <div class="bg-red-600 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-3xl font-extrabold sm:text-4xl">Pusat Belajar Kebencanaan</h1>
            <p class="mt-4 text-xl text-red-100 max-w-3xl mx-auto">
                Temukan panduan lengkap untuk setiap fase bencana. Pilih kategori di bawah ini untuk memulai.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($categories as $category)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
                    <div class="p-6 flex-1">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600 mb-4">
                            <i class="{{ $category->icon ?? 'fas fa-book' }} text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $category->name }}</h2>
                        <p class="text-gray-500 mb-6">{{ $category->description }}</p>

                        @if($category->topics_count > 0)
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Topik Tersedia:</h3>
                            <ul class="space-y-2">
                                @foreach($category->topics as $topic)
                                    <li>
                                        <a href="{{ route('content.topic', ['category' => $category->slug, 'topic' => $topic->slug]) }}" class="flex items-center text-gray-600 hover:text-red-600 group">
                                            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full mr-2 group-hover:bg-red-500 transition-colors"></span>
                                            {{ $topic->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-sm text-gray-400 italic">Belum ada topik.</div>
                        @endif
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        <a href="{{ route('content.topic', $category->slug) }}" class="text-red-600 font-medium hover:text-red-700 flex items-center justify-between">
                            Lihat Semua Materi
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection