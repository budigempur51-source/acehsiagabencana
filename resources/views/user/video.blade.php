@extends('layouts.user')

@section('content')
    <div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex text-sm font-medium text-gray-500 space-x-2 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('content.index') }}" class="hover:text-red-600 transition">Pusat Belajar</a>
                <span>/</span>
                <a href="{{ route('content.topic', ['category' => $video->topic->category->slug]) }}" class="hover:text-red-600 transition">
                    {{ $video->topic->category->name }}
                </a>
                <span>/</span>
                <a href="{{ route('content.topic', ['category' => $video->topic->category->slug, 'topic' => $video->topic->slug]) }}" class="hover:text-red-600 transition">
                    {{ $video->topic->name }}
                </a>
            </nav>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2">
                    <div class="bg-black rounded-2xl shadow-lg overflow-hidden mb-6">
                        <div class="relative w-full" style="padding-top: 56.25%;"> @if($video->url)
                                @php
                                    // 1. Ambil Youtube ID
                                    $url = $video->url;
                                    parse_str(parse_url($url, PHP_URL_QUERY), $params);
                                    $youtubeId = $params['v'] ?? null;
                                    
                                    if(!$youtubeId && strpos($url, 'youtu.be') !== false) {
                                        $youtubeId = substr(parse_url($url, PHP_URL_PATH), 1);
                                    }

                                    // 2. Ambil Origin Domain (Paling Aman untuk Hosting & Cloudflare)
                                    $origin = request()->schemeAndHttpHost();
                                @endphp

                                @if($youtubeId)
                                    {{-- 
                                        FIX ERROR 153 & MOBILE ISSUE:
                                        1. playsinline=1 -> Wajib buat iPhone biar gak maksa fullscreen.
                                        2. autoplay=0 -> Hapus autoplay biar stabil di HP (User harus klik play).
                                        3. enablejsapi=1 -> Aktifkan API JS YouTube.
                                        4. origin -> Identitas domain kita.
                                        5. referrerpolicy -> Agar browser kirim header origin yang benar.
                                    --}}
                                    <iframe class="absolute top-0 left-0 w-full h-full" 
                                            src="https://www.youtube.com/embed/{{ $youtubeId }}?enablejsapi=1&playsinline=1&rel=0&modestbranding=1&origin={{ $origin }}" 
                                            title="{{ $video->title }}" 
                                            frameborder="0" 
                                            referrerpolicy="strict-origin-when-cross-origin"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                            allowfullscreen>
                                    </iframe>
                                @else
                                    <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center text-white">
                                        <div class="text-center">
                                            <i class="fas fa-exclamation-triangle text-4xl mb-2 text-red-500"></i>
                                            <p>Video tidak valid</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 leading-tight">
                            {{ $video->title }}
                        </h1>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-6 pb-6 border-b border-gray-100">
                            <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full font-medium mr-3">
                                {{ $video->topic->name }}
                            </span>
                            <i class="far fa-calendar-alt mr-1"></i> {{ $video->created_at->translatedFormat('d F Y') }}
                        </div>

                        <div class="prose max-w-none text-gray-600">
                            {!! nl2br(e($video->description)) !!}
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                        <div class="p-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-bold text-gray-800">Video Lainnya</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($relatedVideos as $related)
                                <a href="{{ route('content.video', $related->slug) }}" class="flex gap-4 p-4 hover:bg-gray-50 transition group">
                                    <div class="flex-shrink-0 w-32 h-20 bg-gray-200 rounded-lg overflow-hidden relative">
                                        {{-- Logic Thumbnail Sederhana untuk Related --}}
                                        @php
                                            $thumb = $related->thumbnail ? \Illuminate\Support\Facades\Storage::url($related->thumbnail) : null;
                                            if(!$thumb && $related->url) {
                                                // Coba ambil dari Youtube
                                                $rUrl = $related->url;
                                                parse_str(parse_url($rUrl, PHP_URL_QUERY), $rParams);
                                                $rId = $rParams['v'] ?? null;
                                                if(!$rId && strpos($rUrl, 'youtu.be') !== false) {
                                                    $rId = substr(parse_url($rUrl, PHP_URL_PATH), 1);
                                                }
                                                if($rId) $thumb = "https://img.youtube.com/vi/{$rId}/mqdefault.jpg";
                                            }
                                        @endphp

                                        <img src="{{ $thumb ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition flex items-center justify-center">
                                            <i class="fas fa-play text-white opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0 py-1">
                                        <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug group-hover:text-red-600 transition">
                                            {{ $related->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $related->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="p-8 text-center text-gray-400">
                                    <i class="fas fa-film text-2xl mb-2"></i>
                                    <p class="text-sm">Tidak ada video terkait.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection