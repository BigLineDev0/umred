@extends('dashboard')
@section('title', 'Tableau de bord Technicien')
@section('titre', 'Histoirique des Maintenances')

@section('content')
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item">
                <a href="#modal-planifier-maintenance" class="btn btn-sm btn-dark text-white" data-toggle="modal">Planifier une
                    maintenance</a>
            </li>

            <li id="btn-show-liste-user" class="breadcrumb-item">
                <a href="#" class="btn btn-sm btn-dark text-white">Equipements en Maintenances</a>
            </li>
        </ol>

        <h1 class="page-header"># Historique</h1>

        <!-- Liste Equipements -->
        <div id="table-liste-user" class="panel panel-inverse">

            <div class="panel-heading">
                <h4 class="panel-title">Historiques des Maintenances effectuées</h4>
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
                            <th class="text-nowrap text-center">Equipement</th>
                            <th class="text-nowrap text-center">Description</th>
                            <th class="text-nowrap text-center">Date Prévue</th>
                            <th class="text-nowrap text-center">Satut</th>
                            <th class="text-nowrap text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenances as $index => $maintenance)
                            <tr class="odd gradeX">
                                <!-- Index -->
                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Equipement -->
                                <td class="text-center">
                                    {{ $maintenance->equipement->nom }}
                                </td>

                                <td class="text-center">
                                    @if (strlen($maintenance->description) > 20)
                                        {{ Str::limit($maintenance->description, 20, '...') }}
                                    @else
                                        {{ $maintenance->description }}
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ $maintenance->date_prevue->format('d/m/Y') }}
                                </td>

                                <!-- Statut -->
                                <td class="text-center">
                                    <span
                                        class="badge badge-{{ $maintenance->statut === 'en_cours' ? 'warning' : 'success' }}">
                                        {{ ucfirst($maintenance->statut) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="text-center">

                                    <a href="#" class="btn-detail-maintenance" data-id="{{ $maintenance->id }}"
                                        data-equipement="{{ $maintenance->equipement->nom ?? 'N/A' }}"
                                        data-date-prevue="{{ $maintenance->date_prevue }}"
                                        data-statut="{{ ucfirst($maintenance->statut) }}"
                                        data-description="{{ $maintenance->description ?? 'Aucune description' }}"
                                        data-created-at="{{ $maintenance->created_at->format('d/m/Y à H:i') }}"
                                        data-updated-at="{{ $maintenance->updated_at->format('d/m/Y à H:i') }}"
                                        data-toggle="modal" data-target="#modal-detail-maintenance" title="Voir détails">
                                        <i class="fa fa-eye btn btn-info"></i>
                                    </a>

                                    @if ($maintenance->statut !== 'terminée')
                                        <!-- Modifier -->
                                        <a href="#" class="btn-edit-maintenance" data-toggle="modal"
                                            data-target="#modal-edit-maintenance" data-id="{{ $maintenance->id }}"
                                            data-equipement-id="{{ $maintenance->equipement_id }}"
                                            data-date-prevue="{{ \Carbon\Carbon::parse($maintenance->date_prevue)->format('Y-m-d') }}"
                                            data-description="{{ $maintenance->description }}"
                                            data-statut="{{ $maintenance->statut }}"
                                            data-action="{{ route('technicien.maintenances.update', $maintenance->id) }}"
                                            title="Modifier la maintenance">
                                            <i class="fa fa-edit btn btn-warning"></i>
                                        </a>

                                        <!-- Marquer terminé -->
                                        <form id="terminer-form-{{ $maintenance->id }}"
                                            action="{{ route('technicien.maintenances.terminer', $maintenance->id) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                        <button type="button" class="btn btn-success btn-terminer-maintenance"
                                            data-id="{{ $maintenance->id }}" title="Marquer terminé">
                                            <i class="fa fa-check"></i>
                                        </button>

                                        <!-- supprimer maintenance -->
                                        <form id="delete-maintenance-form-{{ $maintenance->id }}"
                                            action="{{ route('technicien.maintenances.destroy', $maintenance->id) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <a href="#" class="btn btn-danger btn-delete-maintenance"
                                            data-id="{{ $maintenance->id }}" title="Supprimer la maintenance">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <p class="alert alert-danger text-center h4 fw-bold">Aucune maintenance pour le moment.</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========== Modal Planifier une Maintenance ========== -->
    <div class="modal fade" id="modal-planifier-maintenance" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Planifier une maintenance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <form id="planifierMaintenanceForm" action="{{ route('technicien.maintenances.store') }}"
                        method="post">
                        @csrf

                        <!-- Équipement -->
                        <div class="mb-3">
                            <label for="maintenance-equipement" class="form-label">Équipement <span
                                    class="text-danger">*</span></label>
                            <select name="equipement_id" id="maintenance-equipement" class="form-control" required>
                                <option value="">-- Sélectionner un équipement --</option>
                                @forelse($equipements as $equipement)
                                    <option value="{{ $equipement->id }}">{{ $equipement->nom }}</option>
                                @empty
                                    <option disabled>Aucun équipement en maintenance disponible</option>
                                @endforelse
                            </select>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('equipement_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Date prévue -->
                        <div class="mb-3">
                            <label for="maintenance-date" class="form-label">Date prévue <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="date_prevue" class="form-control" id="maintenance-date"
                                required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('date_prevue')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="maintenance-description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="maintenance-description" rows="3"
                                placeholder="Description de la maintenance..."></textarea>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div style="display: flex; justify-content: center;">
                            <button type="submit" name="frmPlanifierMaintenance" class="btn btn-primary fw-bold"
                                id="btnPlanifierMaintenance">
                                Planifier
                            </button>
                            &nbsp;&nbsp;
                            <button type="reset" class="btn btn-danger fw-bold">Annuler</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- ========== Modal Modifier une Maintenance ========== -->
    <div class="modal fade" id="modal-edit-maintenance" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Modifier la maintenance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <form id="editMaintenanceForm" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="maintenance_id" id="edit-maintenance-id">

                        <!-- Équipement -->
                        <div class="mb-3">
                            <label for="edit-maintenance-equipement" class="form-label">Équipement <span
                                    class="text-danger">*</span></label>
                            <select name="equipement_id" id="edit-maintenance-equipement" class="form-control" required>
                                <option value="">-- Sélectionner un équipement --</option>
                                @forelse($equipements as $equipement)
                                    <option value="{{ $equipement->id }}">{{ $equipement->nom }}</option>
                                @empty
                                    <option disabled>Aucun équipement disponible</option>
                                @endforelse
                            </select>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('equipement_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Date prévue -->
                        <div class="mb-3">
                            <label for="edit-maintenance-date" class="form-label">Date prévue <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="date_prevue" class="form-control" id="edit-maintenance-date"
                                required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('date_prevue')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="edit-maintenance-description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="edit-maintenance-description" rows="3"
                                placeholder="Description de la maintenance..."></textarea>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="edit-maintenance-statut" class="form-label">Statut</label>
                            <select name="statut" id="edit-maintenance-statut" class="form-control">
                                <option value="en_cours">En cours</option>
                                <option value="terminée">Terminée</option>
                            </select>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('statut')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div style="display: flex; justify-content: center;">
                            <button type="submit" class="btn btn-success fw-bold" id="btnEditMaintenance">
                                Enregistrer
                            </button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-danger fw-bold" data-dismiss="modal">Annuler</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- ========== Modal Détails de la Maintenance ========== -->
    <div class="modal fade" id="modal-detail-maintenance" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalDetailMaintenanceLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content shadow border-0">

                <!-- En-tête -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalDetailMaintenanceLabel">
                        <i class="fa fa-wrench mr-2"></i> Détails de la maintenance
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Corps -->
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">
                            <h4 id="detail-maintenance-equipement" class="text-primary fw-bold mb-3"></h4>

                            <p>
                                <strong>Description :</strong><br>
                                <span id="detail-maintenance-description" class="text-muted"></span>
                            </p>

                            <p>
                                <strong>Date prévue :</strong><br>
                                <i class="fa fa-calendar-alt text-secondary mr-1"></i>
                                <span id="detail-maintenance-date-prevue" class="text-muted"></span>
                            </p>

                            <p>
                                <strong>Statut :</strong>
                                <span id="detail-maintenance-statut" class="badge badge-info px-2 py-1"></span>
                            </p>

                            <p>
                                <strong>Créée le :</strong>
                                <span id="detail-maintenance-created-at" class="text-muted"></span>
                            </p>

                            <p id="updated-at-wrapper" class="d-none">
                                <strong>Dernière modification :</strong>
                                <span id="detail-maintenance-updated-at" class="text-muted"></span>
                            </p>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>


@endsection
@include('technicien.maintenances.scripts')
