<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

// Import Stats
use App\Filament\Widgets\StatsOverview;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->font('Outfit')

            // --- LOGO + TEKS ---
            ->brandName(new HtmlString('
                <div class="flex items-center gap-3 font-bold text-xl tracking-tight">
                    <img src="'.asset('avatar/logoweb.png').'" alt="Logo" class="h-8 w-auto">
                    <span class="text-emerald-600 dark:text-emerald-400">Siaga</span><span class="text-slate-700 dark:text-slate-200">Bencana</span>
                </div>
            '))
            
            ->sidebarCollapsibleOnDesktop()
            ->spa()

            // --- CSS KUSTOM (AURORA + KARTU WARNA-WARNI) ---
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => <<<'HTML'
                    <style>
                        /* --- 1. BACKGROUND AURORA --- */
                        @keyframes moveBlob {
                            0% { transform: translate(0px, 0px) scale(1); }
                            33% { transform: translate(30px, -50px) scale(1.1); }
                            66% { transform: translate(-20px, 20px) scale(0.9); }
                            100% { transform: translate(0px, 0px) scale(1); }
                        }
                        body { background-color: #f8fafc; min-height: 100vh; position: relative; }
                        body::before, body::after { content: ''; position: fixed; width: 60vw; height: 60vw; border-radius: 50%; filter: blur(80px); opacity: 0.4; z-index: -1; animation: moveBlob 15s infinite alternate; }
                        body::before { background: #d1fae5; top: -10%; left: -10%; }
                        body::after { background: #cffafe; bottom: -10%; right: -10%; animation-delay: 5s; }
                        
                        .fi-body, .fi-panel, .fi-main { background-color: transparent !important; }
                        .fi-sidebar { background-color: rgba(255, 255, 255, 0.6) !important; backdrop-filter: blur(10px); border-right: 1px solid rgba(255,255,255,0.5); }
                        .fi-topbar { background-color: rgba(255, 255, 255, 0.7) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.5); }

                        /* --- 2. KARTU STATISTIK WARNA-WARNI & MENGKILAT --- */
                        
                        /* Animasi Kilat */
                        @keyframes shimmer {
                            0% { background-position: -200% center; }
                            100% { background-position: 200% center; }
                        }
                        
                        .fi-wi-stats-overview-stat {
                            border: 1px solid rgba(255, 255, 255, 0.5) !important;
                            border-radius: 1.2rem !important;
                            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
                            transition: transform 0.3s ease, box-shadow 0.3s ease;
                            position: relative;
                            overflow: hidden;
                            color: white !important; /* Teks jadi putih biar kontras */
                        }

                        /* Efek Kilat Berjalan */
                        .fi-wi-stats-overview-stat::before {
                            content: ''; position: absolute; top: 0; left: -150%; width: 150%; height: 100%;
                            background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%);
                            transform: skewX(-25deg); animation: shimmer 3s infinite linear; pointer-events: none;
                        }

                        /* Judul & Angka di dalam kartu jadi Putih */
                        .fi-wi-stats-overview-stat span, 
                        .fi-wi-stats-overview-stat h1, 
                        .fi-wi-stats-overview-stat div {
                            color: #ffffff !important;
                            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
                        }

                        /* --- MEWARNAI KARTU SATU PER SATU --- */
                        
                        /* Kartu 1 (Kategori): Hijau Emerald */
                        .fi-wi-stats-overview-stat:nth-child(1) {
                            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
                        }

                        /* Kartu 2 (Topik): Biru Langit */
                        .fi-wi-stats-overview-stat:nth-child(2) {
                            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
                        }

                        /* Kartu 3 (Video): Teal/Cyan */
                        .fi-wi-stats-overview-stat:nth-child(3) {
                            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
                        }

                        /* Kartu 4 (Modul): Orange/Amber */
                        .fi-wi-stats-overview-stat:nth-child(4) {
                            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
                        }

                        /* Efek Hover */
                        .fi-wi-stats-overview-stat:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
                        }

                        /* --- 3. AVATAR POJOK KANAN ATAS --- */
                        .fi-user-avatar {
                            background-color: #10b981 !important; /* Hijau */
                            color: white !important;
                            font-weight: 800 !important;
                            border: 2px solid #ffffff !important;
                            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                        }
                    </style>
                HTML
            )
            
            // --- SUNTIKAN UCAPAN SELAMAT DATANG (PHP + INISIAL) ---
            ->renderHook(
                PanelsRenderHook::CONTENT_START,
                fn (): string => Blade::render(<<<'HTML'
                    @php
                        // Logika Membuat Inisial Nama (Ambil huruf depan)
                        $name = auth()->user()->name ?? 'Admin';
                        $initials = collect(explode(' ', $name))
                            ->map(fn ($segment) => $segment[0] ?? '')
                            ->take(2)
                            ->join('');
                        $initials = strtoupper($initials);
                    @endphp

                    <div class="mb-8 mt-2 flex items-center gap-5">
                        {{-- Avatar Inisial Besar --}}
                        <div class="hidden sm:flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 shadow-xl shadow-emerald-500/30 text-white text-2xl font-bold border-4 border-white/50 transition duration-300 hover:scale-105">
                            {{ $initials }}
                        </div>
                        
                        {{-- Teks Sapaan --}}
                        <div>
                            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">
                                Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">{{ $name }}</span>! 👋
                            </h2>
                            <p class="text-slate-500 font-medium mt-1 text-lg">
                                Selamat datang di dashboard <span class="font-bold text-emerald-600">SiagaBencana</span>.
                            </p>
                        </div>
                    </div>
                HTML)
            )

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                StatsOverview::class, 
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}