
document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.btn-detail-user');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const nom = this.dataset.nom;
            const prenom = this.dataset.prenom;
            const email = this.dataset.email;
            const telephone = this.dataset.telephone;
            const adresse = this.dataset.adresse;
            const statut = this.dataset.statut;
            const role = this.dataset.role;
            const photo = this.dataset.photo;
            const createdAt = this.dataset.created_at;
            const updatedAt = this.dataset.updated_at;

            document.getElementById('detail-user-fullname').textContent = `${prenom} ${nom}`;
            document.getElementById('detail-user-email').textContent = email;
            document.getElementById('detail-user-telephone').textContent = telephone;
            document.getElementById('detail-user-adresse').textContent = adresse;
            document.getElementById('detail-user-role').textContent = role;

            // Afficher le statut avec une couleur
            const statutSpan = document.getElementById('detail-user-statut');
            statutSpan.textContent = statut == 1 ? 'Actif' : 'Inactif';
            statutSpan.className = 'badge ' + (statut == 1 ? 'badge-success' : 'badge-secondary');

            document.getElementById('detail-user-created-at').textContent = createdAt;

            // Afficher ou non la date de modification
            const updatedWrapper = document.getElementById('updated-at-wrapper');
            if (createdAt !== updatedAt) {
                document.getElementById('detail-user-updated-at').textContent = updatedAt;
                updatedWrapper.classList.remove('d-none');
            } else {
                updatedWrapper.classList.add('d-none');
            }

            // Gestion de la photo
            const photoUrl = photo ? `/storage/${photo}` : `/images/users/default-avatar.jpg`;
            document.getElementById('detail-user-photo').src = photoUrl;
        });
    });
});
