document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.btn-edit-equipement');
    const form = document.getElementById('editEquipementForm');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const nom = this.dataset.nom;
            const description = this.dataset.description;
            const statut = this.dataset.statut;
            const action = this.dataset.action;
            const laboIds = JSON.parse(this.dataset.laboratoires.replace(/&quot;/g, '"'));

            // Remplir les champs
            document.getElementById('edit-equipement-id').value = id;
            document.getElementById('edit-equipement-nom').value = nom;
            document.getElementById('edit-equipement-description').value = description;
            document.getElementById('edit-equipement-statut').value = statut;

            // Décocher tout
            document.querySelectorAll('.edit-labo-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Cocher les laboratoires déjà associés
            laboIds.forEach(id => {
                const cb = document.querySelector(`#edit-labo${id}`);
                if (cb) cb.checked = true;
            });

            // Mettre à jour l’action du formulaire
            form.action = action;
        });
    });
});

