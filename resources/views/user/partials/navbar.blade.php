<nav x-data="{ mobileMenuOpen: false }" class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-emerald-100 shadow-sm h-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
        <div class="flex justify-between items-center h-full">
            
            <div class="flex items-center gap-3">
                <div class="relative w-12 h-12 flex-shrink-0">
                    <img src="{{ asset('avatar/logoweb.png') }}" 
                         alt="Logo" 
                         class="w-full h-full object-contain drop-shadow-md"
                         onerror="this.style.display='none'; document.getElementById('backup-icon').style.display='flex';">
                    
                    <div id="backup-icon" class="hidden w-full h-full items-center justify-center bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                        <i class="fas fa-shield-heart text-white text-xl"></i>
                    </div>
                </div>
                
                <div class="flex flex-col justify-center">
                    <span class="font-black text-2xl text-slate-800 tracking-tight leading-none">
                        Siaga<span class="text-emerald-500">Bencana</span>
                    </span>
                    <span class="text-[0.65rem] text-slate-400 uppercase tracking-[0.3em] font-bold mt-1">
                        Pusat Belajar
                    </span>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <a href="{{ route('home') }}" 
                   class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors rounded-xl hover:bg-emerald-50">
                    Beranda
                </a>
                
                <div class="px-5 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-100/50 rounded-xl border border-emerald-100">
                    Pusat Belajar
                </div>
                
                @auth
                    <a href="{{ url('/admin') }}" class="ml-2 px-6 py-2.5 bg-slate-900 text-white rounded-full text-xs font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                        ADMIN PANEL
                    </a>
                @endauth
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-3 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         @click.away="mobileMenuOpen = false"
         x-transition
         class="absolute top-24 left-0 w-full bg-white border-b border-gray-100 shadow-xl sm:hidden p-4 flex flex-col gap-2">
        
        <a href="{{ route('home') }}" class="block px-4 py-3 text-slate-600 font-bold hover:bg-emerald-50 rounded-xl transition">
            Beranda
        </a>
        <div class="block px-4 py-3 text-emerald-600 font-bold bg-emerald-50 rounded-xl">
            Pusat Belajar
        </div>
        
        @auth
            <a href="{{ url('/admin') }}" class="block px-4 py-3 mt-2 bg-slate-900 text-white font-bold rounded-xl text-center">
                Ke Dashboard Admin
            </a>
        @endauth
    </div>
</nav>

<script src="//unpkg.com/alpinejs" defer></script>