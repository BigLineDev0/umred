@extends('dashboard')
@section('title', 'Tableau de Admin')
@section('titre', 'Réservations')

@section('content')
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item">
                <a href="#modal-add-reservation" class="btn btn-sm btn-dark text-white" data-toggle="modal">Ajouter</a>
            </li>

            <li id="btn-show-liste-user" class="breadcrumb-item">
                <a href="#" class="btn btn-sm btn-dark text-white">Valider une réservation</a>
            </li>
        </ol>

        <h1 class="page-header"># Réservations</h1>

        <!-- Liste Réservations -->
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Liste des Réservations</h4>
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
                            <th>Utilisateur</th>
                            <th>Laboratoire</th>
                            {{-- <th>Équipements</th> --}}
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
                                <td>{{ $reservation->user->prenom }} {{ $reservation->user->name }}</td>
                                <td>{{ $reservation->laboratoire->nom }}</td>
                                {{-- <td>
                                    @foreach ($reservation->equipements as $equipement)
                                        <span class="badge badge-secondary">{{ $equipement->nom }}</span>
                                    @endforeach
                                </td> --}}
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

                                    <!-- Modifier -->
                                    <a href="{{ route('admin.reservations.edit', $reservation->id) }}"
                                        class="btn btn-sm btn-success" title="Modifier">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <!-- Supprimer -->
                                    <form action="{{ route('admin.reservations.destroy', $reservation->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Confirmer la suppression ?')" title="Supprimer">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Ajouter Réservation -->
    <div class="modal fade" id="modal-add-reservation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Nouvelle Réservation</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.reservations.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Utilisateur -->
                            <div class="col-md-6 mb-3">
                                <label for="user_id">Utilisateur</label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->name }} -
                                            {{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Laboratoire -->
                            <div class="col-md-6 mb-3">
                                <label for="laboratoire_id">Laboratoire</label>
                                <select name="laboratoire_id" id="laboratoire-select" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($laboratoires as $labo)
                                        <option value="{{ $labo->id }}">{{ $labo->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="col-md-6 mb-3">
                                <label for="date">Date de réservation</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>

                            <!-- Objectif -->
                            <div class="col-md-6 mb-3">
                                <label for="objectif">Objectif</label>
                                <input type="text" name="objectif" class="form-control"
                                    placeholder="But de la réservation" required>
                            </div>

                            <!-- Équipements -->
                            <div class="col-12 mb-3">
                                <label for="equipements">Équipements</label>
                                <div id="equipements-checkboxes" class="border p-2 rounded" style="min-height: 50px;">
                                    <!-- Checkboxes dynamiques via JS -->
                                    <em class="text-muted">Veuillez sélectionner un laboratoire pour afficher les
                                        équipements.</em>
                                </div>
                            </div>

                            <!-- Horaires -->
                            <div class="col-12 mb-3">
                                <label for="horaires">Horaires (créneaux)</label>
                                <div id="horaires-list">
                                    <div class="row mb-2 horaire-item">
                                        <div class="col-md-5">
                                            <input type="time" name="heure_debut[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="time" name="heure_fin[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-horaire">&times;</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-horaire">+ Ajouter
                                    un créneau</button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="reset" class="btn btn-outline-danger" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Réserver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Détail Réservation -->
    <div class="modal fade" id="modal-detail-reservation" tabindex="-1" role="dialog" aria-hidden="true">
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
@include('admin.reservations.scripts')
