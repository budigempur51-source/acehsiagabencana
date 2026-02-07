@extends('layouts.user')

@section('content')
    <div class="bg-gray-900 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <nav class="flex text-sm font-medium text-gray-400 mb-6">
                <a href="{{ route('content.index') }}" class="hover:text-white">Pusat Belajar</a>
                <span class="mx-2">/</span>
                <a href="{{ route('content.topic', ['category' => $video->topic->category->slug, 'topic' => $video->topic->slug]) }}" class="hover:text-white">
                    {{ $video->topic->name }}
                </a>
                <span class="mx-2">/</span>
                <span class="text-white">Nonton</span>
            </nav>

            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <div class="lg:col-span-2">
                    <div class="aspect-video bg-black rounded-xl overflow-hidden shadow-2xl">
                        <iframe class="w-full h-full" 
                            src="https://www.youtube.com/embed/{{ $video->youtube_id }}?rel=0&autoplay=1" 
                            title="YouTube video player" frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    
                    <div class="mt-6">
                        <h1 class="text-2xl font-bold text-white">{{ $video->title }}</h1>
                        <div class="mt-2 flex items-center text-gray-400 text-sm">
                            <i class="far fa-clock mr-2"></i> {{ $video->duration }} Menit
                            <span class="mx-3">•</span>
                            <span class="px-2 py-0.5 rounded bg-gray-800 text-gray-300 text-xs">{{ $video->topic->name }}</span>
                        </div>
                        <div class="mt-6 bg-gray-800 rounded-xl p-6 text-gray-300 leading-relaxed">
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-2">Deskripsi Video</h3>
                            <p>{{ $video->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 lg:mt-0">
                    <h3 class="text-lg font-bold text-white mb-4">Video Terkait</h3>
                    <div class="space-y-4">
                        @foreach($relatedVideos as $related)
                            <a href="{{ route('content.video', $related->slug) }}" class="flex gap-4 group">
                                <div class="relative flex-shrink-0 w-32 aspect-video bg-gray-800 rounded-lg overflow-hidden">
                                    <img src="https://img.youtube.com/vi/{{ $related->youtube_id }}/mqdefault.jpg" class="w-full h-full object-cover group-hover:opacity-75 transition-opacity">
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white line-clamp-2 group-hover:text-red-400 transition-colors">{{ $related->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $related->duration }} min</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection