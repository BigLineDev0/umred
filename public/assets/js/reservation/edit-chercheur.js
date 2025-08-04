// JavaScript vanilla pour gérer la modification de réservation dans le modal

// Variables globales
let editCurrentLaboratoireId = null;
let editSelectedHoraires = [];

/**
 * Ouvrir le modal de modification avec les données de la réservation
 */
function editReservation(reservationId) {
    // Ouvrir le modal Bootstrap
    const modal = new bootstrap.Modal(document.getElementById('editReservationModal'));
    modal.show();

    showEditLoader();

    // ✅ CORRECTION: Ajouter le préfixe /chercheur/
    fetch(`/chercheur/reservations/${reservationId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateEditModal(data);
        } else {
            showEditError(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showEditError('Erreur lors du chargement de la réservation');
    });
}

/**
 * Remplir le modal avec les données de la réservation
 */
function populateEditModal(data) {
    const reservation = data.reservation;

    // Remplir les champs de base
    document.getElementById('edit_reservation_id').value = reservation.id;
    document.getElementById('edit_laboratoire_nom').value = reservation.laboratoire.nom;
    document.getElementById('edit_date').value = reservation.date;
    document.getElementById('edit_objectif').value = reservation.objectif || '';

    // Stocker l'ID du laboratoire
    editCurrentLaboratoireId = reservation.laboratoire.id;

    // Remplir les équipements
    populateEditEquipements(data.equipements, reservation.equipements_selectionnes);

    // Initialiser les horaires sélectionnés
    editSelectedHoraires = [...reservation.horaires];

    // Charger les horaires disponibles
    loadEditHoraires(reservation.date);

    hideEditLoader();
}

/**
 * Remplir la liste des équipements
 */
function populateEditEquipements(equipements, equipementsSelectionnes) {
    let html = '';

    equipements.forEach(function(equipement) {
        const isSelected = equipementsSelectionnes.includes(equipement.id);
        html += `
            <div class="form-check mb-2">
                <input class="form-check-input equipement-checkbox"
                       type="checkbox"
                       name="equipements[]"
                       value="${equipement.id}"
                       id="edit_equipement_${equipement.id}"
                       ${isSelected ? 'checked' : ''}>
                <label class="form-check-label w-100 p-2 border rounded"
                       for="edit_equipement_${equipement.id}">
                    <strong>${equipement.nom}</strong>
                    ${equipement.description ? `<br><small class="text-muted">${equipement.description}</small>` : ''}
                </label>
            </div>
        `;
    });

    document.getElementById('edit_equipements_container').innerHTML = html;

    // Ajouter les événements pour les checkboxes
    const checkboxes = document.querySelectorAll('.equipement-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.style.backgroundColor = '#e7f1ff';
                label.style.borderColor = '#0d6efd';
            } else {
                label.style.backgroundColor = '';
                label.style.borderColor = '';
            }
        });
    });
}

/**
 * Charger les horaires disponibles pour une date
 */
function loadEditHoraires(date) {
    if (!date || !editCurrentLaboratoireId) {
        document.getElementById('edit_horaires_container').innerHTML =
            '<p class="text-muted text-center mb-0">Veuillez d\'abord sélectionner une date</p>';
        return;
    }

    // Afficher un loader
    document.getElementById('edit_horaires_container').innerHTML =
        '<div class="text-center"><div class="spinner-border spinner-border-sm"></div> Chargement...</div>';

    // ✅ CORRECTION: Ajouter le préfixe /chercheur/
    fetch(`/chercheur/reservations/horaires/${editCurrentLaboratoireId}?date=${date}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        displayEditHoraires(data);
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('edit_horaires_container').innerHTML =
            '<p class="text-danger text-center mb-0">Erreur lors du chargement des horaires</p>';
    });
}

/**
 * Afficher les créneaux horaires dans le modal
 */
