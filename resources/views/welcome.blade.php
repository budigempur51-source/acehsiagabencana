<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SiagaBencana Aceh') }} - Literasi Digital Mitigasi Banjir</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Figtree', sans-serif; letter-spacing: -0.01em; }

        /* --- 1. GLASSMORPHISM PREMIUM --- */
        .glass-premium {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* --- 2. BUTTON GLOW --- */
        .btn-emerald-clean {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3), inset 0 1px 0 rgba(255,255,255,0.2);
            border: 1px solid rgba(16, 185, 129, 0.2);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .btn-emerald-clean:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.5);
            filter: brightness(1.1);
        }

        /* --- 3. TYPOGRAPHY --- */
        .hero-title {
            font-size: clamp(2.5rem, 7vw, 6rem);
            line-height: 1.1; 
            letter-spacing: -0.04em;
            font-weight: 900;
            text-shadow: 0 10px 40px rgba(0,0,0,0.4);
        }
        
        @media (min-width: 1024px) {
            .hero-title { line-height: 0.9; }
        }

        .text-shadow-sm {
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        /* --- 4. VIDEO OVERLAY & PERFORMANCE --- */
        .vignette-master {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, 
                rgba(2, 6, 23, 0.4) 0%, 
                rgba(2, 6, 23, 0.7) 50%, 
                rgba(2, 6, 23, 0.95) 100%
            );
            z-index: 2;
        }

        /* Prevent Layout Shift */
        #bgVideo {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
            filter: saturate(1.2) contrast(1.1);
        }

        /* --- 5. SLIDER ANIMATION --- */
        .slide-item {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 1.2s ease-in-out;
            transform: translateX(30px) scale(0.95);
        }

        .slide-active {
            opacity: 1;
            transform: translateX(0) scale(1);
            z-index: 10;
        }
    </style>
