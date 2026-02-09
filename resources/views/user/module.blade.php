@extends('layouts.user')

@section('content')
    {{-- 
        =========================================================
        FLIPBOOK READER (REMASTERED DESIGN)
        =========================================================
        Fitur: Animasi Aurora Background + Glassmorphism Sidebar
    --}}

    {{-- Setup Variable URL PDF --}}
    @php
        $fileUrl = \Illuminate\Support\Facades\Storage::url($module->file_path);
    @endphp

    {{-- CUSTOM STYLE (Aurora & Glass) --}}
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
            background: #f8fafc; /* Base color */
        }
        .blob {
            position: absolute; filter: blur(80px); opacity: 0.5;
            animation: moveBlob 10s infinite alternate;
        }
        .blob-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #d1fae5; }
        .blob-2 { bottom: -10%; right: -10%; width: 50vw; height: 50vw; background: #cffafe; animation-delay: 2s; }
        
        /* Glass Sidebar */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px);
            border-left: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: -4px 0 15px rgba(0, 0, 0, 0.05);
        }
    </style>

    {{-- Load Library --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script src="https://unpkg.com/page-flip@2.0.7/dist/js/page-flip.browser.js"></script>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';
    </script>

    <div class="min-h-screen flex flex-col font-sans relative overflow-hidden" x-data="flipbookApp()">
        
        {{-- BACKGROUND ELEMENT --}}
        <div class="blob-cont">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
        </div>

        {{-- 1. TOP NAVIGATION BAR (Glassmorphism) --}}
        <div class="bg-white/70 backdrop-blur-md border-b border-white/50 sticky top-0 z-40 h-20 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">
                
                {{-- Kiri: Tombol Kembali & Judul --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('content.topic', ['category' => $module->topic->category->slug, 'topic' => $module->topic->slug]) }}" 
                       class="flex items-center gap-2 text-slate-500 hover:text-emerald-600 transition group font-bold">
                        <div class="p-2 rounded-xl group-hover:bg-emerald-50 transition border border-transparent group-hover:border-emerald-200">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <span class="hidden sm:inline text-sm">Kembali</span>
                    </a>
                    
                    <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
                    
                    <div class="overflow-hidden">
                        <h1 class="text-slate-800 font-black text-lg leading-tight truncate max-w-[200px] sm:max-w-md tracking-tight" title="{{ $module->title }}">
                            {{ $module->title }}
                        </h1>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Pembelajaran</span>
                    </div>
                </div>

                {{-- Kanan: Status Halaman --}}
                <div class="hidden md:flex items-center bg-white/80 rounded-xl px-5 py-2 border border-slate-200 shadow-sm">
                    <span class="text-sm font-bold text-slate-600">
                        Hal <span x-text="currentPage" class="text-emerald-600">1</span> <span class="text-slate-300 mx-1">/</span> <span x-text="totalPages">...</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- 2. MAIN CONTENT AREA (Split View) --}}
        <div class="flex-grow flex flex-col lg:flex-row h-[calc(100vh-5rem)] overflow-hidden relative z-10">
            
            {{-- AREA BUKU (KIRI/TENGAH) --}}
            <div class="flex-grow relative flex items-center justify-center p-4 lg:p-8 overflow-hidden">
                
                {{-- Loading State --}}
                <div x-show="isLoading && !isError" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/50 backdrop-blur-sm">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-emerald-200 border-t-emerald-600 mb-6"></div>
                    <h3 class="text-slate-800 font-black text-xl tracking-tight">Menyiapkan Buku...</h3>
                    <p class="text-slate-500 text-sm mt-2 font-medium bg-white px-4 py-1 rounded-full shadow-sm" x-text="loadingStatus"></p>
                </div>

                {{-- Error State --}}
                <div x-show="isError" style="display: none;" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/80 backdrop-blur-md">
                    <div class="bg-white p-8 rounded-3xl shadow-2xl text-center max-w-md mx-4 border border-red-100">
                        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-book-dead text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Gagal Memuat</h3>
                        <p class="text-slate-500 text-sm mb-8 leading-relaxed px-4" x-text="errorMessage"></p>
                        <a href="{{ $fileUrl }}" target="_blank" class="block w-full py-4 px-6 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-bold transition shadow-lg shadow-red-200">
                            Download PDF Manual
                        </a>
                    </div>
                </div>

                {{-- FLIPBOOK CONTAINER --}}
                <div class="relative transition-all duration-700 ease-out transform"
                     x-show="!isLoading && !isError"
                     x-transition:enter="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    
                    {{-- Efek Bayangan Buku Realistis --}}
                    <div class="relative shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-r-lg">
                        <div id="book" class="hidden"></div>
                    </div>
                </div>

                {{-- Navigasi Floating (Mobile Only) --}}
                <div class="lg:hidden absolute bottom-8 left-1/2 transform -translate-x-1/2 flex gap-6 bg-slate-900/90 backdrop-blur text-white px-8 py-3 rounded-full shadow-2xl z-30 border border-white/10">
                    <button @click="prevPage()" class="hover:text-emerald-400 transition"><i class="fas fa-chevron-left text-lg"></i></button>
                    <span class="font-bold font-mono"><span x-text="currentPage"></span></span>
                    <button @click="nextPage()" class="hover:text-emerald-400 transition"><i class="fas fa-chevron-right text-lg"></i></button>
                </div>
            </div>

            {{-- SIDEBAR INFO (KANAN - DESKTOP ONLY) --}}
            <div class="hidden lg:flex flex-col w-96 glass-sidebar h-full overflow-y-auto z-20 custom-scrollbar">
                <div class="p-8">
                    {{-- Label Kategori --}}
                    <div class="mb-6">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 uppercase tracking-widest shadow-sm">
                            {{ $module->topic->category->name }}
                        </span>
                    </div>

                    <h2 class="text-3xl font-black text-slate-800 leading-tight mb-3">{{ $module->title }}</h2>
                    <p class="text-sm text-slate-500 mb-8 flex items-center font-medium">
                        <i class="far fa-calendar-alt mr-2 text-emerald-500"></i> {{ $module->created_at->translatedFormat('d F Y') }}
                    </p>

                    <div class="h-px bg-gradient-to-r from-slate-200 to-transparent mb-8"></div>

                    {{-- Deskripsi --}}
                    <div class="prose prose-sm text-slate-600 mb-10">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-align-left"></i> Ringkasan Materi
                        </h4>
                        <div class="text-justify leading-relaxed bg-white/50 p-4 rounded-2xl border border-white/60 shadow-sm">
                            {!! $module->description ?? 'Tidak ada deskripsi tambahan.' !!}
                        </div>
                    </div>

                    {{-- Kontrol Navigasi --}}
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <button @click="prevPage()" class="flex items-center justify-center gap-2 px-4 py-4 bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 rounded-2xl font-bold text-sm transition border border-slate-200 shadow-sm hover:shadow-md group">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Prev
                        </button>
                        <button @click="nextPage()" class="flex items-center justify-center gap-2 px-4 py-4 bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 rounded-2xl font-bold text-sm transition border border-slate-200 shadow-sm hover:shadow-md group">
                            Next <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>

                    {{-- Tombol Download --}}
                    <a href="{{ $fileUrl }}" download class="flex items-center justify-center gap-3 w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-bold text-sm shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1">
                        <i class="fas fa-file-download text-emerald-400"></i> Unduh Dokumen PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT LOGIC (SAMA PERSIS - TIDAK BERUBAH) --}}
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
                            const limitPages = Math.min(this.totalPages, 50); 
                            
                            for (let pageNum = 1; pageNum <= limitPages; pageNum++) {
                                const page = await this.pdfDoc.getPage(pageNum);
                                const viewport = page.getViewport({ scale: 1.5 });
                                
                                const canvas = document.createElement('canvas');
                                const context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                canvas.style.width = '100%';
                                canvas.style.height = '100%';
                                
                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                await page.render(renderContext).promise;

                                const pageDiv = document.createElement('div');
                                pageDiv.classList.add('bg-white'); 
                                pageDiv.classList.add('shadow-inner'); 
                                
                                if (pageNum === 1) pageDiv.setAttribute('data-density', 'hard');
                                else pageDiv.setAttribute('data-density', 'soft');
                                
                                pageDiv.appendChild(canvas);
                                bookElement.appendChild(pageDiv);
                                
                                this.loadingStatus = `Menyiapkan hal ${pageNum} dari ${limitPages}...`;
                            }

                            setTimeout(() => {
                                this.initFlipbook();
                                this.isLoading = false;
                            }, 500);

                        }, (error) => { throw error; });

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
                    const width = isMobile ? window.innerWidth * 0.9 : 450;
                    const height = isMobile ? window.innerHeight * 0.6 : 600;

                    this.book = new St.PageFlip(bookElement, {
                        width: width,
                        height: height,
                        size: isMobile ? 'fixed' : 'stretch',
                        minWidth: 300, maxWidth: 1000,
                        minHeight: 400, maxHeight: 1200,
                        maxShadowOpacity: 0.2,
                        showCover: true,
                        mobileScrollSupport: false 
                    });

                    this.book.loadFromHTML(document.querySelectorAll('#book > div'));

                    this.book.on('flip', (e) => {
                        this.currentPage = e.data + 1;
                    });

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