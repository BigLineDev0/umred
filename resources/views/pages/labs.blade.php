@extends('welcome')
@section('title', 'Laboratoires')
<style>
    .lab-bg {
        background: linear-gradient(120deg, rgba(67, 97, 238, 0.95), rgba(60, 55, 201, 0.95)), url('https://images.unsplash.com/photo-1627552245712-3d7f5c7a45d3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80');
        background-size: cover;
        background-position: center;
    }

    .nav-shadow {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .lab-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
        height: auto;
    }

    .lab-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(67, 97, 238, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
        }
    }

    .equipment-count {
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border-radius: 50px;
        padding: 0.3rem 1rem;
        display: inline-flex;
        align-items: center;
    }

    .filter-btn.active {
        background-color: '#4361ee';
        color: white;
        border-color: '#4361ee';
    }
</style>
@section('content')
    <!-- Section Hero Laboratoires -->
    <section class="lab-bg text-white pt-32 pb-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    Nos Laboratoires de recherche
                </h1>
                <p class="text-xl mb-10 max-w-2xl mx-auto">
                    Découvrez nos laboratoires équipés et réservez en ligne en quelques clics
                </p>

                <div class="flex justify-center">
                    <div class="relative w-full max-w-2xl">
                        <input type="text" placeholder="Rechercher un laboratoire..."
                            class="w-full px-6 py-4 rounded-full bg-white/10 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-white pl-12">
                        <i class="fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filtres et tri -->
    <section class="py-8 bg-gray-100">
        <div class="container mx-auto px-4">
            <form method="GET" id="filter-form">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-semibold">{{ $total }} laboratoire{{ $total > 1 ? 's' : '' }}
                            trouvé{{ $total > 1 ? 's' : '' }}</h3>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <!-- Statut -->
                        <div>
                            <label class="mr-2">Statut</label>
                            <select name="statut"
                                class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                onchange="document.getElementById('filter-form').submit();">
                                <option value="">Tous</option>
                                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Disponible
                                </option>
                                <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Indisponible
                                </option>
                            </select>
                        </div>

                        <!-- Localisation -->
                        <div>
                            <label class="mr-2">Localisation</label>
                            <select name="localisation"
                                class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                onchange="document.getElementById('filter-form').submit();">
                                <option value="">Toutes</option>
                                @foreach ($localisations as $loc)
                                    <option value="{{ $loc }}"
                                        {{ request('localisation') == $loc ? 'selected' : '' }}>
                                        {{ $loc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>


    <!-- Liste des Laboratoires -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($laboratoires as $labo)
                    <div class="lab-card bg-white shadow-md">
                        <div class="relative">
                            @if ($labo->photo === null || $labo->photo === '')
                                <img src="{{ asset('images/laboratoires/default.jpg') }}" alt="{{ $labo->nom }}"
                                    class="w-full h-48 object-cover">
                            @else
                                <img src="{{ asset('storage/' . $labo->photo) }}" alt="{{ $labo->nom }}"
                                    class="w-full h-48 object-cover">
                            @endif
                            <div class="absolute top-4 right-4">
                                @if ($labo->statut === 'actif')
                                    <span
                                        class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium flex items-center">
                                        <span class="w-2 h-2 bg-white rounded-full mr-2 pulse"></span> Disponible
                                    </span>
                                @else
                                    <span
                                        class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium flex items-center">
                                        <i class="fas fa-clock mr-2"></i> Indisponible
                                    </span>
                                @endif
                            </div>
                            <div class="absolute bottom-4 left-4">
                                <span class="equipment-count">
                                    <i class="fas fa-microscope mr-2"></i>
                                    {{ $labo->equipements_count }} équipement{{ $labo->equipements_count > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-md font-bold text-dark mb-2">{{ $labo->nom }}</h3>
                            <div class="flex items-center mb-4">
                                <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                <span class="text-gray-600">{{ $labo->localisation }}</span>
                            </div>
                            <p class="text-gray-600 mb-4">
                                {{ \Illuminate\Support\Str::limit($labo->description, 120)  ?? 'Aucune description disponible' }}
                            </p>

                            @if ($labo->statut === 'actif')
                                @auth
                                    <a href="{{ route('reservations.create', $labo->id) }}"
                                        class="w-full bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg text-sm transition block text-center">
                                        Réserver maintenant
                                    </a>
                                @endauth

                                @guest
                                    <a href="{{ route('login') }}"
                                        class="w-full bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm transition block text-center">
                                        Se connecter pour réserver
                                    </a>
                                @endguest
                            @else
                                <button
                                    class="w-full bg-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm cursor-not-allowed"
                                    disabled>
                                    Indisponible actuellement
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $laboratoires->links() }}
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-gradient-to-r from-primary to-secondary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Vous ne trouvez pas ce que vous cherchez ?</h2>
            <p class="text-xl max-w-2xl mx-auto mb-8">
                Notre équipe est à votre disposition pour vous aider à trouver le laboratoire parfait pour vos besoins
            </p>
            <button class="bg-white text-primary hover:bg-gray-100 px-8 py-4 rounded-lg font-medium text-lg transition">
                <i class="fas fa-envelope mr-2"></i> Contacter notre équipe
            </button>
        </div>
    </section>

@endsection
