@extends('dashboard')
@section('title', 'Tableau de bord chercheur')
@section('titre', 'Réservations')

@section('content')
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item">
                <a href="#modal-add-reservation" class="btn btn-sm btn-dark text-white" data-toggle="modal">Ajouter</a>
            </li>

            <li id="btn-show-liste-user" class="breadcrumb-item">
                <a href="#" class="btn btn-sm btn-dark text-white">Terminer une réservation</a>
            </li>
        </ol>

        <h1 class="page-header"># Mes Réservations</h1>

        <!-- Liste Réservations -->
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Historiques de mes Réservations</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload">
                        <i class="fa fa-redo"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse">
                        <i class="fa fa-minus"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>

            <div class="panel-body">
                <table id="data-table-default" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Laboratoire</th>
                            <th>Date</th>
                            <th>Horaires</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $index => $reservation)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $reservation->laboratoire->nom ?? 'N/A' }}</td>

                                <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</td>
                                <td>
                                    @foreach ($reservation->horaires as $horaire)
                                        <div class="text-nowrap">
                                            {{ \Carbon\Carbon::parse($horaire->heure_debut)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($horaire->heure_fin)->format('H:i') }}
                                        </div>
                                    @endforeach
                                </td>

                                <td>
                                    <span
                                        class="badge
                                    @if ($reservation->statut == 'confirmée') badge-success
                                    @elseif($reservation->statut == 'annulée') badge-danger
                                    @elseif($reservation->statut == 'terminée') badge-primary
                                    @else badge-warning @endif">
                                        {{ ucfirst($reservation->statut) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!-- Bouton Voir Détails -->
                                    <a href="javascript:;" class="btn btn-sm btn-info btn-detail-reservation"
                                        data-id="{{ $reservation->id }}"
                                        data-user="{{ $reservation->user->prenom }} {{ $reservation->user->name }}"
                                        data-email="{{ $reservation->user->email }}"
                                        data-labo="{{ $reservation->laboratoire->nom }}"
                                        data-equipements="{{ $reservation->equipements->pluck('nom')->join(', ') }}"
                                        data-horaires="{{ $reservation->horaires->map(fn($h) => $h->heure_debut . '-' . $h->heure_fin)->join(', ') }}"
                                        data-date="{{ $reservation->date->format('d/m/Y') }}"
                                        data-objectif="{{ $reservation->objectif }}"
                                        data-statut="{{ ucfirst($reservation->statut) }}"
                                        data-created="{{ $reservation->created_at->format('d/m/Y à H:i') }}"
                                        data-updated="{{ $reservation->updated_at->format('d/m/Y à H:i') }}"
                                        data-toggle="modal" data-target="#modal-detail-reservation" title="Détails">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    @if (!in_array($reservation->statut, ['annulée', 'confirmée', 'terminée']))
                                        <!-- Modifier -->
                                        <a href="javascript:;" class="btn btn-sm btn-success btn-edit-reservation"
                                            data-id="{{ $reservation->id }}"
                                            data-date="{{ $reservation->date }}"
                                            data-objectif="{{ $reservation->objectif }}"
                                            data-labo-id="{{ $reservation->laboratoire_id }}"
                                            data-equipements="{{ $reservation->equipements->pluck('id')->join(',') }}"
                                            data-horaires="{{ $reservation->horaires->map(fn($h) => $h->heure_debut . '-' . $h->heure_fin)->join(',') }}"
                                            data-toggle="modal" data-target="#modal-edit-reservation" title="Modifier">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form id="annuler-form-{{ $reservation->id }}"
                                        action="{{ route('chercheur.reservations.annuler', $reservation->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger annuler-btn"
                                                data-id="{{ $reservation->id }}" title="Annuler">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- <!-- Modal modification réservation -->
    <div class="modal fade" id="modal-edit-reservation" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">

                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title">Modifier une réservation</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <form id="editReservationForm" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" id="edit-reservation-id">

                        <!-- Date -->
                        <div class="mb-3">
                            <label for="edit-reservation-date" class="form-label">Date</label>
                            <input type="date" name="date" id="edit-reservation-date" class="form-control" required>
                        </div>

                        <!-- Objectif -->
                        <div class="mb-3">
                            <label for="edit-reservation-objectif" class="form-label">Objectif</label>
                            <textarea name="objectif" id="edit-reservation-objectif" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- Laboratoire -->
                        <div class="mb-3">
                            <label for="edit-reservation-laboratoire" class="form-label">Laboratoire</label>
                            <select name="laboratoire_id" id="edit-reservation-laboratoire" class="form-control"
                                required>
                                <option value="">-- Sélectionner un laboratoire --</option>
                                @foreach ($laboratoires as $labo)
                                    <option value="{{ $labo->id }}">{{ $labo->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Équipements -->
                        <div class="mb-3">
                            <label>Équipements</label>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($equipements as $equipement)
                                    <div class="form-check">
                                        <input class="form-check-input edit-equipement-checkbox" type="checkbox"
                                            name="equipements[]" value="{{ $equipement->id }}"
                                            id="edit-eq{{ $equipement->id }}">
                                        <label class="form-check-label" for="edit-eq{{ $equipement->id }}">
                                            {{ $equipement->nom }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Horaires -->
                        <div class="mb-3">
                            <label>Horaires</label>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($horaires as $horaire)
                                    <div class="form-check">
                                        <input class="form-check-input edit-horaire-checkbox" type="checkbox"
                                            name="horaires[]" value="{{ $horaire->id }}"
                                            id="edit-horaire{{ $horaire->id }}">
                                        <label class="form-check-label" for="edit-horaire{{ $horaire->id }}">
                                            {{ $horaire->heure_debut }} - {{ $horaire->heure_fin }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Modifier</button>
                            <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div> --}}


    <!-- Modal Détail Réservation -->
    <div class="modal fade" id="modal-detail-reservation" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Détails de la Réservation</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Utilisateur</h6>
                            <p><strong>Nom :</strong> <span id="detail-user"></span></p>
                            <p><strong>Email :</strong> <span id="detail-email"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Réservation</h6>
                            <p><strong>Date :</strong> <span id="detail-date"></span></p>
                            <p><strong>Statut :</strong> <span id="detail-statut" class="badge"></span></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Laboratoire :</strong> <span id="detail-labo"></span></p>
                            <p><strong>Objectif :</strong> <span id="detail-objectif"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Équipements :</strong> <span id="detail-equipements"></span></p>
                            <p><strong>Horaires :</strong> <span id="detail-horaires"></span></p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Créé le :</strong> <span id="detail-created"></span></p>
                    <p><strong>Mis à jour le :</strong> <span id="detail-updated"></span></p>
                </div>
            </div>
        </div>
    </div>

@endsection
@include('chercheur.reservations.scripts')
