window.addEventListener('DOMContentLoaded', () => {

    // Récuperation des champs du formulaire
    const nomInput = document.getElementById('labo-nom');
    const descriptionInput = document.getElementById('labo-description');
    const adresseInput = document.getElementById('labo-localisation');
    const photoInput = document.getElementById('labo-photo');
    const frmAddLabo = document.getElementById('addlaboForm');
    const btnAjouter = frmAddLabo.querySelector('button[type="submit"]');

    // Désactiver le bouton Ajouter
    btnAjouter.disabled = true;

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
    nomInput.addEventListener('input', () => {
        const nom = nomInput.value.trim();
        const nomValidator = Validator.nameValidator("Le nom", 5, 100, nom);

        if (nomValidator)
        {
            showError(nomInput, nomValidator.message);

        } else
        {
            showError(nomInput, "");
        }

        checkFormValidaty();
    });

    // Validation du champ description à la saisie
    descriptionInput.addEventListener('input', () => {
        const description = descriptionInput.value.trim();

        if (description.length > 0) {
            const descriptionValidator = Validator.nameValidator("La description", 5, 500, description);
            showError(descriptionInput, descriptionValidator ? descriptionValidator.message : "");
        } else {

            showError(descriptionInput, "");
        }

        checkFormValidaty();
    });

    // Validation du champ adresse à la saisie
    adresseInput.addEventListener('input', () => {
        const adresse = adresseInput.value.trim();
        const adresseValidator = Validator.addressValidator("L'adresse", 2, 50, adresse);

        if (adresseValidator)
        {
            showError(adresseInput, adresseValidator.message);

        } else
        {
            showError(adresseInput, "");
        }

        checkFormValidaty();
    });

    // Validation du champ photo à la selection
    photoInput.addEventListener('change', () => {
        const photo = photoInput.files[0];

        if (!photo)
        {
            showError(photoInput, 'La photo est obligatoire.');

        } else if (!photo.type.startsWith('image/')) {
            showError(photoInput, 'Le fichier doit être une image.');
        } else
        {
            showError(photoInput, "");
        }

        checkFormValidaty();
    });

    // Activer le bouton Ajouter si les champs sont valides
    function checkFormValidaty()
    {
        const nom = nomInput.value.trim();
        const description = descriptionInput.value.trim();
        const adresse = adresseInput.value.trim();

        const isNameValid = Validator.nameValidator("Le nom", 5, 100, nom) == null;
        const isDescriptionValid = description.length === 0 || Validator.nameValidator("La description", 5, 500, description) == null;
        const isAdresseValid = Validator.addressValidator("La localisation", 2, 50, adresse) == null;

        btnAjouter.disabled = !(isNameValid && isDescriptionValid && isAdresseValid);
    }


    // Desactiver le bouton ajouter apres cliquer sur annuler
    frmAddLabo.addEventListener('reset', () => {
        btnAjouter.disabled = true;
    });

});
