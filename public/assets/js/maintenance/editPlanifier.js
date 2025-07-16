document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.btn-edit-maintenance');
    const form = document.getElementById('editMaintenanceForm');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const maintenanceId = this.dataset.id;
            const equipementId = this.dataset.equipementId;
            const datePrevue = this.dataset.datePrevue;
            const description = this.dataset.description;
            const statut = this.dataset.statut;
            const action = this.dataset.action;

            // Remplir le formulaire
            form.action = action;
            document.getElementById('edit-maintenance-id').value = maintenanceId;
            document.getElementById('edit-maintenance-equipement').value = equipementId;
            document.getElementById('edit-maintenance-date').value = datePrevue;
            document.getElementById('edit-maintenance-description').value = description;
            document.getElementById('edit-maintenance-statut').value = statut;
        });
    });
});
