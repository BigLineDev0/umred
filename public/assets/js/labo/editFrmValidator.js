document.addEventListener('DOMContentLoaded', function () {
    // Récuperation des champs du formulaire
    const idInputEdit = document.getElementById('edit-labo-id');
    const nomInputEdit = document.getElementById('edit-labo-nom');
    const descriptionInputEdit = document.getElementById('edit-labo-description');
    const adresseInputEdit = document.getElementById('edit-labo-localisation');
    const statutInputEdit = document.getElementById('edit-labo-statut');
    const photoInputEdit = document.getElementById('edit-labo-photo');
    const photoPreview = document.getElementById('photo-preview');
    const formEdit = document.getElementById('editLaboForm');
    const btnSubmitEdit = formEdit.querySelector('button[type="submit"]');

    // Désactiver le bouton Ajouter
    // btnSubmitEdit.disabled = true;

    // Permet d'afficher ou masquer le message d'erreur
    function showError(input, message)
    {
        const baliseP = input.nextElementSibling;
        // console.log(baliseP);
        if (message) {
            baliseP.textContent = message;
            input.classList.add('is-invalid');
            baliseP.classList.add('text-danger', 'fw-bold', 'small');
        }
        else
        {
            baliseP.textContent = "";
            input.classList.remove('is-invalid');
            baliseP.classList.remove('text-danger', 'fw-bold', 'small');
        }
    }

    // Validation du champ nom à la saisie
    nomInputEdit.addEventListener('input', () => {
        const nom = nomInputEdit.value.trim();
        const nomValidator = Validator.nameValidator("Le nom", 5, 50, nom);

        if (nomValidator)
        {
            showError(nomInputEdit, nomValidator.message);

        } else
        {
            showError(nomInputEdit, "");
        }

        checkFormValidaty();
    });

    // Validation du champ description à la saisie
    descriptionInputEdit.addEventListener('input', () => {
        const description = descriptionInputEdit.value.trim();

        if (description.length > 0) {
            const descriptionValidator = Validator.nameValidator("La description", 5, 500, description);
            showError(descriptionInputEdit, descriptionValidator ? descriptionValidator.message : "");
        } else {
            showError(descriptionInputEdit, "");
        }


    });

    // Validation du champ adresse à la saisie
    adresseInputEdit.addEventListener('input', () => {
        const adresse = adresseInputEdit.value.trim();
        const adresseValidator = Validator.addressValidator("La localisation", 2, 50, adresse);

        if (adresseValidator)
        {
            showError(adresseInputEdit, adresseValidator.message);

        } else
        {
            showError(adresseInputEdit, "");
        }

        checkFormValidaty();
    });

    // Validation du champ photo à la selection
    photoInputEdit.addEventListener('change', () => {
        const photo = photoInputEdit.files.length > 0 ? photoInputEdit.files[0] : null;

        if (!photo && !photoPreview.src)
        {
            showError(photoInputEdit, 'La photo est obligatoire.');

        } else if (photo && !photo.type.startsWith('image/')) {
            showError(photoInputEdit, 'Le fichier doit être une image.');
        } else
        {
            showError(photoInputEdit, "");
        }

        checkFormValidaty();
    });

    // Validation du champ statut à la selection
    statutInputEdit.addEventListener('change', () => {

        if (statutInputEdit.value === "")
        {
            showError(statutInputEdit, 'Veuillez selectionner un type.');
        } else
        {
            showError(statutInputEdit, "");
        }
        checkFormValidaty();
    });

    // Activer le bouton Ajouter si les champs sont valides
    function checkFormValidaty()
    {
        const nom = nomInputEdit.value.trim();
        const description = descriptionInputEdit.value.trim();
        const adresse = adresseInputEdit.value.trim();
        const type = statutInputEdit.value.trim();
        const photo = photoInputEdit.files.length > 0 ? photoInputEdit.files[0] : null;

        const isNameValid = Validator.nameValidator("Le nom", 5, 50, nom) == null;
        const isDescriptionValid = description.length === 0 || Validator.nameValidator("La description", 5, 500, description) == null;
        const isAdresseValid = Validator.addressValidator("La localisation", 2, 50, adresse) == null;
        const isPhotoValid = (photo && photo.type.startsWith('image/')) || photoPreview.src !== "";
        const isStatutValid = type !== "";

        btnSubmitEdit.disabled = !(isNameValid && isDescriptionValid && isAdresseValid && isPhotoValid && isStatutValid);
    }

    const editButtons = document.querySelectorAll('.btn-edit-labo');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
        const id = this.dataset.id;
        const nom = this.dataset.nom;
        const description = this.dataset.description;
        const adresse = this.dataset.localisation;
        const statut = this.dataset.statut;
        const photo = this.dataset.photo;

        idInputEdit.value = id;
        nomInputEdit.value = nom;
        descriptionInputEdit.value = description;
        adresseInputEdit.value = adresse;
        statutInputEdit.value = statut;

        // Mettre à jour l'appercu de la photo
        if (photo) {
            photoPreview.src = `/storage/${photo}`;
        } else {
            photoPreview.src = `/images/laboratoires/default.jpg`;
        }

        const isNameValid = Validator.nameValidator("Le nom", 5, 50, nom) == null;
        const isDescriptionValid = description.length === 0 || Validator.nameValidator("La description", 5, 500, description) == null;
        const isAdresseValid = Validator.addressValidator("La localisation", 2, 50, adresse) == null;
        const isphotoValid = photo != null;
        const isStatutValid = statut !== "";

        btnSubmitEdit.disabled = !(isNameValid && isDescriptionValid && isAdresseValid && isphotoValid && isStatutValid);

        // Changer dynamiquement l'action du formulaire
        formEdit.action = `/admin/laboratoires/${id}`;
        });
    });

});
