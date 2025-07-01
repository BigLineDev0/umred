@extends('welcome')

@section('title', 'Accueil')
<style>
    .hero-bg {
        background: linear-gradient(120deg, rgba(67, 97, 238, 0.85), rgba(60, 55, 201, 0.9)), url('https://images.unsplash.com/photo-1581094794329-efd4c4d495d9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80');
        background-size: cover;
        background-position: center;
    }

    .nav-shadow {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .lab-card {
        transition: all 0.3s ease;
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

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
</style>
@section('content')
    <!-- Section Hero -->
    <section class="hero-bg text-white pt-32 pb-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    Réservez votre laboratoire scientifique en toute simplicité
                </h1>
                <p class="text-xl mb-10 max-w-2xl mx-auto">
                    UMRED offre une plateforme moderne pour la réservation de laboratoires et d'équipements scientifiques
                    pour chercheurs et étudiants.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('laboratoires') }}"
                        class="bg-white text-primary hover:bg-gray-100 px-8 py-4 rounded-lg font-medium text-lg transition">
                        <i class="fas fa-flask mr-2"></i> Voir les laboratoires
                    </a>
                    <button
                        class="bg-transparent border-2 border-white hover:bg-white/10 px-8 py-4 rounded-lg font-medium text-lg transition">
                        <i class="fas fa-play-circle mr-2"></i> Découvrir la plateforme
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistiques -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm text-center">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $stats['total_laboratories'] }}</div>
                    <div class="text-gray-600 font-medium">Laboratoires</div>
                </div>
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm text-center">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $stats['total_equipments'] }}</div>
                    <div class="text-gray-600 font-medium">Équipements</div>
                </div>
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm text-center">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $stats['total_users'] }}</div>
                    <div class="text-gray-600 font-medium">Utilisateurs</div>
                </div>
                <div class="stat-card bg-white p-6 rounded-xl shadow-sm text-center">
                    <div class="text-4xl font-bold text-primary mb-2">24/7</div>
                    <div class="text-gray-600 font-medium">Disponibilité</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Laboratoires -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Nos Laboratoires Disponibles</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Chaque laboratoire est équipé d'un nombre spécifique d'appareils scientifiques
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($laboratoires as $labo)
                    <div class="lab-card bg-white shadow-md">
                        <div class="relative">
                            @if ($labo->photo === null || $labo->photo === '')
                                <img src="{{ asset('images/laboratoires/default.jpg') }}" alt="{{ $labo->nom }}" class="w-full h-48 object-cover">
                            @else
                                <img src="{{ asset('storage/' . $labo->photo) }}" alt="{{ $labo->nom }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="absolute top-4 right-4">
                                @if($labo->statut === 'actif')
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium flex items-center">
                                        <span class="w-2 h-2 bg-white rounded-full mr-2 pulse"></span> Disponible
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium flex items-center">
                                        <i class="fas fa-clock mr-2"></i> Indisponible
                                    </span>
                                @endif
                            </div>
                            <div class="absolute bottom-4 left-4">
                                <span class="equipment-count">
                                    <i class="fas fa-microscope mr-2"></i>
                                    {{ $labo->equipements_count }} équipement{{ $labo->equipements_count > 1 ? 's' : ''}}
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
                                {{ \Illuminate\Support\Str::limit($labo->description, 120) }}
                            </p>

                            @if($labo->statut === 'actif')
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
                                <button class="w-full bg-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm cursor-not-allowed" disabled>
                                    Indisponible actuellement
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('laboratoires') }}"
                    class="bg-white border-2 border-primary text-primary hover:bg-primary hover:text-white px-6 py-3 rounded-lg font-medium transition">
                    Voir tous les laboratoires
                </a>
            </div>
        </div>
    </section>

    <!-- Fonctionnalités -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Fonctionnalités de la Plateforme</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Découvrez comment UMRED simplifie la gestion de vos réservations de laboratoires
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card bg-white p-8 rounded-xl shadow-sm transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-check text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Réservation en Ligne</h3>
                    <p class="text-gray-600 mb-4">
                        Réservez vos laboratoires et équipements en quelques clics, 24h/24 et 7j/7, avec un calendrier
                        interactif.
                    </p>
                </div>

                <div class="feature-card bg-white p-8 rounded-xl shadow-sm transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-bell text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Notifications Intelligentes</h3>
                    <p class="text-gray-600 mb-4">
                        Recevez des alertes par email et SMS pour vos réservations, rappels et disponibilités de dernière
                        minute.
                    </p>
                </div>

                <div class="feature-card bg-white p-8 rounded-xl shadow-sm transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Suivi des Équipements</h3>
                    <p class="text-gray-600 mb-4">
                        Visualisez l'état et l'historique d'utilisation de tous les équipements en temps réel.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- À propos (résumé) -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div
                    class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center text-gray-500">
                    Image ou vidéo de présentation
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-dark mb-6">À Propos d'UMRED</h2>
                    <p class="text-gray-600 mb-4">
                        UMRED est une plateforme innovante développée pour répondre aux besoins de gestion des laboratoires
                        de recherche de l'UFR Santé de Thiès.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Notre mission est de simplifier la réservation des équipements et des salles, d'optimiser
                        l'utilisation des ressources et d'améliorer la traçabilité des équipements scientifiques.
                    </p>
                    <a href="#"
                        class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition">
                        <i class="fas fa-book-open mr-2"></i> En savoir plus
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages -->
    <section class="py-16 bg-gradient-to-r from-primary to-secondary text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Ce que disent nos utilisateurs</h2>
                <p class="max-w-2xl mx-auto opacity-90">
                    Découvrez les témoignages des chercheurs et étudiants qui utilisent notre plateforme
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-200 border-2 border-dashed"></div>
                        <div class="ml-4">
                            <h4 class="font-bold">Dr. Aminata Diop</h4>
                            <p class="text-sm opacity-80">Chercheuse en Biologie</p>
                        </div>
                    </div>
                    <p class="opacity-90">
                        "UMRED a révolutionné notre façon de gérer les réservations de laboratoire. Plus de conflits
                        d'horaires et un gain de temps considérable!"
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-200 border-2 border-dashed"></div>
                        <div class="ml-4">
                            <h4 class="font-bold">Moussa Diallo</h4>
                            <p class="text-sm opacity-80">Étudiant en Master</p>
                        </div>
                    </div>
                    <p class="opacity-90">
                        "La plateforme est intuitive et facile à utiliser. Je peux réserver le matériel dont j'ai besoin en
                        quelques clics seulement."
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-200 border-2 border-dashed"></div>
                        <div class="ml-4">
                            <h4 class="font-bold">Saliou Ndiaye</h4>
                            <p class="text-sm opacity-80">Technicien de laboratoire</p>
                        </div>
                    </div>
                    <p class="opacity-90">
                        "Grâce au suivi des équipements, je peux planifier les maintenances plus efficacement. Un outil
                        indispensable pour notre travail."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-8 md:p-12 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">Prêt à simplifier vos réservations?</h2>
                <p class="max-w-2xl mx-auto mb-8 opacity-90">
                    Rejoignez la communauté UMRED et profitez d'une gestion optimisée de vos laboratoires
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button
                        class="bg-white text-primary hover:bg-gray-100 px-8 py-4 rounded-lg font-medium text-lg transition">
                        Créer un compte
                    </button>
                    <button
                        class="bg-transparent border-2 border-white hover:bg-white/10 px-8 py-4 rounded-lg font-medium text-lg transition">
                        Contacter notre équipe
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact (résumé) -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Contactez-nous</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Vous avez des questions ou besoin d'assistance? Notre équipe est là pour vous aider.
                </p>
            </div>

            <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold mb-6">Informations de contact</h3>
                        <div class="space-y-4">
                            <div class="flex">
                                <div class="mr-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium">Adresse</h4>
                                    <p class="text-gray-600">UFR Santé, Thiès, Sénégal</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="mr-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-phone-alt text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium">Téléphone</h4>
                                    <p class="text-gray-600">+221 33 123 45 67</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="mr-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium">Email</h4>
                                    <p class="text-gray-600">contact@umred.sn</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold mb-6">Envoyez-nous un message</h3>
                        <form>
                            <div class="mb-4">
                                <input type="text" placeholder="Votre nom"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div class="mb-4">
                                <input type="email" placeholder="Votre email"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div class="mb-4">
                                <textarea placeholder="Votre message" rows="4"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                            </div>
                            <button type="submit"
                                class="w-full bg-primary hover:bg-secondary text-white py-3 rounded-lg font-medium transition">
                                Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
