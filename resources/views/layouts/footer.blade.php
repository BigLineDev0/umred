<footer class="bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="bg-primary w-10 h-10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-flask text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold ml-3">UMRED</h2>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Plateforme de réservation de laboratoires et d'équipements scientifiques
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Accueil</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Laboratoires</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Réservations</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">À propos</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li>Email: contact@umred.ufr</li>
                        <li>Téléphone: +123 456 789</li>
                        <li>Adresse: 123 Rue de l'Université</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="text-gray-300 mb-4">
                        Abonnez-vous pour recevoir les dernières actualités et mises à jour.
                    </p>
                    <form class="flex">
                        <input type="email" placeholder="Votre email" class="px-4 py-2 w-full rounded-l-lg focus:outline-none">
                        <button class="bg-primary hover:bg-secondary px-4 py-2 rounded-r-lg">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} UMRED. Tous droits réservés. Conçu avec <i class="fas fa-heart text-red-500"></i> pour la recherche scientifique</p>
            </div>
        </div>
    </footer>
