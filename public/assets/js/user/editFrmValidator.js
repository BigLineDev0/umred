document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.btn-edit-user');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = document.getElementById('editUserForm');

            form.action = this.dataset.action;
            document.getElementById('edit-user-id').value = this.dataset.id;
            document.getElementById('edit-user-prenom').value = this.dataset.prenom;
            document.getElementById('edit-user-nom').value = this.dataset.name;
            document.getElementById('edit-user-adresse').value = this.dataset.adresse;
            document.getElementById('edit-user-telephone').value = this.dataset.telephone;
            document.getElementById('edit-user-email').value = this.dataset.email;
            document.getElementById('edit-user-role').value = this.dataset.role;

            // Coche le statut
            // document.getElementById('edit-user-statut').checked = this.dataset.statut == 1;

            // Met à jour l’aperçu de la photo
            document.getElementById('edit-user-photo-preview').src = this.dataset.photo || "{{ asset('images/users/default-avatar.jpg') }}";
        });
    });
});
