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
            
            // --- 1. SETTING DASAR & FAVICON ---
            ->darkMode(false) // Wajib False biar putih bersih
            ->favicon(asset('avatar/logoweb.png')) // <--- INI BIAR LOGO DI TAB BROWSER BERUBAH
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->font('Outfit')
            
            // Logo Header (Dashboard Only)
            ->brandName(new HtmlString('
                <div class="flex items-center gap-3 font-bold text-xl tracking-tight">
                    <img src="'.asset('avatar/logoweb.png').'" alt="Logo" class="h-8 w-auto">
                    <span class="text-emerald-600">Siaga</span><span class="text-slate-700">Bencana</span>
                </div>
            '))
            
            ->sidebarCollapsibleOnDesktop()
            ->spa()

            // --- 2. KONTEN PANEL KIRI (SIRAJA STYLE) ---
            ->renderHook(
                PanelsRenderHook::SIMPLE_PAGE_START,
                fn (): string => Blade::render(<<<'HTML'
                    @if (request()->routeIs('filament.admin.auth.login'))
                        <div class="split-screen-left">
                            <div class="left-content">
                                {{-- Judul Besar --}}
                                <h1 class="brand-title animate-in">SIAGA BENCANA</h1>
                                
                                {{-- Subjudul --}}
                                <p class="brand-subtitle animate-in delay-1">
                                    APLIKASI INFORMASI & EDUKASI<br>
                                    PENANGGULANGAN BENCANA ALAM
                                </p>
                                
                                {{-- Logo Web Besar --}}
                                <div class="brand-logo-container animate-float delay-2">
                                    <img src="{{ asset('avatar/logoweb.png') }}" alt="Logo" class="brand-logo">
                                </div>
                                
                                {{-- Footer --}}
                                <div class="brand-footer animate-in delay-3">
                                    <p>Badan Penanggulangan Bencana Daerah (BPBD)</p>
                                    <p>Pemerintah Aceh</p>
                                    <p class="copyright">Copyright © {{ date('Y') }}. Hak Cipta Dilindungi.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                HTML)
            )

            // --- 3. CSS "PENGHANCUR" LAYOUT BAWAAN ---
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => <<<'HTML'
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');

                        /* RESET GLOBAL */
                        html, body {
                            font-family: 'Outfit', sans-serif !important;
                            background-color: #ffffff !important;
                            margin: 0; padding: 0;
                            overflow-x: hidden;
                        }

                        /* ==================================================
                           KHUSUS HALAMAN LOGIN (SPLIT SCREEN FIX)
                           ================================================== */
                        
                        /* 1. Paksa Layout Utama Jadi Baris (Kiri & Kanan) */
                        .fi-simple-layout {
                            display: flex !important;
                            flex-direction: row !important; /* Wajib Row */
                            height: 100vh !important;
                            width: 100vw !important;
                            max-width: 100% !important;
                            padding: 0 !important;
                            margin: 0 !important;
                        }

                        /* 2. Panel Kiri (Buatan Kita) */
                        .split-screen-left {
                            width: 55%; /* Lebar 55% */
                            background-color: #ffffff;
                            display: flex;
                            align-items: center;
                            justify-content: center; /* Konten di tengah vertikal */
                            padding: 4rem;
                            border-right: 1px solid #f1f5f9;
                            position: relative;
                        }

                        .left-content {
                            width: 100%;
                            max-width: 600px;
                            text-align: left; /* Teks Rata Kiri sesuai SIRAJA */
                        }

                        /* Styling Teks Kiri */
                        .brand-title {
                            font-size: 3.5rem;
                            font-weight: 800;
                            color: #059669; /* Emerald 600 */
                            line-height: 1.1;
                            margin-bottom: 1rem;
                            letter-spacing: -0.5px;
                        }
                        .brand-subtitle {
                            font-size: 1.1rem;
                            font-weight: 600;
                            color: #475569;
                            margin-bottom: 3rem;
                            line-height: 1.6;
                            text-transform: uppercase;
                        }
                        
                        /* Styling Logo Kiri */
                        .brand-logo {
                            height: 180px; /* Ukuran Logo */
                            width: auto;
                            margin-bottom: 3rem;
                        }

                        /* Footer Kiri */
                        .brand-footer p {
                            margin: 0.25rem 0;
                            color: #64748b;
                            font-size: 0.9rem;
                        }
                        .brand-footer .copyright {
                            margin-top: 1.5rem;
                            font-size: 0.8rem;
                            color: #94a3b8;
                        }

                        /* 3. Panel Kanan (Form Filament) - HANCURKAN BATASANNYA */
                        .fi-simple-main {
                            width: 45% !important; /* Sisa lebar */
                            display: flex !important;
                            flex-direction: column !important;
                            justify-content: center !important;
                            align-items: center !important;
                            background: #ffffff !important;
                            padding: 2rem !important;
                            margin: 0 !important;
                            max-width: none !important; /* Hapus batasan max-width */
                        }

                        /* Kontainer Form */
                        .fi-simple-main > div {
                            width: 100%;
                            max-width: 450px; /* Batasi lebar form biar rapi */
                        }

                        /* Sembunyikan Logo Bawaan Filament */
                        .fi-simple-header, .fi-simple-footer { display: none !important; }

                        /* Kartu Login jadi Transparan/Menyatu */
                        .fi-simple-card {
                            box-shadow: none !important;
                            background: transparent !important;
                            padding: 0 !important;
                            border: none !important;
                        }

                        /* Judul Form Login "Masuk ke akun Anda" */
                        .fi-simple-header-heading {
                            font-size: 2rem !important;
                            color: #1e293b !important;
                            text-align: left !important; /* Rata kiri */
                            margin-bottom: 2rem !important;
                        }

                        /* Input Form */
                        .fi-input {
                            padding: 1rem !important;
                            background-color: #f8fafc !important;
                            border: 1px solid #e2e8f0 !important;
                            border-radius: 0.5rem !important;
                        }
                        .fi-input:focus {
                            border-color: #10b981 !important;
                            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
                        }

                        /* Tombol Login */
                        .fi-btn-primary {
                            background-color: #10b981 !important;
                            padding: 0.8rem !important;
                            font-size: 1rem !important;
                            border-radius: 0.5rem !important;
                            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2) !important;
                        }
                        .fi-btn-primary:hover {
                            background-color: #059669 !important;
                        }

                        /* ==================================================
                           ANIMASI
                           ================================================= */
                        @keyframes slideUpFade {
                            from { opacity: 0; transform: translateY(20px); }
                            to { opacity: 1; transform: translateY(0); }
                        }
                        .animate-in { opacity: 0; animation: slideUpFade 0.8s ease-out forwards; }
                        .delay-1 { animation-delay: 0.2s; }
                        .delay-2 { animation-delay: 0.4s; }
                        .delay-3 { animation-delay: 0.6s; }

                        @keyframes floatLogo {
                            0%, 100% { transform: translateY(0); }
                            50% { transform: translateY(-8px); }
                        }
                        .animate-float { animation: floatLogo 4s ease-in-out infinite; }


                        /* ==================================================
                           RESPONSIVE MOBILE (HP)
                           ================================================= */
                        @media (max-width: 1024px) {
                            .fi-simple-layout { flex-direction: column !important; height: auto !important; }
                            .split-screen-left { width: 100% !important; padding: 3rem 1.5rem !important; text-align: center !important; border-right: none; border-bottom: 1px solid #e2e8f0; }
                            .left-content { text-align: center !important; margin: 0 auto; }
                            .brand-title { font-size: 2.5rem !important; }
                            .brand-logo { height: 120px !important; margin-bottom: 2rem !important; }
                            .brand-footer { display: none !important; }
                            .fi-simple-main { width: 100% !important; padding: 3rem 1.5rem !important; }
                            .fi-simple-header-heading { text-align: center !important; }
                        }

                        /* ==================================================
                           CSS DASHBOARD (TIDAK GANGGU LOGIN)
                           ================================================= */
                        /* Aurora Background hanya di Dashboard */
                        .fi-layout body::before { content: ''; position: fixed; width: 60vw; height: 60vw; border-radius: 50%; background: #d1fae5; filter: blur(80px); opacity: 0.5; z-index: -1; top: -10%; left: -10%; }
                        
                        /* Shiny Cards Dashboard */
                        @keyframes shimmer { 0% { background-position: -200% center; } 100% { background-position: 200% center; } }
                        .fi-wi-stats-overview-stat { border: 1px solid rgba(255, 255, 255, 0.5) !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important; position: relative; overflow: hidden; color: white !important; }
                        .fi-wi-stats-overview-stat::before { content: ''; position: absolute; top: 0; left: -150%; width: 150%; height: 100%; background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%); transform: skewX(-25deg); animation: shimmer 3s infinite linear; pointer-events: none; }
                        .fi-wi-stats-overview-stat span, .fi-wi-stats-overview-stat h1, .fi-wi-stats-overview-stat div { color: #ffffff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1); }
                        .fi-wi-stats-overview-stat:nth-child(1) { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
                        .fi-wi-stats-overview-stat:nth-child(2) { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important; }
                        .fi-wi-stats-overview-stat:nth-child(3) { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important; }
                        .fi-wi-stats-overview-stat:nth-child(4) { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; }

                        /* Avatar Dashboard */
                        .fi-user-avatar { background: linear-gradient(135deg, #34d399 0%, #059669 100%) !important; color: #ffffff !important; font-weight: 900 !important; border: 2px solid #ffffff !important; }
                        .fi-dropdown-panel { background: rgba(255, 255, 255, 0.95) !important; margin-top: 10px !important; }
                    </style>
                HTML
            )
            
            // --- 4. WIDGET DASHBOARD (SAPAAN) ---
            ->renderHook(
                PanelsRenderHook::CONTENT_START,
                fn (): string => Blade::render(<<<'HTML'
                    @php
                        $name = auth()->user()->name ?? 'Admin';
                        $initials = collect(explode(' ', $name))->map(fn ($segment) => $segment[0] ?? '')->take(2)->join('');
                        $initials = strtoupper($initials);
                    @endphp

                    <div class="mb-8 mt-2 flex items-center gap-5">
                        <div class="hidden sm:flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-700 shadow-xl shadow-emerald-500/30 text-white text-3xl font-extrabold border-4 border-white/80 transition duration-300 hover:scale-105 drop-shadow-sm">
                            {{ $initials }}
                        </div>
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