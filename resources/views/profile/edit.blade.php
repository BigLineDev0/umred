@extends('dashboard')
@section('title', 'Mon Profil')
@section('titre', 'Mise à jour Profil')

@section('content')
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
            <li class="breadcrumb-item active">@yield('titre')</li>
        </ol>

        <h1 class="page-header">Mon profil</h1>

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations personnelles -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-user me-2"></i>
                            Informations personnelles
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="row">
                                <!-- Photo de profil -->
                                <div class="col-md-12 mb-4">
                                    <div class="text-center">
                                        <div class="mb-3">
                                            @if ($user->photo)
                                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil"
                                                    class="rounded-circle img-thumbnail" width="150" height="150"
                                                    id="photo-preview">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                                    style="width: 150px; height: 150px;" id="photo-preview">
                                                    <i class="fa fa-user fa-4x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Photo de profil</label>
                                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                                id="photo" name="photo" accept="image/*"
                                                onchange="previewPhoto(event)">
                                            @error('photo')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Formats acceptés: JPG, PNG, GIF. Taille maximum: 2MB
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nom -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Prénom -->
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                        id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}">
                                    @error('prenom')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Téléphone -->
                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                        id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                                        placeholder="Ex: +221 77 123 45 67">
                                    @error('telephone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Adresse -->
                                <div class="col-md-12 mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" rows="3"
                                        placeholder="Votre adresse complète">{{ old('adresse', $user->adresse) }}</textarea>
                                    @error('adresse')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Vérification email -->
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <div class="alert alert-warning" role="alert">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    <strong>Attention!</strong> Votre adresse email n'est pas vérifiée.
                                    <button form="send-verification"
                                        class="btn btn-link p-0 ms-2 text-decoration-underline">
                                        Cliquez ici pour renvoyer l'email de vérification.
                                    </button>
                                </div>

                                @if (session('status') === 'verification-link-sent')
                                    <div class="alert alert-success" role="alert">
                                        <i class="fa fa-check-circle me-2"></i>
                                        Un nouveau lien de vérification a été envoyé à votre adresse email.
                                    </div>
                                @endif
                            @endif

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-2"></i>
                                    Mettre à jour le profil
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                                        <i class="fa fa-check-circle me-2"></i>
                                        Profil mis à jour avec succès !
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Changement de mot de passe -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-lock me-2"></i>
                            Changer le mot de passe
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="row">
                                <!-- Mot de passe actuel -->
                                <div class="col-md-12 mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel <span
                                            class="text-danger">*</span></label>
                                    <input type="password"
                                        class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                        id="current_password" name="current_password" autocomplete="current-password">
                                    @error('current_password', 'updatePassword')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Nouveau mot de passe -->
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe <span
                                            class="text-danger">*</span></label>
                                    <input type="password"
                                        class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                        id="password" name="password" autocomplete="new-password">
                                    @error('password', 'updatePassword')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Confirmer le mot de passe -->
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span
                                            class="text-danger">*</span></label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation"
                                        autocomplete="new-password">
                                    @error('password_confirmation', 'updatePassword')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-key me-2"></i>
                                    Changer le mot de passe
                                </button>

                                @if (session('status') === 'password-updated')
                                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                                        <i class="fa fa-check-circle me-2"></i>
                                        Mot de passe mis à jour avec succès !
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Supprimer le compte -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            Zone dangereuse
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement
                            supprimées.
                            Cette action est irréversible.
                        </p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteAccountModal">
                            <i class="fa fa-trash me-2"></i>
                            Supprimer mon compte
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Résumé du profil -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-info-circle me-2"></i>
                            Résumé du profil
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if ($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil"
                                    class="rounded-circle img-thumbnail" width="80" height="80">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 80px; height: 80px;">
                                    <i class="fa fa-user fa-2x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <h6 class="mb-1">{{ $user->name }} {{ $user->prenom }}</h6>
                        <p class="text-muted mb-2">{{ $user->email }}</p>

                        <div class="mb-3">
                            <span
                                class="badge
                            @if ($user->hasRole('admin')) badge-danger
                            @elseif($user->hasRole('chercheur')) badge-primary
                            @elseif($user->hasRole('technicien')) badge-success @endif">
                                @if ($user->hasRole('admin'))
                                    <i class="fa fa-crown me-1"></i>
                                    Administrateur
                                @elseif($user->hasRole('chercheur'))
                                    <i class="fa fa-search me-1"></i>
                                    Chercheur
                                @elseif($user->hasRole('technicien'))
                                    <i class="fa fa-wrench me-1"></i>
                                    Technicien
                                @endif
                            </span>
                        </div>

                        <hr>

                        <div class="row text-center">
                            <div class="col-6">
                                <small class="text-muted d-block">Téléphone</small>
                                <strong>{{ $user->telephone ?: 'Non renseigné' }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Membre depuis</small>
                                <strong>{{ $user->created_at->format('M Y') }}</strong>
                            </div>
                        </div>

                        @if ($user->adresse)
                            <hr>
                            <div class="text-center">
                                <small class="text-muted d-block">Adresse</small>
                                <p class="mb-0">{{ $user->adresse }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Conseils -->
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-lightbulb me-2"></i>
                            Conseils
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fa fa-check text-success me-2"></i>
                                Utilisez une photo de profil claire
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-check text-success me-2"></i>
                                Renseignez vos informations complètes
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-check text-success me-2"></i>
                                Utilisez un mot de passe fort
                            </li>
                            <li class="mb-0">
                                <i class="fa fa-check text-success me-2"></i>
                                Vérifiez votre adresse email
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de suppression -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    <div class="modal-body">
                        @csrf
                        @method('delete')

                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Attention!</strong> Cette action est irréversible.
                        </div>

                        <p>Êtes-vous sûr de vouloir supprimer votre compte? Toutes vos données seront perdues.</p>

                        <div class="mb-3">
                            <label for="password_delete" class="form-label">
                                Tapez votre mot de passe pour confirmer
                            </label>
                            <input type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                id="password_delete" name="password" required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash me-2"></i>
                            Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Formulaire de vérification email caché -->
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>

    <script>
        // Prévisualisation de la photo
        function previewPhoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('photo-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="rounded-circle img-thumbnail" width="150" height="150" alt="Prévisualisation">`;
                }
                reader.readAsDataURL(file);
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                if (alert.querySelector('.btn-close')) {
                    alert.querySelector('.btn-close').click();
                }
            });
        }, 5000);
    </script>
@endsection