</head>
<body class="antialiased bg-slate-900 text-slate-800 overflow-x-hidden selection:bg-emerald-500 selection:text-white">
    
    <nav class="fixed w-full z-50 transition-all duration-500 py-4 lg:py-6 top-0" id="mainNav">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3 lg:space-x-6">
                    <img src="{{ asset('avatar/logoweb.png') }}" onerror="this.src='https://via.placeholder.com/150?text=LOGO'" alt="Logo" class="h-10 md:h-16 lg:h-20 w-auto drop-shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                    <div class="hidden sm:block border-l border-white/20 pl-4 md:pl-6">
                        <h2 class="text-sm md:text-2xl font-black uppercase tracking-tighter text-shadow-sm text-white">SiagaBencana</h2>
                        <p class="text-[8px] md:text-[9px] font-bold text-emerald-400 uppercase tracking-[0.4em] text-shadow-sm">Aceh Digilitera</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 lg:space-x-8">
                    <a href="{{ route('content.index') }}" class="text-xs md:text-sm font-bold text-white/90 hover:text-emerald-400 transition text-shadow-sm">Pusat Belajar</a>
                    
                    <a href="/admin" class="btn-emerald-clean px-5 py-2 md:px-6 md:py-2.5 text-white text-[10px] md:text-xs font-black rounded-full shadow-2xl flex items-center gap-2">
                        <i class="fas fa-user-shield"></i> Admin Area
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="relative min-h-screen w-full flex items-center justify-center overflow-hidden bg-slate-900">
        <div class="absolute inset-0 z-0">
            <div class="vignette-master"></div>
            <video 
                id="bgVideo" 
                autoplay 
                muted 
                loop 
                playsinline 
                preload="auto"
                class="opacity-60">
                <source src="{{ asset('vidio/vidiowelkom.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-10 relative z-10 w-full pt-24 lg:pt-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                
                <div class="text-center lg:text-left order-1">
                    <div class="inline-flex items-center space-x-3 px-4 py-1.5 rounded-full glass-premium border-white/10 text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] mb-6 md:mb-10 backdrop-blur-md">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_#10b981]"></span>
                        <span>Literasi Digital Aceh</span>
                    </div>
                    
                    <h1 class="hero-title text-white mb-6 leading-tight">
                        Budaya<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 drop-shadow-lg">Siaga.</span>
                    </h1>
                    
                    <p class="text-base md:text-xl lg:text-2xl text-slate-200/90 leading-relaxed mb-10 max-w-lg mx-auto lg:mx-0 font-medium text-shadow-sm">
                        Membangun ketangguhan masyarakat Aceh melalui <span class="text-white font-bold border-b-2 border-emerald-500/50">Edukasi Digital</span> berbasis kearifan lokal yang presisi.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('content.index') }}" class="btn-emerald-clean px-8 py-4 text-white text-sm md:text-lg font-black rounded-2xl w-full sm:w-auto text-center">
                            Mulai Belajar
                        </a>
                        <a href="#tentang" class="glass-premium px-8 py-4 text-white text-sm md:text-lg font-bold rounded-2xl hover:bg-white/10 transition-all border border-white/10 w-full sm:w-auto text-center">
                            Lihat Materi
                        </a>
                    </div>
                </div>

                <div class="hidden lg:flex relative h-[600px] lg:h-[700px] w-full pointer-events-none order-2 items-center justify-center">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-emerald-500/20 rounded-full blur-[100px] animate-pulse"></div>

                    <div class="slide-item slide-active">
                        <img src="{{ asset('avatar/slide1.png') }}" class="max-h-full w-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.6)]">
                    </div>
                    <div class="slide-item">
                        <img src="{{ asset('avatar/slide2.png') }}" class="max-h-full w-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.6)]">
                    </div>
                    <div class="slide-item">
                        <img src="{{ asset('avatar/slide3.png') }}" class="max-h-full w-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.6)]">
                    </div>
                    <div class="slide-item">
                        <img src="{{ asset('avatar/slide4.png') }}" class="max-h-full w-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.6)]">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce hidden lg:block z-20">
            <a href="#tentang" class="text-white/50 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </a>
        </div>
    </section>

    <section id="tentang" class="py-20 md:py-32 bg-white text-slate-950 relative z-30 rounded-t-[2.5rem] md:rounded-t-[5rem] -mt-10 lg:-mt-20 shadow-[0_-20px_60px_rgba(0,0,0,0.5)]">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex flex-col lg:flex-row justify-between items-end mb-16 gap-6">
                <div class="max-w-3xl text-center lg:text-left mx-auto lg:mx-0">
                    <h2 class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tighter leading-[0.9] mb-6 uppercase">
                        Fokus Utama<br><span class="text-emerald-600">Literasi Kami.</span>
                    </h2>
                    <p class="text-lg md:text-2xl text-slate-500 font-light max-w-xl mx-auto lg:mx-0">Strategi tepat sasaran untuk mewujudkan Aceh yang lebih tangguh menghadapi bencana.</p>
                </div>
                <div class="hidden lg:block h-2 w-32 bg-emerald-500 rounded-full mb-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                <div class="glass-card p-8 md:p-12 rounded-[2rem] border border-slate-100 group">
                    <div class="w-16 h-16 bg-emerald-500 text-white rounded-3xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-laptop-code text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 tracking-tight text-slate-800">Edukasi Digital</h3>
                    <p class="text-base text-slate-500 leading-relaxed">Modul pembelajaran berbasis video dan interaksi untuk semua usia, mudah diakses di mana saja.</p>
                </div>

                <div class="glass-card p-8 md:p-12 rounded-[2rem] border border-slate-100 group">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-3xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-mosque text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 tracking-tight text-slate-800">Kearifan Lokal</h3>
                    <p class="text-base text-slate-500 leading-relaxed">Penyelamatan aset budaya dan pendekatan mitigasi berbasis nilai-nilai ke-Aceh-an.</p>
                </div>

                <div class="glass-card p-8 md:p-12 rounded-[2rem] border border-slate-100 group">
                    <div class="w-16 h-16 bg-rose-600 text-white rounded-3xl flex items-center justify-center mb-6 shadow-lg shadow-rose-500/30 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-hands-helping text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 tracking-tight text-slate-800">Cepat Tanggap</h3>
                    <p class="text-base text-slate-500 leading-relaxed">Materi praktis yang mempersiapkan Anda bertindak cepat dan tepat saat sirine berbunyi.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="materi" class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Pilih Kategori</h2>
                    <p class="text-slate-500 mt-2">Pelajari mitigasi berdasarkan jenis ancaman.</p>
                </div>
                <a href="{{ route('content.index') }}" class="text-emerald-600 font-bold hover:text-emerald-700 mt-4 md:mt-0">Lihat Semua &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('content.topic', ['category' => $category->slug]) }}" class="group relative bg-white p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:-translate-y-1">
                        <div class="flex items-center justify-center h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-emerald-200">
                            @if($category->icon)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($category->icon) }}" class="h-8 w-8 object-contain filter brightness-0 invert">
                            @else
                                <i class="fas fa-shield-alt text-2xl"></i>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-emerald-600 transition">
                            {{ $category->name }}
                        </h3>
                        <p class="mt-3 text-sm text-slate-500 line-clamp-2">
                            {{ $category->description ?? 'Panduan lengkap keselamatan dan mitigasi.' }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="py-16 bg-white border-t border-slate-100 text-center">
        <div class="max-w-7xl mx-auto px-6">
            <img src="{{ asset('avatar/logoweb.png') }}" onerror="this.style.display='none'" alt="Logo" class="h-10 md:h-16 w-auto mx-auto mb-8 grayscale opacity-30 hover:opacity-100 transition-all duration-700 cursor-pointer">
            <div class="flex flex-wrap justify-center gap-6 md:gap-10 mb-8 text-xs md:text-sm font-bold text-slate-400 uppercase tracking-widest">
                <a href="#" class="hover:text-emerald-500 transition">Beranda</a>
                <a href="#tentang" class="hover:text-emerald-500 transition">Tentang</a>
                <a href="{{ route('content.index') }}" class="hover:text-emerald-500 transition">Materi</a>
                <a href="/admin" class="hover:text-emerald-500 transition">Admin</a>
            </div>
            <p class="text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Program Mahasiswa Berdampak Aceh.
            </p>
        </div>
    </footer>

    <script>
        // --- 1. Navbar Scroll Styling ---
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('glass-premium', 'py-3', 'shadow-2xl');
                nav.classList.remove('py-4', 'lg:py-6');
            } else {
                nav.classList.remove('glass-premium', 'py-3', 'shadow-2xl');
                nav.classList.add('py-4', 'lg:py-6');
            }
        });

        // --- 2. Slider Desktop Logic ---
        let current = 0;
        const items = document.querySelectorAll('.slide-item');
        
        function rotate() {
            if(items.length > 0 && window.innerWidth >= 1024) { 
                items[current].classList.remove('slide-active');
                current = (current + 1) % items.length;
                items[current].classList.add('slide-active');
            }
        }
        setInterval(rotate, 5000);

        // --- 3. THE "MAGIC" AUTOPLAY FIX (FOR IPHONE & ALL DEVICES) ---
        // Penjelasan: Browser modern melarang autoplay jika tidak berinteraksi. 
        // Script ini menangkap interaksi pertama (klik/sentuh) di mana saja untuk memulai video.
        
        const video = document.getElementById('bgVideo');

        function playVideo() {
            if (video) {
                video.play().then(() => {
                    console.log("Video started successfully");
                    // Jika sukses jalan, hapus listener agar tidak boros resource
                    document.removeEventListener('click', playVideo);
                    document.removeEventListener('touchstart', playVideo);
                }).catch(error => {
                    console.log("Playback failed, waiting for user interaction.");
                });
            }
        }

        // Coba jalan otomatis saat load
        window.addEventListener('load', playVideo);
        
        // Paksa jalan saat ada sentuhan pertama di layar (Sangat ampuh di iPhone)
        document.addEventListener('click', playVideo);
        document.addEventListener('touchstart', playVideo);
    </script>
</body>
</html>