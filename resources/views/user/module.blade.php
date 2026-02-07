@extends('layouts.user')

@section('content')
    {{-- 
        =========================================================
        FLIPBOOK READER (CUSTOM IMPLEMENTATION)
        =========================================================
        Stack: PDF.js (Renderer) + PageFlip (Animation) + Alpine.js (State)
        Keunggulan: Lebih ringan, UI bersih, Sidebar Info aktif.
    --}}

    {{-- Setup Variable URL PDF --}}
    @php
        $fileUrl = \Illuminate\Support\Facades\Storage::url($module->file_path);
    @endphp

    {{-- Load Library Khusus Halaman Ini --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script src="https://unpkg.com/page-flip@2.0.7/dist/js/page-flip.browser.js"></script>

    <script>
        // Set Worker PDF.js
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';
    </script>

    <div class="bg-gray-100 min-h-screen flex flex-col font-sans" x-data="flipbookApp()">
        
        {{-- 1. TOP NAVIGATION BAR --}}
        <div class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm h-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">
                
                {{-- Kiri: Tombol Kembali & Judul --}}
                <div class="flex items-center gap-4">
                    {{-- Link Kembali ke Topik --}}
                    <a href="{{ route('content.topic', ['category' => $module->topic->category->slug, 'topic' => $module->topic->slug]) }}" 
                       class="flex items-center gap-2 text-gray-500 hover:text-red-600 transition group">
                        <div class="p-2 rounded-full group-hover:bg-red-50 transition">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <span class="hidden sm:inline font-medium text-sm">Kembali</span>
                    </a>
                    
                    <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                    
                    <div class="overflow-hidden">
                        <h1 class="text-gray-900 font-bold text-lg leading-tight truncate max-w-[200px] sm:max-w-md" title="{{ $module->title }}">
                            {{ $module->title }}
                        </h1>
                    </div>
                </div>

                {{-- Kanan: Status Halaman --}}
                <div class="hidden md:flex items-center bg-gray-50 rounded-full px-4 py-1.5 border border-gray-200">
                    <span class="text-sm font-mono font-bold text-gray-600">
                        Hal <span x-text="currentPage">1</span> / <span x-text="totalPages">...</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- 2. MAIN CONTENT AREA (Split View) --}}
        <div class="flex-grow flex flex-col lg:flex-row h-[calc(100vh-4rem)] overflow-hidden">
            
            {{-- AREA BUKU (KIRI/TENGAH) --}}
            <div class="flex-grow bg-gray-200/50 relative flex items-center justify-center p-4 lg:p-8 overflow-hidden">
                
                {{-- Loading State --}}
                <div x-show="isLoading && !isError" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-gray-100">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-red-600 mb-4"></div>
                    <h3 class="text-gray-800 font-bold text-lg">Menyiapkan Buku...</h3>
                    <p class="text-gray-500 text-sm mt-1" x-text="loadingStatus"></p>
                </div>

                {{-- Error State --}}
                <div x-show="isError" style="display: none;" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-gray-100">
                    <div class="bg-white p-8 rounded-2xl shadow-xl text-center max-w-md mx-4">
                        <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Gagal Memuat</h3>
                        <p class="text-gray-500 text-sm mb-6" x-text="errorMessage"></p>
                        <a href="{{ $fileUrl }}" target="_blank" class="block w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold transition">
                            Download PDF Manual
                        </a>
                    </div>
                </div>

                {{-- FLIPBOOK CONTAINER --}}
                <div class="relative transition-all duration-700 ease-out"
                     x-show="!isLoading && !isError"
                     x-transition:enter="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    
                    {{-- Shadow buku agar terlihat 3D --}}
                    <div class="relative shadow-2xl">
                        <div id="book" class="hidden"></div>
                    </div>
                </div>

                {{-- Navigasi Floating (Mobile Only) --}}
                <div class="lg:hidden absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-4 bg-white px-6 py-2 rounded-full shadow-lg border border-gray-100 z-30">
                    <button @click="prevPage()" class="text-gray-600 hover:text-red-600"><i class="fas fa-chevron-left"></i></button>
                    <span class="font-bold text-gray-800"><span x-text="currentPage"></span></span>
                    <button @click="nextPage()" class="text-gray-600 hover:text-red-600"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            {{-- SIDEBAR INFO (KANAN - DESKTOP ONLY) --}}
            <div class="hidden lg:flex flex-col w-96 bg-white border-l border-gray-200 h-full overflow-y-auto z-10 shadow-lg">
                <div class="p-6">
                    {{-- Label Kategori --}}
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 uppercase tracking-wide">
                            {{ $module->topic->category->name }}
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 leading-snug mb-2">{{ $module->title }}</h2>
                    <p class="text-sm text-gray-500 mb-6 flex items-center">
                        <i class="far fa-clock mr-2"></i> {{ $module->created_at->translatedFormat('d F Y') }}
                    </p>

                    <hr class="border-gray-100 mb-6">

                    {{-- Deskripsi --}}
                    <div class="prose prose-sm text-gray-600 mb-8">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ringkasan Materi</h4>
                        <div class="text-justify">
                            {!! $module->description ?? 'Tidak ada deskripsi tambahan.' !!}
                        </div>
                    </div>

                    {{-- Kontrol Navigasi --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <button @click="prevPage()" class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-sm transition">
                            <i class="fas fa-arrow-left"></i> Prev
                        </button>
                        <button @click="nextPage()" class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-sm transition">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    {{-- Tombol Download --}}
                    <a href="{{ $fileUrl }}" download class="flex items-center justify-center gap-2 w-full py-4 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-red-200 transition transform hover:-translate-y-1">
                        <i class="fas fa-download"></i> Unduh PDF Asli
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPT LOGIC (ALPINE JS) --}}
    <script>
        function flipbookApp() {
            return {
                pdfDoc: null,
                book: null,
                isLoading: true,
                isError: false,
                errorMessage: '',
                loadingStatus: 'Menghubungkan...',
                currentPage: 1,
                totalPages: 0,
                pdfUrl: '{{ $fileUrl }}',

                async init() {
                    try {
                        const loadingTask = pdfjsLib.getDocument(this.pdfUrl);
                        
                        loadingTask.promise.then(async (pdf) => {
                            this.pdfDoc = pdf;
                            this.totalPages = this.pdfDoc.numPages;
                            this.loadingStatus = `Merender ${this.totalPages} halaman...`;

                            const bookElement = document.getElementById('book');
                            
                            // Limit halaman jika terlalu banyak agar browser tidak crash
                            // User tetap bisa download full PDF
                            const limitPages = Math.min(this.totalPages, 50); 
                            
                            for (let pageNum = 1; pageNum <= limitPages; pageNum++) {
                                const page = await this.pdfDoc.getPage(pageNum);
                                
                                // Scale 1.5 cukup tajam untuk layar laptop standar
                                const viewport = page.getViewport({ scale: 1.5 });
                                
                                const canvas = document.createElement('canvas');
                                const context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                
                                // PENTING: Style width/height 100% agar canvas responsif di dalam page div
                                canvas.style.width = '100%';
                                canvas.style.height = '100%';
                                
                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                
                                await page.render(renderContext).promise;

                                const pageDiv = document.createElement('div');
                                pageDiv.classList.add('bg-white'); // Kertas putih
                                pageDiv.classList.add('shadow-inner'); // Efek bayangan lipatan
                                
                                // Tandai halaman pertama/terakhir sebagai hard cover jika mau (opsional)
                                if (pageNum === 1) pageDiv.setAttribute('data-density', 'hard');
                                else pageDiv.setAttribute('data-density', 'soft');
                                
                                pageDiv.appendChild(canvas);
                                bookElement.appendChild(pageDiv);
                                
                                this.loadingStatus = `Menyiapkan hal ${pageNum} dari ${limitPages}...`;
                            }

                            // Delay sedikit untuk memastikan DOM render selesai
                            setTimeout(() => {
                                this.initFlipbook();
                                this.isLoading = false;
                            }, 500);

                        }, (error) => {
                            throw error;
                        });

                    } catch (error) {
                        console.error('Flipbook Error:', error);
                        this.isError = true;
                        this.errorMessage = 'Gagal memuat file PDF. Pastikan file tersedia.';
                    }
                },

                initFlipbook() {
                    const bookElement = document.getElementById('book');
                    bookElement.classList.remove('hidden');

                    const isMobile = window.innerWidth < 1024;
                    
                    // Dimensi Buku
                    const width = isMobile ? window.innerWidth * 0.9 : 450;
                    const height = isMobile ? window.innerHeight * 0.6 : 600;

                    this.book = new St.PageFlip(bookElement, {
                        width: width,
                        height: height,
                        size: isMobile ? 'fixed' : 'stretch',
                        minWidth: 300,
                        maxWidth: 1000,
                        minHeight: 400,
                        maxHeight: 1200,
                        maxShadowOpacity: 0.2,
                        showCover: true,
                        mobileScrollSupport: false 
                    });

                    this.book.loadFromHTML(document.querySelectorAll('#book > div'));

                    this.book.on('flip', (e) => {
                        this.currentPage = e.data + 1;
                    });

                    // FIX "LAYAR HITAM": Trigger resize event
                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 1000);
                },

                nextPage() { if(this.book) this.book.flipNext(); },
                prevPage() { if(this.book) this.book.flipPrev(); }
            }
        }
    </script>
@endsection