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
            background: rgba(15, 23, 42, 0.6); /* Darker base for better contrast */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: rgba(16, 185, 129, 0.3);
        }

        /* --- 2. BUTTON GLOW --- */
        .btn-emerald-clean {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3), inset 0 1px 0 rgba(255,255,255,0.2);
            border: 1px solid rgba(16, 185, 129, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-emerald-clean:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.5);
            filter: brightness(1.1);
        }

        .btn-emerald-clean:active {
            transform: translateY(0);
        }

        /* --- 3. TYPOGRAPHY --- */
        .hero-title {
            font-size: clamp(2.5rem, 6vw, 5rem); /* Adjusted for better mobile fit */
            line-height: 1.05; 
            letter-spacing: -0.03em;
            font-weight: 900;
            text-shadow: 0 10px 40px rgba(0,0,0,0.4);
        }
        
        @media (min-width: 1024px) {
            .hero-title { line-height: 0.95; }
        }

        /* --- 4. VIDEO & BACKGROUND --- */
        .vignette-master {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, 
                rgba(2, 6, 23, 0.5) 0%, 
                rgba(2, 6, 23, 0.7) 40%, 
                rgba(2, 6, 23, 1) 100%
            );
            z-index: 2;
        }

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
            filter: saturate(1.1) brightness(0.8);
        }

        /* --- 5. SLIDER ANIMATION --- */
        .slide-item {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 1s ease-in-out;
            transform: translateX(50px) scale(0.9);
            pointer-events: none;
        }

        .slide-active {
            opacity: 1;
            transform: translateX(0) scale(1);
            z-index: 10;
        }

        /* Utility Helpers */
        .text-shadow-sm { text-shadow: 0 2px 4px rgba(0,0,0,0.3); }
    </style>