function displayEditHoraires(data) {
    const horairesBases = data.horaires_base;
    const horairesReserves = data.horaires_reserves;
    const currentTime = data.current_time;
    const currentDate = data.current_date;
    const selectedDate = document.getElementById('edit_date').value;

    let html = '<div class="row">';

    horairesBases.forEach(function(horaire) {
        const isReserved = horairesReserves.some(hr => hr.debut === horaire.debut && hr.fin === horaire.fin);
        const isPassed = selectedDate === currentDate && horaire.fin <= currentTime;
        const isSelected = editSelectedHoraires.some(h => h.debut === horaire.debut && h.fin === horaire.fin);
        const isDisabled = isReserved || isPassed;

        let statusClass = '';
        let statusText = '';

        if (isDisabled) {
            statusClass = 'disabled';
            statusText = isReserved ? 'Réservé' : 'Passé';
        } else if (isSelected) {
            statusClass = 'selected';
        }

        html += `
            <div class="col-md-6 mb-2">
                <div class="horaire-option p-2 ${statusClass}"
                     data-debut="${horaire.debut}"
                     data-fin="${horaire.fin}"
                     ${!isDisabled ? `onclick="toggleEditHoraire('${horaire.debut}', '${horaire.fin}')"` : ''}
                     style="cursor: ${isDisabled ? 'not-allowed' : 'pointer'}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><strong>${horaire.debut} - ${horaire.fin}</strong></span>
                        ${statusText ? `<small class="text-muted">${statusText}</small>` : ''}
                        ${isSelected ? '<i class="fas fa-check text-primary"></i>' : ''}
                    </div>
                </div>
            </div>
        `;
    });

    html += '</div>';

    if (editSelectedHoraires.length > 0) {
        html += `<div class="alert alert-info mt-2">
            <strong>Créneaux sélectionnés:</strong> ${editSelectedHoraires.length}
        </div>`;
    }

    document.getElementById('edit_horaires_container').innerHTML = html;
}

/**
 * Basculer la sélection d'un créneau horaire
 */
function toggleEditHoraire(debut, fin) {
    const index = editSelectedHoraires.findIndex(h => h.debut === debut && h.fin === fin);

    if (index > -1) {
        // Désélectionner
        editSelectedHoraires.splice(index, 1);
    } else {
        // Sélectionner
        editSelectedHoraires.push({ debut, fin });
    }

    // Réafficher les horaires avec la nouvelle sélection
    const selectedDate = document.getElementById('edit_date').value;
    loadEditHoraires(selectedDate);
}

/**
 * Afficher/masquer le loader du modal
 */
function showEditLoader() {
    const modalBody = document.querySelector('#editReservationModal .modal-body');
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Chargement des données...</p>
        </div>
    `;
}

function hideEditLoader() {
    // Le contenu sera déjà remplacé par populateEditModal
}

/**
 * Afficher une erreur dans le modal
 */
function showEditError(message) {
    const modalBody = document.querySelector('#editReservationModal .modal-body');
    modalBody.innerHTML = `
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
        </div>
    `;
}

/**
 * Soumettre le formulaire de modification
 */
function submitEditReservation() {
    // Validation côté client
    if (!validateEditForm()) {
        return;
    }

    // Afficher le loader sur le bouton
    const submitBtn = document.getElementById('edit_submit_btn');
    const spinner = submitBtn.querySelector('.spinner-border');

    submitBtn.disabled = true;
    spinner.classList.remove('d-none');

    // Préparer les données
    const equipementsChecked = document.querySelectorAll('input[name="equipements[]"]:checked');
    const equipements = Array.from(equipementsChecked).map(el => el.value);

    const formData = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        _method: 'PUT',
        date: document.getElementById('edit_date').value,
        equipements: equipements,
        horaires: editSelectedHoraires,
        objectif: document.getElementById('edit_objectif').value
    };

    const reservationId = document.getElementById('edit_reservation_id').value;

    // ✅ CORRECTION: Ajouter le slash initial
    fetch(`/chercheur/reservations/${reservationId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': formData._token
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher le message de succès
            showAlert('success', data.message);

            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editReservationModal'));
            modal.hide();

            // Recharger la page ou mettre à jour le tableau
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            handleEditErrors(data);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', 'Une erreur est survenue lors de la modification');
    })
    .finally(() => {
        // Cacher le loader
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
    });
}

