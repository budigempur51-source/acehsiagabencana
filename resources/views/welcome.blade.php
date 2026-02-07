@extends('layouts.user')

@section('content')
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Siap Siaga Hadapi</span>
                            <span class="block text-red-600 xl:inline">Bencana Alam</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Edukasi mitigasi bencana yang lengkap, mudah dipahami, dan dapat diakses kapan saja. Lindungi diri dan keluarga Anda dengan pengetahuan yang tepat.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('content.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 md:py-4 md:text-lg">
                                    Mulai Belajar
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#videos" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 md:py-4 md:text-lg">
                                    Tonton Video
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-gray-100">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1581093583449-ed2521338d12?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Tim Penyelamat">
        </div>
    </div>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-red-600 font-semibold tracking-wide uppercase">Materi Pembelajaran</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Pilih Fase Bencana
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Pelajari langkah-langkah yang harus dilakukan sebelum, saat, dan sesudah bencana terjadi.
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($categories as $category)
                        <a href="{{ route('content.topic', $category->slug) }}" class="group relative bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white mb-4 group-hover:scale-110 transition-transform">
                                <i class="{{ $category->icon ?? 'fas fa-book' }} text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-red-600">
                                {{ $category->name }}
                            </h3>
                            <p class="mt-2 text-base text-gray-500">
                                {{ $category->description ?? 'Pelajari panduan lengkap di kategori ini.' }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="videos" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Video Edukasi Pilihan</h2>
                    <p class="mt-2 text-gray-500">Tonton panduan visual agar lebih mudah dipahami.</p>
                </div>
                <a href="{{ route('content.index') }}" class="text-red-600 hover:text-red-700 font-medium">Lihat Semua &rarr;</a>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredVideos as $video)
                    <div class="flex flex-col rounded-lg shadow-lg overflow-hidden border border-gray-100">
                        <div class="flex-shrink-0 relative">
                            <img class="h-48 w-full object-cover" src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}">
                            <a href="{{ route('content.video', $video->slug) }}" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 opacity-0 hover:opacity-100 transition-opacity duration-300">
                                <i class="fas fa-play-circle text-6xl text-white"></i>
                            </a>
                        </div>
                        <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-red-600">
                                    {{ $video->topic->name ?? 'Umum' }}
                                </p>
                                <a href="{{ route('content.video', $video->slug) }}" class="block mt-2">
                                    <p class="text-xl font-semibold text-gray-900 hover:text-red-600 transition-colors">{{ $video->title }}</p>
                                    <p class="mt-3 text-base text-gray-500 line-clamp-2">
                                        {{ $video->description }}
                                    </p>
                                </a>
                            </div>
                            <div class="mt-4 flex items-center text-sm text-gray-500">
                                <i class="far fa-clock mr-1.5"></i> {{ $video->duration }} menit
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection