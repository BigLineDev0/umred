document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-detail-labo');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const nom = this.dataset.nom;
            const description = this.dataset.description;
            const localisation = this.dataset.localisation;
            const statut = this.dataset.statut;
            const photo = this.dataset.photo;
            const createdAt = this.dataset.created_at;
            const updatedAt = this.dataset.updated_at;

            document.getElementById('detail-labo-nom').textContent = nom;
            document.getElementById('detail-labo-description').textContent = description || 'Non renseignée';
            document.getElementById('detail-labo-localisation').textContent = localisation || 'Non spécifiée';
            document.getElementById('detail-labo-created-at').textContent = createdAt;

            // Statut avec badge stylé
            const statutBadge = document.getElementById('detail-labo-statut');
            statutBadge.textContent = statut;
            statutBadge.className = 'badge'; // reset classes
            if (statut === 'actif') statutBadge.classList.add('badge-success');
            else if (statut === 'inactif') statutBadge.classList.add('badge-secondary');
            else if (statut === 'maintenance') statutBadge.classList.add('badge-warning');

            // Image preview
            const img = document.getElementById('detail-labo-photo');
            img.src = photo ? `/storage/${photo}` : `/images/laboratoires/default.jpg`;

            // Modification date conditionnelle
            const updatedAtWrapper = document.getElementById('updated-at-wrapper');
            const updatedAtSpan = document.getElementById('detail-labo-updated-at');
            if (updatedAt !== createdAt) {
                updatedAtSpan.textContent = updatedAt;
                updatedAtWrapper.classList.remove('d-none');
            } else {
                updatedAtWrapper.classList.add('d-none');
            }
        });
    });
});