/**
 * Valider le formulaire de modification
 */
function validateEditForm() {
    clearEditErrors();
    let isValid = true;

    // Vérifier la date
    if (!document.getElementById('edit_date').value) {
        showEditFieldError('date', 'La date est requise');
        isValid = false;
    }

    // Vérifier les équipements
    const equipementsChecked = document.querySelectorAll('input[name="equipements[]"]:checked');
    if (equipementsChecked.length === 0) {
        showEditFieldError('equipements', 'Veuillez sélectionner au moins un équipement');
        isValid = false;
    }

    // Vérifier les horaires
    if (editSelectedHoraires.length === 0) {
        showEditFieldError('horaires', 'Veuillez sélectionner au moins un créneau horaire');
        isValid = false;
    }

    return isValid;
}

/**
 * Gérer les erreurs de validation
 */
function handleEditErrors(response) {
    if (response.errors) {
        // Erreurs de validation Laravel
        Object.keys(response.errors).forEach(function(field) {
            const message = response.errors[field][0];
            showEditFieldError(field, message);
        });
    } else if (response.message) {
        // Message d'erreur général
        showAlert('danger', response.message);
    }
}

/**
 * Afficher une erreur pour un champ spécifique
 */
function showEditFieldError(field, message) {
    const errorDiv = document.getElementById(`edit_${field}_error`);
    const inputField = document.getElementById(`edit_${field}`) ||
                      document.querySelector(`[name="${field}[]"]`) ||
                      document.querySelector(`#edit_${field}_container`);

    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    if (inputField) {
        inputField.classList.add('is-invalid');
    }
}

/**
 * Effacer toutes les erreurs
 */
function clearEditErrors() {
    const errorDivs = document.querySelectorAll('.invalid-feedback');
    const invalidInputs = document.querySelectorAll('.is-invalid');

    errorDivs.forEach(div => div.style.display = 'none');
    invalidInputs.forEach(input => input.classList.remove('is-invalid'));
}

/**
 * Afficher une alerte globale (fonction utilitaire)
 */
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Ajouter l'alerte
    document.body.insertAdjacentHTML('beforeend', alertHtml);

    // Auto-masquer après 5 secondes
    setTimeout(function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            const alertInstance = new bootstrap.Alert(alert);
            alertInstance.close();
        }
    }, 5000);
}

/**
 * Initialisation des événements au chargement de la page
 */
document.addEventListener('DOMContentLoaded', function() {
    // Actualiser les horaires quand la date change
    const editDateInput = document.getElementById('edit_date');
    if (editDateInput) {
        editDateInput.addEventListener('change', function() {
            const date = this.value;
            editSelectedHoraires = []; // Réinitialiser la sélection
            loadEditHoraires(date);
        });
    }

    // Bouton d'actualisation des horaires
    const refreshBtn = document.getElementById('edit_refresh_horaires');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            const date = document.getElementById('edit_date').value;
            loadEditHoraires(date);
        });
    }

    // Soumission du formulaire de modification
    const editForm = document.getElementById('editReservationForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitEditReservation();
        });
    }

    // Réinitialiser le modal à la fermeture
    const editModal = document.getElementById('editReservationModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            editSelectedHoraires = [];
            editCurrentLaboratoireId = null;
            clearEditErrors();
        });
    }
});

/**
 * Fonction utilitaire pour débugger (à supprimer en production)
 */
function debugEdit() {
    console.log('Laboratoire ID:', editCurrentLaboratoireId);
    console.log('Horaires sélectionnés:', editSelectedHoraires);
    console.log('Date:', document.getElementById('edit_date').value);
}