</head>
<body class="antialiased bg-slate-900 text-slate-800 overflow-x-hidden selection:bg-emerald-500 selection:text-white">
    
    <nav class="fixed w-full z-50 transition-all duration-300 py-4 top-0" id="mainNav">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center">
                
                <a href="/" class="flex items-center gap-3 group">
                    <div class="relative flex items-center justify-center">
                        <img src="{{ asset('avatar/logoweb.png') }}" 
                             alt="Logo" 
                             class="h-10 md:h-12 lg:h-14 w-auto object-contain transition-transform group-hover:scale-105"
                             onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                        
                        <div class="hidden h-10 w-10 md:h-12 md:w-12 lg:h-14 lg:w-14 rounded-full bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/40 animate-pulse-once">
                            <i class="fas fa-shield-halved text-lg md:text-xl"></i>
                        </div>
                    </div>

                    <div class="flex flex-col justify-center">
                        <h2 class="text-lg md:text-xl font-black uppercase tracking-tight text-white leading-none">
                            Siaga<span class="text-emerald-400">Bencana</span>
                        </h2>
                        <p class="text-[0.6rem] md:text-[0.65rem] font-bold text-slate-400 uppercase tracking-[0.2em] leading-tight mt-1">
                            Aceh Digilitera
                        </p>
                    </div>
                </a>

                <div class="flex items-center gap-4 md:gap-6">
                    <a href="{{ route('content.index') }}" class="hidden md:block text-sm font-bold text-slate-300 hover:text-white transition-colors duration-300">
                        Pusat Belajar
                    </a>
                    
                    <a href="/admin" class="btn-emerald-clean px-4 py-2 md:px-5 md:py-2.5 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-2 group">
                        <i class="fas fa-user-shield group-hover:rotate-12 transition-transform"></i> 
                        <span>Admin</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="relative min-h-screen w-full flex items-center justify-center overflow-hidden bg-slate-900">
        <div class="absolute inset-0 z-0 bg-slate-900">
            <div class="vignette-master"></div>
            <video 
                id="bgVideo" 
                autoplay 
                muted 
                loop 
                playsinline 
                preload="auto"
                poster="{{ asset('avatar/video-poster.jpg') }}" 
                class="opacity-70">
                <source src="{{ asset('vidio/vidiowelkom.mp4') }}" type="video/mp4">
                </video>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 w-full pt-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                
                <div class="text-center lg:text-left order-1">
                    <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full glass-premium border border-white/10 text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-6 backdrop-blur-md shadow-lg">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>Literasi Digital Aceh</span>
                    </div>
                    
                    <h1 class="hero-title text-white mb-6">
                        Budaya<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 filter drop-shadow-lg">
                            Siaga.
                        </span>
                    </h1>
                    
                    <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0 font-medium">
                        Membangun ketangguhan masyarakat Aceh melalui <span class="text-white font-bold border-b border-emerald-500">Edukasi Digital</span> berbasis kearifan lokal yang presisi dan mudah diakses.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('content.index') }}" class="btn-emerald-clean px-8 py-4 text-white text-sm font-black rounded-xl w-full sm:w-auto text-center shadow-emerald-500/20 shadow-xl">
                            Mulai Belajar
                        </a>
                        <a href="#tentang" class="glass-premium px-8 py-4 text-white text-sm font-bold rounded-xl hover:bg-white/10 transition-all border border-white/10 w-full sm:w-auto text-center">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>

                <div class="hidden lg:flex relative h-[600px] w-full pointer-events-none order-2 items-center justify-center">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-emerald-500/10 rounded-full blur-[80px]"></div>

                    <div class="slide-item slide-active">
                        <img src="{{ asset('avatar/foto/slide1.png') }}" onerror="this.style.display='none'" class="max-h-full w-auto object-contain drop-shadow-2xl">
                    </div>
                    <div class="slide-item">
                        <img src="{{ asset('avatar/foto/slide2.png') }}" onerror="this.style.display='none'" class="max-h-full w-auto object-contain drop-shadow-2xl">
                    </div>
                    <div class="slide-item">
                        <img src="{{ asset('avatar/foto/slide3.png') }}" onerror="this.style.display='none'" class="max-h-full w-auto object-contain drop-shadow-2xl">
                    </div>
                    <div class="slide-item">
                        <img src="{{ asset('avatar/foto/slide4.png') }}" onerror="this.style.display='none'" class="max-h-full w-auto object-contain drop-shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce hidden lg:block z-20">
            <a href="#tentang" class="text-white/30 hover:text-white transition p-2">
                <i class="fas fa-chevron-down text-xl"></i>
            </a>
        </div>
    </section>

    <section id="tentang" class="py-20 md:py-28 bg-slate-50 relative z-30 rounded-t-[3rem] -mt-16 shadow-[0_-20px_60px_rgba(0,0,0,0.3)]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between items-end mb-16 gap-6">
                <div class="max-w-2xl text-center lg:text-left mx-auto lg:mx-0">
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
                        Fokus Utama <span class="text-emerald-600">Literasi.</span>
                    </h2>
                    <p class="text-lg text-slate-500 font-light leading-relaxed">
                        Strategi komprehensif untuk mewujudkan Aceh yang tangguh bencana.
                    </p>
                </div>
                <div class="hidden lg:block h-1.5 w-24 bg-emerald-500 rounded-full mb-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <div class="glass-card p-8 rounded-[2rem] group hover:bg-white">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-laptop-code text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Edukasi Digital</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Akses materi pembelajaran interaktif kapan saja dan di mana saja.
                    </p>
                </div>

                <div class="glass-card p-8 rounded-[2rem] group hover:bg-white">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-mosque text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Kearifan Lokal</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Pendekatan mitigasi yang menghormati nilai budaya dan adat Aceh.
                    </p>
                </div>

                <div class="glass-card p-8 rounded-[2rem] group hover:bg-white">
                    <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-bolt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Respon Cepat</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Panduan praktis untuk tindakan cepat saat detik-detik krusial.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="materi" class="py-16 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Kategori Materi</h2>
                <a href="{{ route('content.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @if(isset($categories) && count($categories) > 0)
                    @foreach($categories as $category)
                        <a href="{{ route('content.topic', ['category' => $category->slug]) }}" class="group relative bg-slate-50 p-6 rounded-3xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-white text-emerald-600 mb-4 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                @if($category->icon)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($category->icon) }}" class="h-6 w-6 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                                    <i class="fas fa-shield-alt text-lg hidden"></i>
                                @else
                                    <i class="fas fa-shield-alt text-lg"></i>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="mt-2 text-xs text-slate-500 line-clamp-2">
                                {{ $category->description ?? 'Pelajari panduan mitigasi lengkap di sini.' }}
                            </p>
                        </a>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-10 text-slate-400 text-sm">
                        <i class="fas fa-folder-open mb-2 text-2xl"></i>
                        <p>Belum ada kategori materi.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <footer class="py-12 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('avatar/foto/logoweb.png') }}" 
                     alt="Logo Footer" 
                     class="h-10 w-auto opacity-40 hover:opacity-100 transition-opacity"
                     onerror="this.style.display='none'; document.getElementById('footerIcon').classList.remove('hidden');">
                
                <div id="footerIcon" class="hidden text-slate-300 text-3xl">
                    <i class="fas fa-shield-cat"></i>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-6 mb-8 text-xs font-bold text-slate-400 uppercase tracking-widest">
                <a href="#" class="hover:text-emerald-600 transition">Home</a>
                <a href="#tentang" class="hover:text-emerald-600 transition">Tentang</a>
                <a href="{{ route('content.index') }}" class="hover:text-emerald-600 transition">Materi</a>
                <a href="/admin" class="hover:text-emerald-600 transition">Login Admin</a>
            </div>
            
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Built with <i class="fas fa-heart text-red-400 mx-1"></i> for Aceh.
            </p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Navbar Logic
            const nav = document.getElementById('mainNav');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    nav.classList.add('glass-premium', 'shadow-lg');
                    nav.classList.remove('py-4');
                    nav.classList.add('py-2');
                } else {
                    nav.classList.remove('glass-premium', 'shadow-lg', 'py-2');
                    nav.classList.add('py-4');
                }
            });

            // 2. Slider Logic (Desktop Only)
            const items = document.querySelectorAll('.slide-item');
            let current = 0;
            if(items.length > 0) {
                setInterval(() => {
                    if (window.innerWidth >= 1024) {
                        items[current].classList.remove('slide-active');
                        current = (current + 1) % items.length;
                        items[current].classList.add('slide-active');
                    }
                }, 4000);
            }

            // 3. Robust Video Autoplay
            const video = document.getElementById('bgVideo');
            const playAttempt = setInterval(() => {
                if(video) {
                    video.play()
                        .then(() => {
                            clearInterval(playAttempt);
                        })
                        .catch(() => {
                            // Waiting for interaction
                        });
                }
            }, 3000);

            // Unlock audio/video context on first interaction
            const unlock = () => {
                if(video) video.play();
                document.removeEventListener('click', unlock);
                document.removeEventListener('touchstart', unlock);
            };
            document.addEventListener('click', unlock);
            document.addEventListener('touchstart', unlock);
        });
    </script>
</body>
</html>