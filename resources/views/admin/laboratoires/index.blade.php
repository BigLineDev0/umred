@extends('dashboard')
@section('title', 'Tableau de Admin')
@section('titre', 'Laboratoires')

@section('content')
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item">
                <a href="#modal-add-labo" class="btn btn-sm btn-dark text-white" data-toggle="modal">Ajouter</a>
            </li>

            <li id="btn-show-liste-user" class="breadcrumb-item">
                <a href="#" class="btn btn-sm btn-dark text-white">Afficher liste</a>
            </li>
        </ol>

        <h1 class="page-header"># Laboratoires</h1>

        <!-- Liste Utilisateur -->
        <div id="table-liste-user" class="panel panel-inverse">

            <div class="panel-heading">
                <h4 class="panel-title">Liste Laboratoires</h4>
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
                            <th class="text-nowrap text-center">Photo</th>
                            <th class="text-nowrap text-center">Nom</th>
                            <th class="text-nowrap text-center">Localisation</th>
                            <th class="text-nowrap text-center">Statut</th>
                            <th class="text-nowrap text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laboratoires as $index => $laboratoire)
                            <tr class="odd gradeX">
                                <!-- Index -->
                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Photo -->
                                <td width="1%" class="with-img text-center">
                                    @if (!empty($laboratoire->photo))
                                        <img src="{{ asset('storage/' . $laboratoire->photo) }}"
                                            style="width: 40px;" class="img-rounded height-30" />
                                    @else
                                        <img src="{{ asset('images/laboratoires/default.jpg') }}"
                                            class="img-rounded height-30" />
                                    @endif
                                </td>

                                <!-- Nom -->
                                <td class="text-center">
                                    {{ $laboratoire->nom }}
                                </td>

                                <!-- Adresse -->
                                <td class="text-center">
                                    {{ $laboratoire->localisation }}
                                </td>

                                <!-- Statut -->
                                <td class="text-center">
                                    @if ($laboratoire->statut == 'actif')
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($laboratoire->statut == 'inactif')
                                        <span class="badge bg-danger">Indisponible</span>
                                    @else
                                        <span class="badge bg-warning">Maintenance</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="text-center">
                                    <!-- Detail -->
                                    <a href="#" class="btn-detail-labo" data-id="{{ $laboratoire->id }}"
                                        data-nom="{{ $laboratoire->nom }}"
                                        data-description="{{ $laboratoire->description }}"
                                        data-localisation="{{ $laboratoire->localisation }}"
                                        data-statut="{{ $laboratoire->statut }}" data-photo="{{ $laboratoire->photo }}"
                                        data-created_at="{{ $laboratoire->created_at->format('d/m/Y à H:i') }}"
                                        data-updated_at="{{ $laboratoire->updated_at->format('d/m/Y à H:i') }}"
                                        data-toggle="modal" data-target="#modal-detail-labo" title="Voir détails">
                                        <i class="fa fa-eye btn btn-info"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="javascript:;" class="btn-edit-labo" data-id="{{ $laboratoire->id }}"
                                        data-nom="{{ $laboratoire->nom }}"
                                        data-description="{{ $laboratoire->description }}"
                                        data-localisation="{{ $laboratoire->localisation }}"
                                        data-statut="{{ $laboratoire->statut }}" data-photo="{{ $laboratoire->photo }}"
                                        data-toggle="modal" data-target="#modal-edit-labo" title="Modifier">
                                        <i class="fa fa-edit btn btn-success"></i>
                                    </a>

                                    <!-- Supprimer -->
                                    <form id="delete-form-{{ $laboratoire->id }}"
                                        action="{{ route('admin.laboratoires.destroy', $laboratoire) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-danger delete-btn"
                                        data-id="{{ $laboratoire->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <p class="alert alert-danger text-center h4 fw-bold">La liste des laboratoires est vide.</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========== Modal add laboratoire ========== -->
    <div class="modal fade" id="modal-add-labo" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Ajouter un laboratoire</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <form id="addlaboForm" action="{{ route('admin.laboratoires.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="labo-nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" id="labo-nom" placeholder="Nom"
                                required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('nom')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- localisation -->
                        <div class="mb-3">
                            <label for="labo-localisation" class="form-label">Localisation <span class="text-danger">*</span></label>
                            <input type="text" name="localisation" class="form-control" id="labo-localisation"
                                placeholder="Adresse" required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('localisation')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="labo-description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="labo-description" rows="3" placeholder="Description"></textarea>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Photo -->
                        <div class="mb-3">
                            <label for="labo-photo" class="form-label">Photo</label><br>
                            <input type="file" name="photo" class="form-control-file" id="labo-photo"
                                accept="image/*">
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('photo')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Btn soumition -->
                        <div style="display: flex; justify-content: center;">
                            <button type="submit" name="frmAdLabo" class="btn btn-primary fw-bold"
                                id="btnAddLabo">Ajouter</button>
                            &nbsp; &nbsp;
                            <button type="reset" class="btn btn-danger fw-bold">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== Modal edit laboratoire ========== -->
    <div class="modal fade" id="modal-edit-labo" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Modifier un laboratoire</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <form id="editLaboForm" action="{{ route('admin.laboratoires.update', $laboratoire) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="edit-labo-nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="hidden" name="edit-labo-id" id="edit-labo-id" value="">
                            <input type="text" name="nom" class="form-control" id="edit-labo-nom" required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('nom')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- localisation -->
                        <div class="mb-3">
                            <label for="edit-labo-localisation" class="form-label">Localisation <span class="text-danger">*</span></label>
                            <input type="text" name="localisation" class="form-control" id="edit-labo-localisation"
                                placeholder="Adresse" required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('localisation')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="edit-labo-description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="edit-labo-description" rows="3"
                                placeholder="Description"></textarea>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="edit-labo-statut" class="form-label">Statut <span class="text-danger">*</span></label>
                            <select class="form-control" name="statut" id="edit-labo-statut" required>
                                <option value="">--Selectionner un statut--</option>
                                <option value="actif">Disponible</option>
                                <option value="inactif">indisponible</option>
                            </select>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('statut')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Photo -->
                        <div class="mb-3">
                            <label for="edit-labo-photo" class="form-label">Photo</label><br>
                            <input type="file" name="photo" class="form-control-file" id="edit-labo-photo"
                                accept="image/*">
                            <div class="image-preview-container">
                                <img src="" id="photo-preview" alt="Aperçu de l'image">
                            </div>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('photo')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Btn soumition -->
                        <div style="display: flex; justify-content: center;">
                            <button type="submit" name="frmEditLabo" class="btn btn-primary fw-bold"
                                id="btnEditLabo">Modifier</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== Modal de détails laboratoire ========== -->
    <div class="modal fade" id="modal-detail-labo" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalDetailLaboLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-sm border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDetailLaboLabel">Détails du laboratoire</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Photo -->
                        <div class="col-md-4 text-center">
                            <img id="detail-labo-photo" src="" alt="Photo du laboratoire"
                                class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                        </div>
                        <!-- Infos -->
                        <div class="col-md-8">
                            <h4 id="detail-labo-nom" class="text-primary"></h4>
                            <p><strong>Description :</strong> <span id="detail-labo-description"></span></p>
                            <p><strong>Localisation :</strong> <span id="detail-labo-localisation"></span></p>
                            <p><strong>Statut :</strong> <span id="detail-labo-statut" class="badge"></span></p>
                            <p><strong>Créé le :</strong> <span id="detail-labo-created-at"></span>
                                par
                                <span class="text-muted">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</span>
                            </p>
                            <p id="updated-at-wrapper" class="d-none">
                                <strong>Dernière modification :</strong> <span id="detail-labo-updated-at"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@include('admin.laboratoires.scripts')
