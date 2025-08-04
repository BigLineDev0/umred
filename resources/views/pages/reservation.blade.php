@extends('welcome')

@section('title', 'Réserver - ' . $laboratoire->nom)

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-800 py-20 md:py-28">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Réserver le {{ $laboratoire->nom }}</h1>
                {{-- <p class="text-lg text-blue-100 max-w-3xl mx-auto">{{ $laboratoire->description }}</p> --}}
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-12 bg-white transform skew-y-1 -mb-6"></div>
    </div>

    <!-- Reservation Form -->
    <div class="bg-gray-50 py-12" id="reservation-form">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <div class="md:flex">
                    <!-- Form Section -->
                    <div class="md:w-2/3 p-6 sm:p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Formulaire de réservation</h2>

                        @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="laboratoire_id" value="{{ $laboratoire->id }}">

                            <!-- Equipments Section -->
                            <div class="space-y-2">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                    Équipements disponibles
                                </h3>
                                @if ($laboratoire->equipements->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($laboratoire->equipements as $equipement)
                                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors duration-200">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="equipements[]" value="{{ $equipement->id }}" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded equipement-checkbox" onchange="updateRecap()">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <span class="font-medium text-gray-900">{{ $equipement->nom }}</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-gray-500 text-center py-8">Aucun équipement disponible pour ce laboratoire.</p>
                                @endif
                            </div>

                            <!-- Date Selection -->
                            <div class="space-y-2">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Date de réservation
                                </h3>
                                <input type="date" name="date" id="dateInput" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="{{ date('Y-m-d') }}" onchange="loadHoraires()" required>
                            </div>

                            <!-- Time Slots -->
                            <div class="space-y-2">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Créneaux horaires
                                </h3>
                                <div id="horairesContainer" class="text-center text-gray-500 py-8 bg-gray-50 rounded-lg">
                                    Sélectionnez d'abord une date pour voir les créneaux disponibles
                                </div>
                            </div>

                            <!-- Purpose -->
                            <div class="space-y-2">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1.586l-4 4z"></path>
                                    </svg>
                                    Objectif de la réservation
                                </h3>
                                <textarea name="objectif" id="objectif" rows="4" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Décrivez l'objectif de votre réservation (optionnel)" onchange="updateRecap()"></textarea>
                                <p class="text-sm text-gray-500">Maximum 500 caractères</p>
                            </div>
                        </form>
                    </div>

                    <!-- Summary Section -->
                    <div class="md:w-1/3 bg-gray-50 p-6 sm:p-8 border-t md:border-t-0 md:border-l border-gray-200">
                        <div class="sticky top-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Récapitulatif
                            </h2>

                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Laboratoire</h3>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $laboratoire->nom }}</p>
                                </div>

                                <div id="recapDate" class="hidden">
                                    <h3 class="text-sm font-medium text-gray-500">Date</h3>
                                    <p class="mt-1 text-lg font-semibold text-gray-900" id="recapDateValue"></p>
                                </div>

                                <div id="recapEquipements" class="hidden">
                                    <h3 class="text-sm font-medium text-gray-500">Équipements</h3>
                                    <ul class="mt-2 space-y-1 text-gray-900" id="recapEquipementsList"></ul>
                                </div>

                                <div id="recapHoraires" class="hidden">
                                    <h3 class="text-sm font-medium text-gray-500">Créneaux</h3>
                                    <ul class="mt-2 space-y-1 text-gray-900" id="recapHorairesList"></ul>
                                </div>

                                <div id="recapObjectif" class="hidden">
                                    <h3 class="text-sm font-medium text-gray-500">Objectif</h3>
                                    <p class="mt-1 text-gray-900" id="recapObjectifValue"></p>
                                </div>

                                <button type="submit" form="reservationForm" id="submitBtn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-300" disabled>
                                    Confirmer la réservation
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals and Overlays -->
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

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3">
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
        let horairesSelectionnes = [];

        // Charger les horaires disponibles
        async function loadHoraires() {
            const date = document.getElementById('dateInput').value;
            if (!date) return;

            const container = document.getElementById('horairesContainer');
            container.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-500">Chargement des créneaux...</p></div>';

            try {
                const response = await fetch(`/reservations/horaires/{{ $laboratoire->id }}?date=${date}`);
                const data = await response.json();

                console.log('Données reçues:', data);

                if (!data.horaires_base || data.horaires_base.length === 0) {
                    container.innerHTML = '<div class="text-center text-gray-500 py-8">Aucun créneau disponible pour cette date</div>';
                    return;
                }

                afficherHoraires(data.horaires_base, data.horaires_reserves, data.current_date, data.current_time);
                updateRecap();
            } catch (error) {
                console.error('Erreur:', error);
                container.innerHTML = '<div class="text-center text-red-500 py-8">Erreur lors du chargement des créneaux</div>';
            }
        }

        // Afficher les horaires
        function afficherHoraires(horairesBase, horairesReserves, currentDate, currentTime) {
            const container = document.getElementById('horairesContainer');
            const selectedDate = document.getElementById('dateInput').value;
            const isToday = selectedDate === currentDate;

            let html = '<div class="grid grid-cols-2 gap-3">';
            horairesBase.forEach((horaire) => {
                // Vérifier si le créneau est réservé
                const estReserve = horairesReserves.some(reserve =>
                    reserve.debut === horaire.debut && reserve.fin === horaire.fin
                );

                // Vérifier si le créneau est passé (uniquement pour aujourd'hui)
                const isPast = isToday && (
                    horaire.fin < currentTime ||
                    (horaire.debut < currentTime && horaire.fin > currentTime)
                );

                // Un créneau est disponible s'il n'est pas réservé OU s'il est passé
                const isAvailable = !estReserve || isPast;

                const disabledClass = !isAvailable ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:border-blue-300';
                const bgClass = estReserve && !isPast ? 'bg-red-50' : 'bg-white';
                const borderColor = estReserve && !isPast ? 'border-red-300' : 'border-gray-200';
                const textColor = estReserve && !isPast ? 'text-red-600' : 'text-gray-900';

                let statusText = '';
                if (estReserve && !isPast) statusText = 'Réservé';
                else if (isPast) statusText = 'Disponible';

                html += `
                    <label class="relative flex items-center justify-center p-3 border-2 rounded-lg transition-all duration-200 horaire-item ${borderColor} ${bgClass} ${disabledClass}">
                        <input type="checkbox" class="sr-only horaire-checkbox"
                               data-debut="${horaire.debut}"
                               data-fin="${horaire.fin}"
                               onchange="toggleHoraire(this)"
                               ${!isAvailable ? 'disabled' : ''}>
                        <div class="text-center">
                            <div class="font-medium ${textColor}">
                                ${horaire.debut} - ${horaire.fin}
                            </div>
                            ${statusText ? `<div class="text-xs ${estReserve && !isPast ? 'text-red-500' : 'text-green-500'} mt-1">${statusText}</div>` : ''}
                        </div>
                    </label>
                `;
            });
            html += '</div>';

            container.innerHTML = html;
        }

        // Toggle horaire
        function toggleHoraire(checkbox) {
            if (checkbox.disabled) return; // Ne rien faire si le créneau est indisponible

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
            const canSubmit = date && equipements.length > 0 && horairesSelectionnes.length > 0;
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

        // Soumission du formulaire
        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Vérifier que tous les champs sont remplis
            const date = document.getElementById('dateInput').value;
            const equipements = document.querySelectorAll('.equipement-checkbox:checked');
            const objectif = document.getElementById('objectif').value;

            if (!date || equipements.length === 0 || horairesSelectionnes.length === 0) {
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
                    <span class="text-green-600 font-medium">${reservation.statut}</span>
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
