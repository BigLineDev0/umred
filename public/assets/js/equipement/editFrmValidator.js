document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.btn-edit-equipement');
    const form = document.getElementById('editEquipementForm');
    const nomInput = document.getElementById('edit-equipement-nom');
    const descriptionInput = document.getElementById('edit-equipement-description');
    const statutInput = document.getElementById('edit-equipement-statut');
    const laboInputs = document.querySelectorAll('.edit-labo-checkbox');
    const btnModifier = form.querySelector('button[type="submit"]');

    const laboErrorMessage = document.querySelector('input[name="laboratoires[]"]').closest('.mb-3').querySelector('.error-message');

    // Initialement désactiver le bouton
    btnModifier.disabled = true;

    // Fonction d'affichage des erreurs
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

    // Pour message labo (hors input direct)
    function showErrorCustom(container, message) {
        if (message) {
            container.textContent = message;
            container.classList.add('text-danger', 'fw-bold', 'small');
        } else {
            container.textContent = "";
            container.classList.remove('text-danger', 'fw-bold', 'small');
        }
    }

    // Validation champ nom
    nomInput.addEventListener('input', () => {
        const nom = nomInput.value.trim();
        const result = Validator.nameValidator("Le nom", 5, 50, nom);
        showError(nomInput, result ? result.message : "");
        checkFormValidity();
    });

    // Validation champ description
    descriptionInput.addEventListener('input', () => {
        const description = descriptionInput.value.trim();
        const result = description.length > 0 ? Validator.nameValidator("La description", 5, 500, description) : null;
        showError(descriptionInput, result ? result.message : "");
        checkFormValidity();
    });

    // Validation statut
    statutInput.addEventListener('change', () => {
        if (statutInput.value === "") {
            showError(statutInput, 'Veuillez sélectionner un statut.');
        } else {
            showError(statutInput, "");
        }
        checkFormValidity();
    });

    // Validation labo
    function validateLaboSelection() {
        const isAnyChecked = Array.from(laboInputs).some(cb => cb.checked);
        showErrorCustom(laboErrorMessage, isAnyChecked ? "" : "Veuillez sélectionner au moins un laboratoire.");
        checkFormValidity();
    }

    laboInputs.forEach(cb => {
        cb.addEventListener('change', validateLaboSelection);
    });

    // Vérifie la validité globale
    function checkFormValidity() {
        const nom = nomInput.value.trim();
        const description = descriptionInput.value.trim();
        const statut = statutInput.value.trim();

        const isNameValid = Validator.nameValidator("Le nom", 5, 50, nom) == null;
        const isDescriptionValid = description.length === 0 || Validator.nameValidator("La description", 5, 500, description) == null;
        const isStatutValid = statut !== "";
        const isLaboValid = Array.from(laboInputs).some(cb => cb.checked);

        btnModifier.disabled = !(isNameValid && isDescriptionValid && isStatutValid && isLaboValid);
    }

    // Préremplissage des champs lors du clic sur "modifier"
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const nom = this.dataset.nom;
            const description = this.dataset.description;
            const statut = this.dataset.statut;
            const action = this.dataset.action;
            const laboIds = JSON.parse(this.dataset.laboratoires.replace(/&quot;/g, '"'));

            document.getElementById('edit-equipement-id').value = id;
            nomInput.value = nom;
            descriptionInput.value = description;
            statutInput.value = statut;

            // Décocher tout
            laboInputs.forEach(checkbox => checkbox.checked = false);

            // Cocher les bons labos
            laboIds.forEach(id => {
                const cb = document.querySelector(`#edit-labo${id}`);
                if (cb) cb.checked = true;
            });

            // Action formulaire
            form.action = action;

            // Lancer validation immédiate
            showError(nomInput, "");
            showError(descriptionInput, "");
            showError(statutInput, "");
            showErrorCustom(laboErrorMessage, "");
            checkFormValidity();
        });
    });

    // Désactiver bouton si on reset
    form.addEventListener('reset', () => {
        btnModifier.disabled = true;
    });
});
