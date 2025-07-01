@extends('welcome')

@section('title', 'Réserver - ' . $laboratoire->nom)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="p-32 pb-16 mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                    <a href="{{ route('laboratoires') }}" class="hover:text-blue-600">Laboratoires</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-900">Réservation</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Réserver {{ $laboratoire->nom }}</h1>
                <p class="text-gray-600 mt-2">{{ $laboratoire->description }}</p>
            </div>

            <!-- Messages d'erreur/succès -->
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST"
                class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                <input type="hidden" name="laboratoire_id" value="{{ $laboratoire->id }}">

                <!-- Formulaire principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Sélection des équipements -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                            Équipements disponibles
                        </h2>

                        @if ($laboratoire->equipements->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($laboratoire->equipements as $equipement)
                                    <label
                                        class="relative flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200">
                                        <input type="checkbox" name="equipements[]" value="{{ $equipement->id }}"
                                            class="sr-only equipement-checkbox" onchange="updateRecap()">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-6 h-6 bg-white border-2 border-gray-300 rounded flex items-center justify-center checkbox-custom">
                                                <svg class="w-4 h-4 text-blue-600 hidden check-icon" fill=""
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <span class="block text-sm font-medium text-gray-900">{{ $equipement->nom }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">Aucun équipement disponible pour ce laboratoire.</p>
                        @endif
                    </div>

                    <!-- Sélection de la date -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Date de réservation
                        </h2>
                        <input type="date" name="date" id="dateInput"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            min="{{ date('Y-m-d') }}" onchange="loadHoraires()" required>
                    </div>

                    <!-- Sélection des horaires -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Créneaux horaires
                        </h2>

                        <div id="horairesContainer" class="text-center text-gray-500 py-8">
                            Sélectionnez d'abord une date pour voir les créneaux disponibles
                        </div>
                    </div>

                    <!-- Commentaire/Objectif -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1.586l-4 4z">
                                </path>
                            </svg>
                            Objectif de la réservation
                        </h2>
                        <textarea name="objectif" id="objectif" rows="4"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="Décrivez l'objectif de votre réservation (optionel)" onchange="updateRecap()" required></textarea>
                        <p class="text-sm text-gray-500 mt-2">Maximum 500 caractères</p>
                    </div>
                </div>

                <!-- Récapitulatif -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Récapitulatif
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-900">Laboratoire</h3>
                                <p class="text-gray-600">{{ $laboratoire->nom }}</p>
                            </div>

                            <div id="recapDate" class="hidden">
                                <h3 class="font-medium text-gray-900">Date</h3>
                                <p class="text-gray-600" id="recapDateValue"></p>
                            </div>

                            <div id="recapEquipements" class="hidden">
                                <h3 class="font-medium text-gray-900">Équipements</h3>
                                <ul class="text-gray-600 text-sm space-y-1" id="recapEquipementsList"></ul>
                            </div>

                            <div id="recapHoraires" class="hidden">
                                <h3 class="font-medium text-gray-900">Créneaux</h3>
                                <ul class="text-gray-600 text-sm space-y-1" id="recapHorairesList"></ul>
                            </div>

                            <div id="recapObjectif" class="hidden">
                                <h3 class="font-medium text-gray-900">Objectif</h3>
                                <p class="text-gray-600 text-sm" id="recapObjectifValue"></p>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn"
                            class="w-full mt-6 bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed"
                            disabled>
                            Confirmer la réservation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup de confirmation -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <!-- Header -->
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 text-center mb-4">
                    🎉 Réservation confirmée !
                </h3>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-800 text-center">
                        <strong>Votre réservation a été enregistrée avec succès !</strong><br>
                        Un email de confirmation vous a été envoyé avec tous les détails.
                    </p>
                </div>

                <!-- Détails de la réservation -->
                <div id="modalReservationDetails" class="space-y-4 mb-6">
                    <!-- Le contenu sera injecté par JavaScript -->
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800">En attente de confirmation</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                Votre réservation est en attente de validation par l'équipe du laboratoire.
                                Vous recevrez un email dès qu'elle sera confirmée.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button onclick="closeModal()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        Fermer
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-center transition-colors duration-200">
                        Voir mes réservations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay de chargement -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 font-medium">Enregistrement en cours...</span>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let horairesDisponibles = [];
        let horairesSelectionnes = [];

        // Charger les horaires disponibles
        async function loadHoraires() {
            const date = document.getElementById('dateInput').value;
            if (!date) return;

            const container = document.getElementById('horairesContainer');
            container.innerHTML =
                '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-500">Chargement des créneaux...</p></div>';

            try {
                const response = await fetch(`/reservations/horaires/{{ $laboratoire->id }}?date=${date}`);
                const horaires = await response.json();

                if (horaires.length === 0) {
                    container.innerHTML =
                        '<div class="text-center text-gray-500 py-8">Aucun créneau disponible pour cette date</div>';
                    return;
                }

                horairesDisponibles = horaires;
                afficherHoraires(horaires);
                updateRecap();
            } catch (error) {
                container.innerHTML =
                    '<div class="text-center text-red-500 py-8">Erreur lors du chargement des créneaux</div>';
            }
        }

        // Afficher les horaires
        function afficherHoraires(horaires) {
            const container = document.getElementById('horairesContainer');

            let html = '<div class="grid grid-cols-2 gap-3">';
            horaires.forEach((horaire, index) => {
                html += `
            <label class="relative flex items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200 horaire-item">
                <input type="checkbox" class="sr-only horaire-checkbox"
                       data-debut="${horaire.debut}"
                       data-fin="${horaire.fin}"
                       onchange="toggleHoraire(this)">
                <div class="text-center">
                    <div class="font-medium text-gray-900">${horaire.debut} - ${horaire.fin}</div>
                </div>
            </label>
        `;
            });
            html += '</div>';

            container.innerHTML = html;
        }

        // Toggle horaire
        function toggleHoraire(checkbox) {
            const debut = checkbox.dataset.debut;
            const fin = checkbox.dataset.fin;
            const label = checkbox.closest('.horaire-item');

            if (checkbox.checked) {
                label.classList.add('border-blue-500', 'bg-blue-50');
                horairesSelectionnes.push({
                    debut,
                    fin
                });
            } else {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                horairesSelectionnes = horairesSelectionnes.filter(h => h.debut !== debut || h.fin !== fin);
            }

            updateRecap();
        }

        // Mettre à jour le récapitulatif
        function updateRecap() {
            // Date
            const date = document.getElementById('dateInput').value;
            const recapDate = document.getElementById('recapDate');
            const recapDateValue = document.getElementById('recapDateValue');

            if (date) {
                recapDate.classList.remove('hidden');
                const dateObj = new Date(date);
                recapDateValue.textContent = dateObj.toLocaleDateString('fr-FR', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } else {
                recapDate.classList.add('hidden');
            }

            // Équipements
            const equipements = document.querySelectorAll('.equipement-checkbox:checked');
            const recapEquipements = document.getElementById('recapEquipements');
            const recapEquipementsList = document.getElementById('recapEquipementsList');

            if (equipements.length > 0) {
                recapEquipements.classList.remove('hidden');
                recapEquipementsList.innerHTML = '';
                equipements.forEach(eq => {
                    const label = eq.closest('label').querySelector('span');
                    const li = document.createElement('li');
                    li.textContent = `• ${label.textContent}`;
                    recapEquipementsList.appendChild(li);
                });
            } else {
                recapEquipements.classList.add('hidden');
            }

            // Horaires
            const recapHoraires = document.getElementById('recapHoraires');
            const recapHorairesList = document.getElementById('recapHorairesList');

            if (horairesSelectionnes.length > 0) {
                recapHoraires.classList.remove('hidden');
                recapHorairesList.innerHTML = '';
                horairesSelectionnes.forEach(h => {
                    const li = document.createElement('li');
                    li.textContent = `• ${h.debut} - ${h.fin}`;
                    recapHorairesList.appendChild(li);
                });
            } else {
                recapHoraires.classList.add('hidden');
            }

            // Objectif
            const objectif = document.getElementById('objectif').value;
            const recapObjectif = document.getElementById('recapObjectif');
            const recapObjectifValue = document.getElementById('recapObjectifValue');

            if (objectif.trim()) {
                recapObjectif.classList.remove('hidden');
                recapObjectifValue.textContent = objectif.length > 100 ? objectif.substring(0, 100) + '...' : objectif;
            } else {
                recapObjectif.classList.add('hidden');
            }

            // Bouton submit
            const submitBtn = document.getElementById('submitBtn');
            const canSubmit = date && equipements.length > 0 && horairesSelectionnes.length > 0 && objectif.trim();
            submitBtn.disabled = !canSubmit;
        }

        // Fonction pour réinitialiser le formulaire après une réservation réussie
        function resetFormAfterSuccess() {
            // Réinitialiser le formulaire
            document.getElementById('reservationForm').reset();

            // Réinitialiser les équipements
            const equipementCheckboxes = document.querySelectorAll('.equipement-checkbox');
            equipementCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                const customCheckbox = checkbox.closest('label').querySelector('.checkbox-custom');
                const checkIcon = customCheckbox.querySelector('.check-icon');
                customCheckbox.classList.remove('bg-blue-600', 'border-blue-600');
                checkIcon.classList.add('hidden');
            });

            // Réinitialiser les horaires
            horairesSelectionnes = [];
            const horaireItems = document.querySelectorAll('.horaire-item');
            horaireItems.forEach(item => {
                const checkbox = item.querySelector('.horaire-checkbox');
                if (checkbox) {
                    checkbox.checked = false;
                    item.classList.remove('border-blue-500', 'bg-blue-50');
                }
            });

            // Vider le container des horaires
            const horairesContainer = document.getElementById('horairesContainer');
            horairesContainer.innerHTML =
                '<div class="text-center text-gray-500 py-8">Sélectionnez d\'abord une date pour voir les créneaux disponibles</div>';

            // Réinitialiser le textarea objectif
            document.getElementById('objectif').value = '';

            // Réinitialiser la date
            document.getElementById('dateInput').value = '';

            // Masquer tous les éléments du récapitulatif
            document.getElementById('recapDate').classList.add('hidden');
            document.getElementById('recapEquipements').classList.add('hidden');
            document.getElementById('recapHoraires').classList.add('hidden');
            document.getElementById('recapObjectif').classList.add('hidden');

            // Désactiver le bouton de soumission
            document.getElementById('submitBtn').disabled = true;
        }

        // Gestion des checkboxes personnalisées
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('equipement-checkbox')) {
                const checkbox = e.target.closest('label').querySelector('.checkbox-custom');
                const checkIcon = checkbox.querySelector('.check-icon');

                if (e.target.checked) {
                    checkbox.classList.add('bg-blue-600', 'border-blue-600');
                    checkIcon.classList.remove('hidden');
                } else {
                    checkbox.classList.remove('bg-blue-600', 'border-blue-600');
                    checkIcon.classList.add('hidden');
                }
            }
        });

        // Soumission du formulaire
        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Vérifier que tous les champs sont remplis
            const date = document.getElementById('dateInput').value;
            const equipements = document.querySelectorAll('.equipement-checkbox:checked');
            const objectif = document.getElementById('objectif').value;

            if (!date || equipements.length === 0 || horairesSelectionnes.length === 0 || !objectif.trim()) {
                alert('Veuillez remplir tous les champs obligatoires.');
                return;
            }

            // Afficher le loading
            document.getElementById('loadingOverlay').classList.remove('hidden');

            // Préparer les données
            const formData = new FormData(this);

            // Ajouter les horaires sélectionnés
            horairesSelectionnes.forEach((horaire, index) => {
                formData.append(`horaires[${index}][debut]`, horaire.debut);
                formData.append(`horaires[${index}][fin]`, horaire.fin);
            });

            // Ajouter le header pour indiquer que c'est une requête AJAX
            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Masquer le loading
                    document.getElementById('loadingOverlay').classList.add('hidden');

                    if (data.success) {
                        // Afficher le popup de confirmation (qui va automatiquement réinitialiser le formulaire)
                        showConfirmationModal(data.reservation);
                    } else {
                        // Afficher l'erreur
                        alert(data.message || 'Une erreur est survenue');
                    }
                })
                .catch(error => {
                    // Masquer le loading
                    document.getElementById('loadingOverlay').classList.add('hidden');

                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de l\'enregistrement');
                });
        });

        // Fonction pour afficher le modal de confirmation
        function showConfirmationModal(reservation) {
            const modalDetails = document.getElementById('modalReservationDetails');

            modalDetails.innerHTML = `
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-900 mb-3">📋 Détails de votre réservation</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Numéro :</span>
                    <span class="text-gray-900">#${reservation.numero}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Statut :</span>
                    <span class="text-yellow-600 font-medium">${reservation.statut}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="font-medium text-gray-700">Laboratoire :</span>
                    <span class="text-gray-900">${reservation.laboratoire}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="font-medium text-gray-700">Date :</span>
                    <span class="text-gray-900">${reservation.date}</span>
                </div>
            </div>

            <div class="mt-4">
                <span class="font-medium text-gray-700">Créneaux horaires :</span>
                <ul class="mt-1 text-gray-900">
                    ${reservation.horaires.map(h => `<li class="text-sm">• ${h}</li>`).join('')}
                </ul>
            </div>

            <div class="mt-4">
                <span class="font-medium text-gray-700">Équipements :</span>
                <ul class="mt-1 text-gray-900">
                    ${reservation.equipements.map(e => `<li class="text-sm">• ${e}</li>`).join('')}
                </ul>
            </div>

            <div class="mt-4">
                <span class="font-medium text-gray-700">Objectif :</span>
                <p class="text-gray-900 text-sm mt-1 bg-white p-2 rounded border">${reservation.objectif}</p>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-200">
                <span class="text-xs text-gray-500">Réservation effectuée le ${reservation.created_at}</span>
            </div>
        </div>
    `;

            // Afficher le modal
            document.getElementById('confirmationModal').classList.remove('hidden');

            // Réinitialiser le formulaire après avoir affiché le modal
            resetFormAfterSuccess();
        }

        // Fonction pour fermer le modal
        function closeModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Fermer le modal avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.keyCode === 27) { // Escape key
                closeModal();
            }
        });
    </script>
@endsection
