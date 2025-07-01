document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.btn-edit-reservation');
    const form = document.getElementById('editReservationForm');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Récupération des données
            const id = button.getAttribute('data-id');
            const date = button.getAttribute('data-date');
            const objectif = button.getAttribute('data-objectif');
            const laboId = button.getAttribute('data-labo-id');
            const equipements = (button.getAttribute('data-equipements') || '').split(',');
            const horaires = (button.getAttribute('data-horaires') || '').split(',');

            // Remplissage des champs du formulaire
            document.getElementById('edit-reservation-id').value = id;
            document.getElementById('edit-reservation-date').value = date;
            document.getElementById('edit-reservation-objectif').value = objectif;
            document.getElementById('edit-reservation-laboratoire').value = laboId;

            // Réinitialise tous les checkboxes
            document.querySelectorAll('.edit-equipement-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.edit-horaire-checkbox').forEach(cb => cb.checked = false);

            // Coche les équipements existants
            equipements.forEach(eqId => {
                const checkbox = document.getElementById(`edit-eq${eqId}`);
                if (checkbox) checkbox.checked = true;
            });

            // Coche les horaires existants
            horaires.forEach(hId => {
                const checkbox = document.getElementById(`edit-horaire${hId}`);
                if (checkbox) checkbox.checked = true;
            });

            // Modifie dynamiquement l'action du formulaire si nécessaire
            form.action = `/chercheur/reservations/${id}`;
        });
    });
});
