document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editUserForm');

    const prenomInput = document.getElementById('edit-user-prenom');
    const nomInput = document.getElementById('edit-user-nom');
    const adresseInput = document.getElementById('edit-user-adresse');
    const telephoneInput = document.getElementById('edit-user-telephone');
    const emailInput = document.getElementById('edit-user-email');
    const photoInput = document.getElementById('edit-user-photo');
    const roleInput = document.getElementById('edit-user-role');
    const btnModifier = document.getElementById('btnEditUser');

    // Désactiver le bouton au départ
    btnModifier.disabled = true;

    // Affichage des erreurs
    function showError(input, message) {
        const baliseP = input.nextElementSibling;
        if (message) {
            baliseP.textContent = message;
            input.classList.add('is-invalid');
            baliseP.classList.add('text-danger', 'fw-bold', 'small');
        } else {
            baliseP.textContent = "";
            input.classList.remove('is-invalid');
            baliseP.classList.remove('text-danger', 'fw-bold', 'small');
        }
    }

    // Validation prénom
    prenomInput.addEventListener('input', () => {
        const val = prenomInput.value.trim();
        const result = Validator.nameValidator("Le prénom", 2, 50, val);
        showError(prenomInput, result ? result.message : "");
        checkFormValidaty();
    });

    // Validation nom
    nomInput.addEventListener('input', () => {
        const val = nomInput.value.trim();
        const result = Validator.nameValidator("Le nom", 2, 50, val);
        showError(nomInput, result ? result.message : "");
        checkFormValidaty();
    });

    // Validation adresse
    adresseInput.addEventListener('input', () => {
        const val = adresseInput.value.trim();
        const result = Validator.addressValidator("L'adresse", 2, 50, val);
        showError(adresseInput, result ? result.message : "");
        checkFormValidaty();
    });

    // Validation téléphone
    telephoneInput.addEventListener('input', () => {
        const val = telephoneInput.value.trim();
        const result = Validator.phoneValidator("Le numéro de téléphone", 9, 17, val);
        showError(telephoneInput, result ? result.message : "");
        checkFormValidaty();
    });

    // Validation email
    emailInput.addEventListener('input', () => {
        const val = emailInput.value.trim();
        const result = Validator.emailValidator("L'email", val);
        showError(emailInput, result ? result.message : "");
        checkFormValidaty();
    });

    // Validation rôle
    roleInput.addEventListener('change', () => {
        const val = roleInput.value.trim();
        if (val === "") {
            showError(roleInput, "Veuillez sélectionner un rôle.");
        } else {
            showError(roleInput, "");
        }
        checkFormValidaty();
    });

    // Validation photo (si une nouvelle image est sélectionnée)
    photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        if (file && !file.type.startsWith('image/')) {
            showError(photoInput, "Le fichier doit être une image.");
        } else {
            showError(photoInput, "");
        }
        checkFormValidaty();
    });

    // Validation globale
    function checkFormValidaty() {
        const prenom = prenomInput.value.trim();
        const nom = nomInput.value.trim();
        const adresse = adresseInput.value.trim();
        const telephone = telephoneInput.value.trim();
        const email = emailInput.value.trim();
        const role = roleInput.value.trim();

        const isPrenomValid = Validator.nameValidator("Le prénom", 2, 50, prenom) == null;
        const isNomValid = Validator.nameValidator("Le nom", 2, 50, nom) == null;
        const isAdresseValid = Validator.addressValidator("L'adresse", 2, 50, adresse) == null;
        const isTelephoneValid = Validator.phoneValidator("Le numéro de téléphone", 9, 17, telephone) == null;
        const isEmailValid = Validator.emailValidator("L'email", email) == null;
        const isRoleValid = role !== "";

        btnModifier.disabled = !(isPrenomValid && isNomValid && isAdresseValid && isTelephoneValid && isEmailValid && isRoleValid);
    }

    // Préremplissage via bouton Modifier
    const editButtons = document.querySelectorAll('.btn-edit-user');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            form.action = this.dataset.action;

            document.getElementById('edit-user-id').value = this.dataset.id;
            prenomInput.value = this.dataset.prenom;
            nomInput.value = this.dataset.name;
            adresseInput.value = this.dataset.adresse;
            telephoneInput.value = this.dataset.telephone;
            emailInput.value = this.dataset.email;
            roleInput.value = this.dataset.role;

            document.getElementById('edit-user-photo-preview').src = this.dataset.photo || "{{ asset('images/users/default-avatar.jpg') }}";

            // Réinitialise les erreurs
            showError(prenomInput, "");
            showError(nomInput, "");
            showError(adresseInput, "");
            showError(telephoneInput, "");
            showError(emailInput, "");
            showError(roleInput, "");

            checkFormValidaty();
        });
    });

    // Désactiver après reset
    form.addEventListener('reset', () => {
        btnModifier.disabled = true;
    });
});