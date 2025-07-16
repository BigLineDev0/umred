window.addEventListener('DOMContentLoaded', () => {

    // Récuperation des champs du formulaire
    const nomInput = document.getElementById('equipement-nom');
    const descriptionInput = document.getElementById('equipement-description');
    const statutInput = document.getElementById('equipement-statut');
    const laboInput = document.getElementById('labo');
    const frmAdEquipement = document.getElementById('addEquipementForm');
    const btnAjouter = frmAdEquipement.querySelector('button[type="submit"]');

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
        const nomValidator = Validator.nameValidator("Le nom", 5, 50, nom);

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

    // Validation du champ type à la selection
    statutInput.addEventListener('change', () => {

        if (statutInput.value === "")
        {
            showError(statutInput, 'Veuillez selectionner un statut.');
        } else
        {
            showError(statutInput, "");
        }
        checkFormValidaty();
    });

    // Validation des laboratoires associés
    const laboCheckboxes = document.querySelectorAll('input[name="laboratoires[]"]');
    const laboErrorMessage = document.querySelector('input[name="laboratoires[]"]').closest('.mb-3').querySelector('.error-message');

    function validateLaboSelection() {
        const isAnyChecked = Array.from(laboCheckboxes).some(cb => cb.checked);

        if (!isAnyChecked) {
            laboErrorMessage.textContent = "Veuillez sélectionner au moins un laboratoire.";
            laboErrorMessage.classList.add('text-danger', 'fw-bold', 'small');
        } else {
            laboErrorMessage.textContent = "";
            laboErrorMessage.classList.remove('text-danger', 'fw-bold', 'small');
        }

        checkFormValidaty();
    }

    // Ajouter un écouteur sur chaque checkbox
    laboCheckboxes.forEach(cb => {
        cb.addEventListener('change', validateLaboSelection);
    });



    // Activer le bouton Ajouter si les champs sont valides
    function checkFormValidaty()
    {
        const nom = nomInput.value.trim();
        const description = descriptionInput.value.trim();
        const statut = statutInput.value.trim();

        const isNameValid = Validator.nameValidator("Le nom", 5, 50, nom) == null;
        const isDescriptionValid = description.length === 0 || Validator.nameValidator("La description", 5, 500, description) == null;
        const isstatutValid = statut !== "";
        const isLaboValid = Array.from(laboCheckboxes).some(cb => cb.checked);

        btnAjouter.disabled = !(isNameValid && isDescriptionValid && isstatutValid && isLaboValid);
    }


    // Desactiver le bouton ajouter apres cliquer sur annuler
    frmAdEquipement.addEventListener('reset', () => {
        btnAjouter.disabled = true;
    });

});
