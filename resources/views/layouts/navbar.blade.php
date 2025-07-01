<nav class="fixed w-full bg-white z-50 nav-shadow">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('assets/img/logo/UMRED.png') }}" alt="UMRED Logo" class="h-10 w-auto">
                </a>
            </div>

            <!-- Menu principal -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-primary font-medium border-b-2 border-primary pb-1' : 'hover:text-primary transition' }}">Accueil</a>
                <a href="{{ route('laboratoires') }}" class="{{ request()->routeIs('laboratoires') ? 'text-primary font-medium border-b-2 border-primary pb-1' : 'hover:text-primary transition' }}">Laboratoires</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-primary font-medium border-b-2 border-primary pb-1' : 'hover:text-primary transition' }}">À propos</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-primary font-medium border-b-2 border-primary pb-1' : 'hover:text-primary transition' }}">Contact</a>

                @auth
                    <!-- Tableau de bord - visible uniquement si connecté -->
                    <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-2 rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-chart-line mr-2"></i>Tableau de bord
                    </a>
                @endauth
            </div>

            <!-- Section droite -->
            <div class="flex items-center space-x-4">
                @guest
                    <!-- Boutons connexion/inscription - visible si déconnecté -->
                    <a href="{{ route('login') }}" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                    </a>
                    <a href="{{ route('register') }}" class="bg-white border-2 border-primary text-primary hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-user-plus mr-2"></i>Inscription
                    </a>
                @else
                    <!-- Profil utilisateur - visible si connecté -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 bg-gray-50 hover:bg-gray-100 px-4 py-2 rounded-lg transition-all duration-200 border border-gray-200 shadow-sm">
                            <!-- Avatar avec initiales -->
                            <div class="w-8 h-8 bg-gradient-to-br from-primary to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                 {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <!-- Nom et prénom -->
                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-semibold text-gray-900">
                                   {{ Auth::user()->prenom }} {{ Auth::user()->name }}
                                </div>
                            </div>
                            <!-- Icône dropdown -->
                            <i class="fas fa-chevron-down text-gray-400 text-xs transform transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Menu déroulant -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 origin-top-right z-50">

                            <div class="py-2">

                                <!-- Options du menu -->
                                <a href="{{ route('profile.edit') ?? '#' }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                    <i class="fas fa-user w-5 text-gray-400 mr-3"></i>
                                    <span>Mon profil</span>
                                </a>

                                <div class="border-t border-gray-100 mt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50 transition">
                                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                            <span>Se déconnecter</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest

                <!-- Menu mobile -->
                <button class="md:hidden text-gray-600" @click="mobileMenuOpen = !mobileMenuOpen">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Menu mobile -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white border-t border-gray-200">
            <div class="px-4 py-4 space-y-4">
                <a href="{{ route('home') }}" class="block text-gray-700 hover:text-primary transition">Accueil</a>
                <a href="{{ route('laboratoires') }}" class="block text-gray-700 hover:text-primary transition">Laboratoires</a>
                <a href="{{ route('about') }}" class="block text-gray-700 hover:text-primary transition">À propos</a>
                <a href="{{ route('contact') }}" class="block text-gray-700 hover:text-primary transition">Contact</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="block bg-primary text-white px-4 py-2 rounded-lg font-semibold">
                        <i class="fas fa-chart-line mr-2"></i>Tableau de bord
                    </a>
                @endauth

                @guest
                    <div class="space-y-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('login') }}" class="block bg-primary text-white px-4 py-2 rounded-lg text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                        </a>
                        <a href="{{ route('register') }}" class="block border-2 border-primary text-primary px-4 py-2 rounded-lg text-center">
                            <i class="fas fa-user-plus mr-2"></i>Inscription
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- Styles CSS additionnels --}}
<style>
    .nav-shadow {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>

{{-- Script Alpine.js pour les interactions --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbar', () => ({
            mobileMenuOpen: false
        }))
    })
</script>
