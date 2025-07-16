@extends('dashboard')
@section('title', 'Tableau de Admin')
@section('titre', 'Utilisateurs')

@section('content')
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item">
                <a href="#modal-add-user" class="btn btn-sm btn-dark text-white" data-toggle="modal">Ajouter</a>
            </li>

            <li id="btn-show-liste-user" class="breadcrumb-item">
                <a href="#" class="btn btn-sm btn-dark text-white">Afficher liste</a>
            </li>
        </ol>

        <h1 class="page-header"># Utilisateurs</h1>

        <!-- Liste Utilisateurs -->
        <div id="table-liste-user" class="panel panel-inverse">

            <div class="panel-heading">
                <h4 class="panel-title">Liste Utilisateurs</h4>
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
                            <th class="text-nowrap text-center">Nom complet</th>
                            <th class="text-nowrap text-center">Email</th>
                            <th class="text-nowrap text-center">Adresse</th>
                            <th class="text-nowrap text-center">Rôle</th>
                            <th class="text-nowrap text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="odd gradeX">

                                <!-- Index -->
                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Photo -->
                                <td class="text-center">
                                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/users/default-avatar.jpg') }}"
                                        alt="Photo de {{ $user->prenom }} {{ $user->name }}"
                                        class="img-fluid rounded-circle" style="width: 50px; height: 50px;">
                                </td>

                                <!-- Nom complet-->
                                <td class="text-center">
                                    {{ $user->prenom }} {{ $user->name }}
                                </td>

                                <!-- Email -->
                                <td class="text-center">
                                    {{ $user->email }}
                                </td>

                                <!-- Adresse -->
                                <td class="text-center">
                                    {{ $user->adresse }}
                                </td>

                                <!-- Rôle -->
                                <td class="text-center">
                                    <span class="badge badge-info">{{ $user->getRoleNames()->first() }}</span>
                                </td>

                                <!-- Actions -->
                                <td class="text-center">

                                    <!-- Bouton Détails -->
                                    <a href="#" class="btn-detail-user" data-id="{{ $user->id }}"
                                        data-nom="{{ $user->name }}" data-prenom="{{ $user->prenom }}"
                                        data-email="{{ $user->email }}" data-telephone="{{ $user->telephone }}"
                                        data-adresse="{{ $user->adresse }}" data-statut="{{ $user->statut }}"
                                        data-role="{{ $user->getRoleNames()->first() }}" data-photo="{{ $user->photo }}"
                                        data-created_at="{{ $user->created_at->format('d/m/Y à H:i') }}"
                                        data-updated_at="{{ $user->updated_at->format('d/m/Y à H:i') }}"
                                        data-toggle="modal" data-target="#modal-detail-user" title="Voir détails">
                                        <i class="fa fa-eye btn btn-info"></i>
                                    </a>

                                    <a href="javascript:;" class="btn-edit-user"
                                        data-id="{{ $user->id }}"
                                        data-action="{{ route('admin.utilisateurs.update', $user->id) }}"
                                        data-prenom="{{ $user->prenom }}" data-name="{{ $user->name }}"
                                        data-adresse="{{ $user->adresse }}" data-telephone="{{ $user->telephone }}"
                                        data-email="{{ $user->email }}" data-role="{{ $user->getRoleNames()->first() }}"
                                        {{-- data-statut="{{ $user->statut }}" --}}
                                        data-photo="{{ asset('storage/' . $user->photo) }}"
                                        data-toggle="modal"
                                        data-target="#modal-edit-user" title="Modifier">
                                        <i class="fa fa-edit btn btn-success"></i>
                                    </a>


                                    <!-- Supprimer -->
                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('admin.utilisateurs.destroy', $user) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-danger delete-btn"
                                        data-id-user="{{ $user->id }}" data-nom-user="{{ $user->name }}"
                                        data-prenom-user="{{ $user->prenom }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <p class="alert alert-danger text-center h4 fw-bold">La liste des utilisateurs est vide.</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========== Modal add utilisateur ========== -->
    <div class="modal fade" id="modal-add-user" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Nouveau utilisateur</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <form id="addUserForm" action="{{ route('admin.utilisateurs.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- Prénom -->
                        <div class="mb-3">
                            <label for="user-prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" class="form-control" id="user-prenom"
                                placeholder="Prénom" required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('prenom')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="user-nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="user-nom" placeholder="Nom"
                                required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="mb-3">
                            <label for="user-adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" name="adresse" class="form-control" id="user-adresse"
                                placeholder="Adresse" required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('adresse')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Téléphone -->
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" name="telephone" class="form-control" id="user-telephone"
                                placeholder="Téléphone" required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('telephone')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="user-email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="user-email"
                                placeholder="Email" required>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Rôle -->
                        <div class="mb-3">
                            <label for="user-role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select class="form-control" name="role" id="user-role" required>
                                <option value="">--Selectionner un rôle--</option>
                                <option value="admin">Admin</option>
                                <option value="chercheur">Chercheur</option>
                                <option value="technicien">Technicien</option>
                            </select>
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('role')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Photo -->
                        <div class="mb-3">
                            <label for="user-photo" class="form-label">Photo</label><br>
                            <input type="file" name="photo" class="form-control-file" id="user-photo"
                                accept="image/*">
                            <p class="error-message mt-2"></p>
                            <div class="text-danger small">
                                @error('photo')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="text-center">
                            <button type="submit" name="frmAddUser" class="btn btn-primary fw-bold"
                                id="btnAddUser">Ajouter</button>
                            &nbsp; &nbsp;
                            <button type="reset" class="btn btn-danger fw-bold">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Détails Utilisateur -->
    <div class="modal fade" id="modal-detail-user" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-labelledby="modalDetailUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-sm border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDetailUserLabel">Détails de l'utilisateur</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Colonne gauche -->
                        <div class="col-md-4 text-center">
                            <img id="detail-user-photo" src="" alt="Photo utilisateur"
                                class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                        </div>

                        <!-- Colonne droite -->
                        <div class="col-md-8">
                            <h4 class="text-primary" id="detail-user-fullname"></h4>
                            <p><strong>Email :</strong> <span id="detail-user-email"></span></p>
                            <p><strong>Téléphone :</strong> <span id="detail-user-telephone"></span></p>
                            <p><strong>Adresse :</strong> <span id="detail-user-adresse"></span></p>
                            <p><strong>Rôle :</strong> <span id="detail-user-role" class="badge badge-info"></span></p>
                            <p><strong>Statut :</strong> <span id="detail-user-statut" class="badge"></span></p>
                            <p><strong>Créé le :</strong> <span id="detail-user-created-at"></span>
                                par
                                <span class="text-muted">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</span>
                            </p>
                            <p id="updated-at-wrapper" class="d-none">
                                <strong>Dernière modification :</strong> <span id="detail-user-updated-at"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== Modal Edit utilisateur ========== -->
    <div class="modal fade" id="modal-edit-user" data-backdrop="static" data-keyboard="false">

        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title">Modifier l'utilisateur</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="editUserForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" id="edit-user-id">

                        <!-- Prénom -->
                        <div class="mb-3">
                            <label for="edit-user-prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" class="form-control" id="edit-user-prenom" required>
                            <p class="error-message mt-2"></p>
                        </div>

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="edit-user-nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="edit-user-nom" required>
                            <p class="error-message mt-2"></p>
                        </div>

                        <!-- Adresse -->
                        <div class="mb-3">
                            <label for="edit-user-adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" name="adresse" class="form-control" id="edit-user-adresse" required>
                            <p class="error-message mt-2"></p>
                        </div>

                        <!-- Téléphone -->
                        <div class="mb-3">
                            <label for="edit-user-telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" name="telephone" class="form-control" id="edit-user-telephone"
                                required>
                            <p class="error-message mt-2"></p>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="edit-user-email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="edit-user-email" required>
                            <p class="error-message mt-2"></p>
                        </div>

                        <!-- Rôle -->
                        <div class="mb-3">
                            <label for="edit-user-role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select class="form-control" name="role" id="edit-user-role" required>
                                <option value="">-- Sélectionner un rôle --</option>
                                <option value="admin">Admin</option>
                                <option value="chercheur">Chercheur</option>
                                <option value="technicien">Technicien</option>
                            </select>
                            <p class="error-message mt-2"></p>
                        </div>

                        {{-- <!-- Statut -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="statut" class="form-check-input" id="edit-user-statut"
                                value="1">
                            <label class="form-check-label" for="edit-user-statut">Actif</label>
                        </div> --}}

                        <!-- Photo -->
                        <div class="mb-3">
                            <label for="edit-user-photo" class="form-label">Photo</label><br>
                            <input type="file" name="photo" class="form-control-file" id="edit-user-photo"
                                accept="image/*">
                            <div class="mb-3 text-center">
                                <label class="form-label">Photo actuelle</label><br>
                                <img id="edit-user-photo-preview" src="" alt="Photo utilisateur" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                            <p class="error-message mt-2"></p>
                        </div>

                        <!-- Boutons -->
                        <div class="text-center">
                            <button type="submit" id="btnEditUser" class="btn btn-success fw-bold">Modifier</button>
                            &nbsp;&nbsp;
                            <button type="reset" class="btn btn-danger fw-bold">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@include('admin.utilisateurs.scripts')
