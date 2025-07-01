@extends('dashboard')
@section('title', 'Tableau de Admin')
@section('titre', 'Equipements')

@section('content')
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item">
                <a href="#modal-add-equipement" class="btn btn-sm btn-dark text-white" data-toggle="modal">Ajouter</a>
            </li>

            <li id="btn-show-liste-user" class="breadcrumb-item">
                <a href="#" class="btn btn-sm btn-dark text-white">Afficher liste</a>
            </li>
        </ol>

        <h1 class="page-header"># Equipements</h1>

        <!-- Liste Equipements -->
        <div id="table-liste-user" class="panel panel-inverse">

            <div class="panel-heading">
                <h4 class="panel-title">Liste Equipements</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                            class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i
                            class="fa fa-times"></i></a>
                </div>
            </div>

            <div class="panel-body">
                <table id="data-table-default" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th width="1%" data-orderable="false">#</th>
                            <th class="text-nowrap text-center">Nom</th>
                            <th class="text-nowrap text-center">Laboratoire</th>
                            <th class="text-nowrap text-center">Statut</th>
                            <th class="text-nowrap text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipements as $index => $equipement)
                            <tr class="odd gradeX">
                                <!-- Index -->
                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Nom -->
                                <td class="text-center">
                                    {{ $equipement->nom }}
                                </td>

                                <!-- Laboratoire -->
                                <td>
                                    @foreach ($equipement->laboratoires as $laboratoire)
                                        <div><i class="fa fa-check-circle text-success"></i> {{ $laboratoire->nom }}</div>
                                    @endforeach
                                </td>

                                <!-- Statut -->
                                <td class="text-center">
                                    @if ($equipement->statut == 'disponible')
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($equipement->statut == 'reserve')
                                        <span class="badge bg-warning">Réservé</span>
                                    @else
                                        <span class="badge bg-danger">Maintenance</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="text-center">
                                    <!-- Détails -->
                                    <a href="#" class="btn-detail-labo" data-id="{{ $equipement->id }}"
                                        data-nom="{{ $equipement->nom }}" data-description="{{ $equipement->description }}"
                                        data-statut="{{ $equipement->statut }}"
                                        data-created_at="{{ $equipement->created_at->format('d/m/Y à H:i') }}"
                                        data-updated_at="{{ $equipement->updated_at->format('d/m/Y à H:i') }}"
                                        data-laboratoires="{{ $equipement->laboratoires->pluck('nom')->implode('|') }}"
                                        data-toggle="modal" data-target="#modal-detail-labo" title="Voir détails">
                                        <i class="fa fa-eye btn btn-info"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="javascript:;" class="btn-edit-equipement" data-id="{{ $equipement->id }}"
                                        data-nom="{{ $equipement->nom }}"
                                        data-description="{{ $equipement->description }}"
                                        data-statut="{{ $equipement->statut }}"
                                        data-laboratoires="{{ $equipement->laboratoires->pluck('id') }}"
                                        data-action="{{ route('admin.equipements.update', $equipement->id) }}"
                                        data-toggle="modal" data-target="#modal-edit-equipement" title="Modifier">
                                        <i class="fa fa-edit btn btn-success"></i>
                                    </a>

                                    <!-- Supprimer -->
                                    <form id="delete-form-{{ $equipement->id }}"
                                        action="{{ route('admin.equipements.destroy', $equipement) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-danger delete-btn"
                                        data-id="{{ $equipement->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <p class="alert alert-danger text-center h4 fw-bold">La liste des Equipements est vide.</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========== Modal add équipement ========== -->
    <div class="modal fade" id="modal-add-equipement" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Ajouter un équipement</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <form id="addEquipementForm" action="{{ route('admin.equipements.store') }}" method="post">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="equipement-nom" class="form-label">Nom de l’équipement</label>
                            <input type="text" name="nom" class="form-control" id="equipement-nom"
                                placeholder="Ex : Spectrophotomètre UV" required>
                            <div class="text-danger small">
                                @error('nom')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="equipement-description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="equipement-description" rows="3"
                                placeholder="Description détaillée de l’équipement..."></textarea>
                            <div class="text-danger small">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="equipement-statut" class="form-label">Statut</label>
                            <select name="statut" id="equipement-statut" class="form-control" required>
                                <option value="">-- Sélectionner un statut --</option>
                                <option value="disponible">Disponible</option>
                                <option value="reserve">Réservé</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            <div class="text-danger small">
                                @error('statut')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Laboratoires associés -->
                        <div class="mb-3">
                            <label class="form-label">Laboratoires associés</label>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($laboratoires as $laboratoire)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="laboratoires[]"
                                            value="{{ $laboratoire->id }}" id="labo{{ $laboratoire->id }}">
                                        <label class="form-check-label" for="labo{{ $laboratoire->id }}">
                                            {{ $laboratoire->nom }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-danger small mt-1">
                                @error('laboratoires')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary fw-bold">Ajouter</button>
                            <button type="reset" class="btn btn-danger fw-bold ml-2">Annuler</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal modification équipement -->
    <div class="modal fade" id="modal-edit-equipement" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Modifier un équipement</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <form id="editEquipementForm" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" id="edit-equipement-id">

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="edit-equipement-nom" class="form-label">Nom</label>
                            <input type="text" name="nom" id="edit-equipement-nom" class="form-control" required>
                            <div class="text-danger small">
                                @error('nom')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="edit-equipement-description" class="form-label">Description</label>
                            <textarea name="description" id="edit-equipement-description" class="form-control" rows="3"></textarea>
                            <div class="text-danger small">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="edit-equipement-statut" class="form-label">Statut</label>
                            <select name="statut" id="edit-equipement-statut" class="form-control" required>
                                <option value="">-- Sélectionner un statut --</option>
                                <option value="disponible">Disponible</option>
                                <option value="reserve">Réservé</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            <div class="text-danger small">
                                @error('statut')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Laboratoires -->
                        <div class="mb-3">
                            <label>Laboratoires associés</label>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($laboratoires as $laboratoire)
                                    <div class="form-check">
                                        <input class="form-check-input edit-labo-checkbox" type="checkbox"
                                            name="laboratoires[]" value="{{ $laboratoire->id }}"
                                            id="edit-labo{{ $laboratoire->id }}">
                                        <label class="form-check-label" for="edit-labo{{ $laboratoire->id }}">
                                            {{ $laboratoire->nom }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-danger small">
                                @error('laboratoires')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Modifier</button>
                            <button type="reset" class="btn btn-secondary ml-2" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- ========== Modal de détails équipement ========== -->
    <div class="modal fade" id="modal-detail-labo" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalDetailLaboLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content shadow border-0">

                <!-- En-tête -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalDetailLaboLabel">Détails de l'équipement</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Corps -->
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">
                            <h4 id="detail-labo-nom" class="text-primary fw-bold mb-3"></h4>

                            <p>
                                <strong>Description :</strong><br>
                                <span id="detail-labo-description" class="text-muted"></span>
                            </p>

                            <p>
                                <strong>Statut :</strong>
                                <span id="detail-labo-statut" class="badge badge-info px-2 py-1"></span>
                            </p>

                            <p>
                                <strong>Laboratoires associés :</strong><br>
                                <span id="detail-labo-laboratoires" class="text-muted"></span>
                            </p>

                            <p>
                                <strong>Créé le :</strong>
                                <span id="detail-labo-created-at" class="text-muted"></span>
                                par <strong>{{ auth()->user()->prenom }} {{ auth()->user()->name }}</strong>
                            </p>

                            <p id="updated-at-wrapper" class="d-none">
                                <strong>Dernière modification :</strong>
                                <span id="detail-labo-updated-at" class="text-muted"></span>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@include('admin.equipements.scripts')
