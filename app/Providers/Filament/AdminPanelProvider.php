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
            
            // --- SETTING DASAR ---
            ->darkMode(false)
            // UPDATE JALUR FAVICON
            ->favicon(asset('avatar/foto/logoweb.png'))
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->font('Outfit')
            
            // UPDATE JALUR LOGO SIDEBAR
            ->brandName(new HtmlString('
                <div class="flex items-center gap-3 font-bold text-xl tracking-tight">
                    <img src="'.asset('avatar/foto/logoweb.png').'" alt="Logo" class="h-8 w-auto">
                    <span class="text-emerald-600">Siaga</span><span class="text-slate-700">Bencana</span>
                </div>
            '))
            
            ->sidebarCollapsibleOnDesktop()
            ->spa()

            // --- 1. VISUAL CARD (BACKGROUND + BINTANG JATUH) ---
            ->renderHook(
                PanelsRenderHook::SIMPLE_PAGE_START,
                fn (): string => Blade::render(<<<'HTML'
                    @if (request()->routeIs('filament.admin.auth.login'))
                        <div class="login-backdrop">
                            <div class="login-card-wide">
                                
                                {{-- KIRI: HIJAU + ANIMASI --}}
                                <div class="side-branding">
                                    <div class="shooting-stars">
                                        <span></span><span></span><span></span><span></span><span></span>
                                    </div>
                                    
                                    <div class="branding-content">
                                        <div class="logo-static-container">
                                            {{-- UPDATE JALUR LOGO LOGIN --}}
                                            <img src="{{ asset('avatar/foto/logoweb.png') }}" class="brand-img">
                                        </div>
                                        <h2 class="brand-heading">Selamat Datang!</h2>
                                        <p class="brand-sub">
                                            Sistem Informasi & Edukasi<br>
                                            <strong>Siaga Bencana Alam</strong>
                                        </p>
                                        <div class="brand-badge">BPBD ACEH</div>
                                    </div>
                                </div>

                                {{-- KANAN: PUTIH KOSONG (Tempat Form) --}}
                                <div class="side-form-spacer"></div>
                            </div>
                        </div>
                    @endif
                HTML)
            )

            // --- 2. CSS FINAL (DENGAN ISOLASI HALAMAN LOGIN) ---
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render(<<<'HTML'
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&display=swap');
                        html, body { font-family: 'Outfit', sans-serif !important; }
                    </style>

                    @if (request()->routeIs('filament.admin.auth.login'))
                    <style>
                        html, body {
                            background-color: #f1f5f9 !important;
                            height: 100vh; width: 100vw; overflow: hidden; margin: 0; padding: 0;
                        }

                        /* 1. CONTAINER BACKGROUND */
                        .login-backdrop {
                            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                            display: flex; align-items: center; justify-content: center;
                            z-index: 0;
                        }

                        .login-card-wide {
                            width: 1200px; height: 680px;
                            background: white;
                            border-radius: 24px;
                            box-shadow: 0 40px 80px -20px rgba(0,0,0,0.15);
                            display: flex; overflow: hidden;
                            position: relative;
                        }

                        /* SISI KIRI (HIJAU) */
                        .side-branding {
                            width: 50%; height: 100%;
                            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                            position: relative; 
                            display: flex; flex-direction: column; align-items: center; justify-content: center;
                            text-align: center; padding: 3rem;
                            overflow: hidden;
                        }
                        
                        .branding-content { position: relative; z-index: 10; }

                        /* BINTANG JATUH */
                        .shooting-stars span {
                            position: absolute; top: 50%; left: 50%; width: 4px; height: 4px;
                            background: #fff; border-radius: 50%;
                            box-shadow: 0 0 0 4px rgba(255,255,255,0.1), 0 0 0 8px rgba(255,255,255,0.1), 0 0 20px rgba(255,255,255,1);
                            animation: animate 3s linear infinite;
                        }
                        .shooting-stars span::before {
                            content: ''; position: absolute; top: 50%; transform: translateY(-50%); width: 300px; height: 1px;
                            background: linear-gradient(90deg, #fff, transparent);
                        }
                        @keyframes animate {
                            0% { transform: rotate(315deg) translateX(0); opacity: 1; }
                            70% { opacity: 1; }
                            100% { transform: rotate(315deg) translateX(-1000px); opacity: 0; }
                        }
                        .shooting-stars span:nth-child(1) { top: 0; right: 0; left: initial; animation-delay: 0s; animation-duration: 1s; }
                        .shooting-stars span:nth-child(2) { top: 0; right: 80px; left: initial; animation-delay: 0.2s; animation-duration: 3s; }
                        .shooting-stars span:nth-child(3) { top: 80px; right: 0px; left: initial; animation-delay: 0.4s; animation-duration: 2s; }
                        .shooting-stars span:nth-child(4) { top: 0; right: 180px; left: initial; animation-delay: 0.6s; animation-duration: 1.5s; }
                        .shooting-stars span:nth-child(5) { top: 0; right: 400px; left: initial; animation-delay: 0.8s; animation-duration: 2.5s; }

                        /* LOGO & TEKS */
                        .brand-img { height: 120px; width: auto; filter: drop-shadow(0 8px 15px rgba(0,0,0,0.15)); }
                        .logo-static-container { margin-bottom: 2rem; display: inline-block; }
                        
                        .brand-heading { font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem; line-height: 1.2; text-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                        .brand-sub { font-size: 1.1rem; color: #ecfdf5; margin-bottom: 3rem; line-height: 1.6; }
                        .brand-badge { background: rgba(255,255,255,0.25); padding: 8px 24px; border-radius: 50px; font-size: 0.9rem; font-weight: 700; color: white; border: 1px solid rgba(255,255,255,0.3); }

                        /* SISI KANAN (PUTIH) */
                        .side-form-spacer { width: 50%; height: 100%; background: white; }

                        /* 2. FORM LOGIN (OVERLAY KANAN) */
                        .fi-simple-layout {
                            position: fixed !important; top: 0; left: 0; width: 100%; height: 100%;
                            z-index: 50; background: transparent !important; pointer-events: none; 
                            display: flex !important; justify-content: center; align-items: center;
                        }

                        .fi-simple-main {
                            pointer-events: auto; position: absolute !important;
                            width: 1200px !important; height: 680px !important;
                            display: flex !important; justify-content: flex-end !important; align-items: center !important;
                            padding: 0 !important; background: transparent !important;
                        }

                        .fi-simple-main > div {
                            width: 50% !important; height: 100%;
                            display: flex; flex-direction: column; justify-content: center;
                            padding: 5rem !important; background: transparent !important;
                        }

                        /* INPUT FORM & TOMBOL */
                        .fi-simple-header-heading {
                            display: block !important;
                            color: #111827 !important; font-size: 2.2rem !important; font-weight: 800 !important;
                            text-align: left !important; margin-bottom: 2rem !important;
                        }

                        .fi-input {
                            display: block !important; visibility: visible !important; opacity: 1 !important;
                            background-color: #f3f4f6 !important; 
                            border: 2px solid #e5e7eb !important; 
                            color: #000000 !important; 
                            padding: 16px !important; border-radius: 12px !important; font-size: 1.05rem !important;
                            margin-top: 8px !important; width: 100% !important; box-shadow: none !important;
                        }
                        
                        .fi-input:focus { background-color: #ffffff !important; border-color: #10b981 !important; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important; outline: none !important; }
                        .fi-simple-main label span { color: #374151 !important; font-weight: 700 !important; font-size: 0.95rem !important; }

                        .fi-btn-primary {
                            width: 100% !important; background-color: #10b981 !important;
                            border-radius: 12px !important; padding: 16px !important;
                            font-weight: 700 !important; font-size: 1.2rem !important;
                            box-shadow: 0 8px 20px -5px rgba(16, 185, 129, 0.4) !important;
                            margin-top: 1.5rem !important; color: white !important;
                        }
                        .fi-btn-primary:hover { background-color: #059669 !important; transform: translateY(-2px); }

                        .fi-simple-header, .fi-simple-footer { display: none !important; }
                        .fi-simple-card { background: transparent !important; box-shadow: none !important; border: none !important; padding: 0 !important; }

                        /* MOBILE RESPONSIVE (HP) */
                        @media (max-width: 1250px) {
                            .login-card-wide { flex-direction: column; width: 90%; height: auto; min-height: 800px; }
                            .side-branding { width: 100%; height: 300px; padding: 2rem; }
                            .brand-img { height: 90px; }
                            .side-form-spacer { display: none; }
                            
                            .fi-simple-main { width: 90% !important; height: auto !important; justify-content: center !important; margin-top: -400px !important; }
                            .fi-simple-main > div { width: 100% !important; padding: 2rem !important; }
                            .fi-simple-header-heading { text-align: center !important; }
                        }
                    </style>
                    @endif
                HTML)
            )
            
            // --- SISA SETTING DASHBOARD ---
            ->renderHook(PanelsRenderHook::CONTENT_START, fn(): string => Blade::render(<<<'HTML'
                @php $initials = strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)); @endphp
                <div class="mb-8 mt-2 flex items-center gap-5">
                    <div class="hidden sm:flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-700 shadow-xl shadow-emerald-500/30 text-white text-3xl font-extrabold border-4 border-white/80 transition duration-300 hover:scale-105">
                        {{ $initials }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">{{ auth()->user()->name ?? 'Admin' }}</span>! 👋</h2>
                        <p class="text-slate-500 font-medium mt-1 text-lg">Selamat datang di dashboard <span class="font-bold text-emerald-600">SiagaBencana</span>.</p>
                    </div>
                </div>
            HTML))

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([Pages\Dashboard::class])
            ->widgets([StatsOverview::class])
            ->middleware([
                EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, AuthenticateSession::class, ShareErrorsFromSession::class, VerifyCsrfToken::class, SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}