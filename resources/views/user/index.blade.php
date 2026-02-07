@extends('layouts.user')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">
                    Pusat Pengetahuan Bencana
                </h1>
                <p class="text-lg text-gray-600">
                    Pilih kategori bencana untuk mempelajari materi mitigasi, tonton video edukasi, dan akses buku saku panduan keselamatan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($categories as $category)
                    <a href="{{ route('content.topic', ['category' => $category->slug]) }}" 
                       class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden flex flex-col h-full transform hover:-translate-y-1">
                        
                        <div class="h-32 bg-gradient-to-br from-red-50 to-white flex items-center justify-center border-b border-gray-100 group-hover:from-red-100 transition">
                            @if($category->icon)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($category->icon) }}" alt="{{ $category->name }}" class="h-16 w-16 object-contain opacity-80 group-hover:opacity-100 transition transform group-hover:scale-110">
                            @else
                                <i class="fas fa-shield-alt text-5xl text-red-300 group-hover:text-red-500 transition"></i>
                            @endif
                        </div>

                        <div class="p-8 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition">
                                    {{ $category->name }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $category->topics_count }} Topik
                                </span>
                            </div>
                            
                            <p class="text-gray-500 text-sm leading-relaxed mb-6 flex-1">
                                {{ Str::limit($category->description ?? 'Pelajari langkah-langkah mitigasi dan panduan keselamatan untuk kategori ini.', 100) }}
                            </p>

                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center text-red-600 font-semibold text-sm group-hover:translate-x-2 transition-transform duration-300">
                                Mulai Belajar <i class="fas fa-arrow-right ml-2"></i>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                            <i class="fas fa-folder-open text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada kategori</h3>
                        <p class="text-gray-500">Silakan hubungi admin untuk menambahkan materi.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection