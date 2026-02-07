@extends('layouts.user')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="flex-shrink-0 flex justify-center md:justify-start">
                            <div class="w-40 h-56 bg-red-50 rounded-lg border-2 border-red-100 flex items-center justify-center text-red-500 shadow-inner">
                                <i class="fas fa-file-pdf text-6xl"></i>
                            </div>
                        </div>

                        <div class="flex-1 text-center md:text-left">
                            <div class="flex items-center justify-center md:justify-start space-x-2 text-sm text-red-600 font-medium mb-2">
                                <span class="bg-red-50 px-2 py-1 rounded">{{ $module->topic->category->name }}</span>
                                <span>&bull;</span>
                                <span>{{ $module->topic->name }}</span>
                            </div>
                            
                            <h1 class="text-3xl font-extrabold text-gray-900 mb-4">{{ $module->title }}</h1>
                            <p class="text-gray-600 leading-relaxed mb-8">
                                {{ $module->description }}
                            </p>

                            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($module->file_path) }}" target="_blank" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 md:text-lg transition-colors shadow-sm">
                                    <i class="fas fa-file-download mr-2"></i>
                                    Download / Baca PDF
                                </a>
                                
                                <a href="{{ route('content.topic', ['category' => $module->topic->category->slug, 'topic' => $module->topic->slug]) }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 md:text-lg transition-colors">
                                    Kembali ke Topik
                                </a>
                            </div>
                            
                            <p class="mt-4 text-xs text-gray-400">
                                *Pastikan perangkat Anda mendukung pembaca PDF untuk membuka file ini secara langsung.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection