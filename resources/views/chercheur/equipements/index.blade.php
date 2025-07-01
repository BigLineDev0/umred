@extends('dashboard')
@section('title', 'Tableau de bord Chercheur')
@section('titre', 'Equipements disponibles')

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
                <h4 class="panel-title">Liste Equipements disponibles</h4>
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
                                        <div><i class="fa fa-check-circle text-success"></i> {{ $laboratoire->nom ?? 'N/A'}}</div>
                                    @endforeach
                                </td>

                                <!-- Statut -->
                                <td class="text-center">
                                    <span class="badge bg-success">Disponible</span>
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
                                </td>
                            </tr>
                        @empty
                            <p class="alert alert-danger text-center h4 fw-bold">Aucun équipement disponible pour le moment.</p>
                        @endforelse
                    </tbody>
                </table>
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
                                <strong>ajoutée le :</strong>
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
@include('chercheur.equipements.scripts')
