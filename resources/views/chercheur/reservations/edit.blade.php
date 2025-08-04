@extends('dashboard')

@section('title', 'Modifier une Réservation')
@section('titre', 'Modifier une Réservation')

@section('content')
<div id="content" class="content">
    <ol class="breadcrumb float-xl-right">
        <li class="breadcrumb-item"><a href="javascript:;">Accueil</a></li>
        <li class="breadcrumb-item active">@yield('titre')</li>
    </ol>

    <h1 class="page-header"># Modification d'une Réservation</h1>

    <form action="{{ route('chercheur.reservations.update', $reservation->id) }}" method="POST" id="reservation-form">
        @csrf
        @method('PUT')

        <!-- Sélection du laboratoire -->
        <div class="form-group">
            <label for="laboratoire">Laboratoire *</label>
            <select name="laboratoire_id" id="laboratoire" class="form-control" required>
                <option value="">Sélectionner un laboratoire</option>
                @foreach ($laboratoires as $labo)
                    <option value="{{ $labo->id }}" {{ $reservation->laboratoire_id == $labo->id ? 'selected' : '' }}>
                        {{ $labo->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Date de réservation -->
        <div class="form-group">
            <label for="date">Date de réservation *</label>
            <input type="date" name="date" id="date" class="form-control"
                value="{{ $reservation->date->format('Y-m-d') }}"
                min="{{ now()->toDateString() }}" required>
        </div>

        <!-- Équipements dynamiques -->
        <div class="form-group" id="equipements-container">
            <label for="equipements">Équipements</label><br>
            <div id="equipements-list">
                @foreach ($laboratoires->find($reservation->laboratoire_id)?->equipements ?? [] as $equipement)
                    <div class="form-check">
                        <input type="checkbox" name="equipements[]" value="{{ $equipement->id }}"
                            class="form-check-input"
                            {{ $reservation->equipements->contains('id', $equipement->id) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $equipement->nom }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Créneaux horaires -->
        <div class="form-group">
            <label>Créneaux horaires *</label>
            <div id="creneaux-container">
                <div class="row" id="creneaux-disponibles">
                    <!-- Les créneaux seront chargés dynamiquement -->
                </div>
                <div class="mt-3">
                    <label>Créneaux sélectionnés :</label>
                    <div id="creneaux-selectionnes">
                        @foreach ($reservation->horaires as $index => $horaire)
                            <div class="mb-2 creneau-selectionne">
                                <input type="hidden" name="horaires[]"
                                    value="{{ $horaire->heure_debut->format('H:i') }} - {{ $horaire->heure_fin->format('H:i') }}">
                                <span class="badge badge-primary mr-2">
                                    {{ $horaire->heure_debut->format('H:i') }} - {{ $horaire->heure_fin->format('H:i') }}
                                    <button type="button" class="btn btn-sm btn-link text-white p-0 ml-1 remove-creneau">×</button>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Objectif -->
        <div class="form-group">
            <label for="objectif">Objectif *</label>
            <textarea name="objectif" id="objectif" class="form-control" rows="4" required>{{ $reservation->objectif }}</textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Modifier la réservation</button>
            <a href="{{ route('chercheur.reservations.historique') }}" class="btn btn-secondary ml-2">Annuler</a>
        </div>
    </form>
</div>

<style>
.creneau-btn {
    margin: 2px;
    border: 1px solid #ddd;
    background-color: #f8f9fa;
}

.creneau-btn.disponible {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}

.creneau-btn.disponible:hover {
    background-color: #218838;
}

.creneau-btn.indisponible {
    background-color: #dc3545;
    color: white;
    border-color: #dc3545;
    cursor: not-allowed;
}

.creneau-btn.selectionne {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.equipement-indisponible {
    opacity: 0.5;
}

.equipement-indisponible input {
    cursor: not-allowed;
}
</style>

<script>
    const reservationId = {{ $reservation->id }};
    const reservationEquipements = @json($reservation->equipements->pluck('id'));
    let creneauxSelectionnes = [];

    // Initialiser les créneaux sélectionnés
    @foreach ($reservation->horaires as $horaire)
        creneauxSelectionnes.push('{{ $horaire->heure_debut->format("H:i") }} - {{ $horaire->heure_fin->format("H:i") }}');
    @endforeach

    document.addEventListener('DOMContentLoaded', function() {
        // Charger les créneaux au chargement de la page
        if (document.getElementById('laboratoire').value && document.getElementById('date').value) {
            chargerCreneaux();
        }

        // Écouteur pour le changement de laboratoire
        document.getElementById('laboratoire').addEventListener('change', function() {
            chargerEquipements();
            chargerCreneaux();
        });

        // Écouteur pour le changement de date
        document.getElementById('date').addEventListener('change', function() {
            chargerEquipements();
            chargerCreneaux();
        });

        // Gérer la suppression des créneaux sélectionnés
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-creneau')) {
                const creneauDiv = e.target.closest('.creneau-selectionne');
                const creneauValue = creneauDiv.querySelector('input').value;

                // Retirer de la liste
                creneauxSelectionnes = creneauxSelectionnes.filter(c => c !== creneauValue);
                creneauDiv.remove();

                // Mettre à jour l'affichage des créneaux
                mettreAJourAffichageCreneaux();
            }
        });
    });

    function chargerEquipements() {
        const laboId = document.getElementById('laboratoire').value;
        const date = document.getElementById('date').value;

        if (!laboId || !date) return;

        const container = document.getElementById('equipements-list');
        container.innerHTML = '<p>Chargement des équipements...</p>';

        fetch(`/chercheur/reservations/equipements/${laboId}?date=${date}&reservation_id=${reservationId}`)
            .then(response => response.json())
            .then(equipements => {
                let html = '';
                equipements.forEach(equipement => {
                    const checked = reservationEquipements.includes(equipement.id) ? 'checked' : '';
                    const disabled = !equipement.disponible && !reservationEquipements.includes(equipement.id) ? 'disabled' : '';
                    const classe = !equipement.disponible && !reservationEquipements.includes(equipement.id) ? 'equipement-indisponible' : '';

                    html += `
                        <div class="form-check ${classe}">
                            <input type="checkbox" name="equipements[]" value="${equipement.id}"
                                class="form-check-input" ${checked} ${disabled}>
                            <label class="form-check-label">
                                ${equipement.nom}
                                ${!equipement.disponible && !reservationEquipements.includes(equipement.id) ? ' (Indisponible)' : ''}
                            </label>
                        </div>
                    `;
                });

                container.innerHTML = html || '<p>Aucun équipement disponible</p>';
            })
            .catch(error => {
                console.error('Erreur:', error);
                container.innerHTML = '<p class="text-danger">Erreur lors du chargement des équipements</p>';
            });
    }

    function chargerCreneaux() {
        const laboId = document.getElementById('laboratoire').value;
        const date = document.getElementById('date').value;

        if (!laboId || !date) return;

        const container = document.getElementById('creneaux-disponibles');
        container.innerHTML = '<p>Chargement des créneaux...</p>';

        fetch(`/chercheur/reservations/horaires-edit/${laboId}?date=${date}&reservation_id=${reservationId}`)
            .then(response => response.json())
            .then(data => {
                const creneaux = data.creneaux;
                let html = '';
                creneaux.forEach(creneau => {
                    const estSelectionne = creneauxSelectionnes.includes(creneau.libelle);
                    let classe = 'btn creneau-btn ';
                    let disabled = '';

                    if (estSelectionne) {
                        classe += 'selectionne';
                    } else if (creneau.disponible) {
                        classe += 'disponible';
                    } else {
                        classe += 'indisponible';
                        disabled = 'disabled';
                    }

                    html += `
                        <div class="col-md-2 mb-2">
                            <button type="button" class="${classe}"
                                data-creneau="${creneau.libelle}" ${disabled}>
                                ${creneau.libelle}
                            </button>
                        </div>
                    `;
                });

                container.innerHTML = html;

                // Ajouter les écouteurs d'événements
                container.querySelectorAll('.creneau-btn').forEach(btn => {
                    if (!btn.disabled) {
                        btn.addEventListener('click', function() {
                            toggleCreneau(this.dataset.creneau);
                        });
                    }
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
                container.innerHTML = '<p class="text-danger">Erreur lors du chargement des créneaux</p>';
            });
    }

    function toggleCreneau(creneau) {
        const index = creneauxSelectionnes.indexOf(creneau);

        if (index > -1) {
            // Retirer le créneau
            creneauxSelectionnes.splice(index, 1);
        } else {
            // Ajouter le créneau
            creneauxSelectionnes.push(creneau);
        }

        mettreAJourAffichageCreneaux();
        mettreAJourBoutonsCreneaux();
    }

    function mettreAJourAffichageCreneaux() {
        const container = document.getElementById('creneaux-selectionnes');
        let html = '';

        creneauxSelectionnes.forEach(creneau => {
            html += `
                <div class="mb-2 creneau-selectionne">
                    <input type="hidden" name="horaires[]" value="${creneau}">
                    <span class="badge badge-primary mr-2">
                        ${creneau}
                        <button type="button" class="btn btn-sm btn-link text-white p-0 ml-1 remove-creneau">×</button>
                    </span>
                </div>
            `;
        });

        container.innerHTML = html || '<p class="text-muted">Aucun créneau sélectionné</p>';
    }

    function mettreAJourBoutonsCreneaux() {
        document.querySelectorAll('.creneau-btn').forEach(btn => {
            const creneau = btn.dataset.creneau;
            const estSelectionne = creneauxSelectionnes.includes(creneau);

            btn.classList.remove('disponible', 'selectionne');

            if (estSelectionne) {
                btn.classList.add('selectionne');
            } else if (!btn.classList.contains('indisponible')) {
                btn.classList.add('disponible');
            }
        });
    }
</script>
@endsection
