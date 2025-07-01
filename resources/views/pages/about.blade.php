@extends('welcome')
@section('title', 'Contact')
<style>
        .about-hero {
            background: linear-gradient(120deg, rgba(67, 97, 238, 0.85), rgba(60, 55, 201, 0.9)), url('https://images.unsplash.com/photo-1581091226033-d5c48150dbaa?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
        }
        .nav-shadow {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #4361ee;
        }
        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
@section('content')
<!-- Section Hero À Propos -->
    <section class="about-hero text-white pt-32 pb-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                À Propos d'UMRED
            </h1>
            <p class="text-xl max-w-2xl mx-auto">
                Découvrez notre mission, notre équipe et notre engagement pour la recherche scientifique
            </p>
        </div>
    </section>

    <!-- Notre Histoire -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-dark mb-6">Notre Histoire</h2>
                    <p class="text-gray-600 mb-4">
                        UMRED est né d'un constat simple mais crucial : les laboratoires de recherche de l'UFR Santé de Thiès rencontraient d'importantes difficultés dans la gestion de leurs équipements et salles.
                    </p>
                    <p class="text-gray-600 mb-4">
                        Fondé en 2020 par une équipe de chercheurs passionnés et de développeurs engagés, UMRED s'est donné pour mission de transformer la gestion des ressources scientifiques.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Aujourd'hui, notre plateforme est utilisée par plus de 15 laboratoires et 850 chercheurs, contribuant chaque jour à l'avancement de la recherche scientifique au Sénégal et au-delà.
                    </p>
                </div>
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center text-gray-500">
                    Image historique d'UMRED
                </div>
            </div>
        </div>
    </section>

    <!-- Notre Mission -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Notre Mission</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Transformer la gestion des ressources scientifiques pour une recherche plus efficace
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="value-card bg-white p-8 rounded-xl shadow-sm transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-lightbulb text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-center">Innovation</h3>
                    <p class="text-gray-600 text-center">
                        Développer des solutions technologiques innovantes pour répondre aux défis de la recherche scientifique moderne.
                    </p>
                </div>

                <div class="value-card bg-white p-8 rounded-xl shadow-sm transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-center">Collaboration</h3>
                    <p class="text-gray-600 text-center">
                        Faciliter la collaboration entre chercheurs et institutions pour accélérer les découvertes scientifiques.
                    </p>
                </div>

                <div class="value-card bg-white p-8 rounded-xl shadow-sm transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-center">Optimisation</h3>
                    <p class="text-gray-600 text-center">
                        Optimiser l'utilisation des ressources scientifiques pour maximiser l'impact de la recherche.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Notre Équipe -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Notre Équipe</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Rencontrez les esprits passionnés qui font d'UMRED une réalité
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Membre 1 -->
                <div class="team-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300">
                    <div class="w-full h-64 bg-gray-200 border-2 border-dashed"></div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-dark mb-1">Dr. Alioune Diagne</h3>
                        <p class="text-primary font-medium mb-4">Fondateur & Directeur</p>
                        <p class="text-gray-600 text-sm mb-4">
                            Docteur en biologie moléculaire avec 15 ans d'expérience dans la recherche.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Membre 2 -->
                <div class="team-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300">
                    <div class="w-full h-64 bg-gray-200 border-2 border-dashed"></div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-dark mb-1">Aminata Ndiaye</h3>
                        <p class="text-primary font-medium mb-4">Développeuse Full-Stack</p>
                        <p class="text-gray-600 text-sm mb-4">
                            Spécialiste en développement web avec une passion pour les solutions innovantes.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Membre 3 -->
                <div class="team-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300">
                    <div class="w-full h-64 bg-gray-200 border-2 border-dashed"></div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-dark mb-1">Moussa Fall</h3>
                        <p class="text-primary font-medium mb-4">Responsable Scientifique</p>
                        <p class="text-gray-600 text-sm mb-4">
                            Expert en gestion de laboratoire avec 10 ans d'expérience dans la recherche.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fab fa-researchgate"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Membre 4 -->
                <div class="team-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300">
                    <div class="w-full h-64 bg-gray-200 border-2 border-dashed"></div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-dark mb-1">Fatou Sarr</h3>
                        <p class="text-primary font-medium mb-4">Designer UX/UI</p>
                        <p class="text-gray-600 text-sm mb-4">
                            Créatrice d'expériences utilisateur intuitives et esthétiques.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fab fa-behance"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fab fa-dribbble"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notre Vision -->
    <section class="py-16 bg-gradient-to-r from-primary to-secondary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-6">Notre Vision</h2>
                <p class="text-xl mb-8 max-w-3xl mx-auto">
                    "Devenir la plateforme de référence pour la gestion des ressources scientifiques en Afrique, en favorisant l'excellence dans la recherche et l'innovation."
                </p>
                <div class="bg-white/20 backdrop-blur-sm p-6 rounded-xl">
                    <p class="mb-4">
                        Nous croyons que la recherche scientifique est le moteur du progrès et que chaque chercheur mérite d'avoir accès à des outils performants pour mener à bien ses travaux.
                    </p>
                    <p>
                        Notre ambition est d'étendre notre plateforme à l'ensemble des institutions de recherche d'Afrique de l'Ouest d'ici 2025, créant ainsi un écosystème scientifique connecté et collaboratif.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Nos Valeurs -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Nos Valeurs</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Les principes qui guident chaque décision que nous prenons
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-primary">
                    <h3 class="text-xl font-semibold mb-3">Excellence Scientifique</h3>
                    <p class="text-gray-600">
                        Nous nous engageons à soutenir la recherche de pointe en fournissant des outils qui répondent aux standards internationaux.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <h3 class="text-xl font-semibold mb-3">Intégrité</h3>
                    <p class="text-gray-600">
                        Nous agissons avec transparence et honnêteté dans toutes nos interactions avec nos utilisateurs et partenaires.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-accent">
                    <h3 class="text-xl font-semibold mb-3">Innovation Continue</h3>
                    <p class="text-gray-600">
                        Nous repoussons constamment les limites technologiques pour offrir des solutions toujours plus performantes.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500">
                    <h3 class="text-xl font-semibold mb-3">Collaboration</h3>
                    <p class="text-gray-600">
                        Nous croyons que les plus grandes découvertes naissent de la collaboration entre chercheurs et institutions.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Notre Impact -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Notre Impact</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Comment UMRED transforme la recherche scientifique
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center text-gray-500">
                    Infographie de l'impact
                </div>
                <div>
                    <div class="space-y-8">
                        <div>
                            <div class="flex items-start mb-2">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold">Réduction du temps de réservation</h3>
                                    <p class="text-gray-600">
                                        Les chercheurs passent 70% moins de temps à organiser leurs réservations.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-start mb-2">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-bolt text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold">Optimisation des ressources</h3>
                                    <p class="text-gray-600">
                                        Taux d'utilisation des équipements augmenté de 45% grâce à une meilleure planification.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-start mb-2">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold">Collaboration accrue</h3>
                                    <p class="text-gray-600">
                                        30% d'augmentation des projets collaboratifs entre laboratoires.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-8 md:p-12 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">Prêt à rejoindre la révolution scientifique?</h2>
                <p class="max-w-2xl mx-auto mb-8 opacity-90">
                    Que vous soyez chercheur, étudiant ou institution, UMRED a des solutions pour vous
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button class="bg-white text-primary hover:bg-gray-100 px-8 py-4 rounded-lg font-medium text-lg transition">
                        Créer un compte
                    </button>
                    <button class="bg-transparent border-2 border-white hover:bg-white/10 px-8 py-4 rounded-lg font-medium text-lg transition">
                        Contacter notre équipe
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
