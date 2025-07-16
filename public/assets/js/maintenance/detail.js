document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.btn-detail-maintenance');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('detail-maintenance-utilisateur').textContent = this.getAttribute('data-utilisateur') || 'Non renseigné';
            document.getElementById('detail-maintenance-equipement').textContent = this.getAttribute('data-equipement') || 'Non défini';
            document.getElementById('detail-maintenance-description').textContent = this.getAttribute('data-description') || 'Non renseignée';
            document.getElementById('detail-maintenance-date-prevue').textContent = this.getAttribute('data-date-prevue') || '-';
            document.getElementById('detail-maintenance-created-at').textContent = this.getAttribute('data-created-at') || '-';
            const updatedAt = this.getAttribute('data-updated-at') || '';
            const statut = this.getAttribute('data-statut') || '';

            // Statut dynamique
            const statutSpan = document.getElementById('detail-maintenance-statut');
            statutSpan.textContent = statut;

            if (statut === 'en_cours') {
                statutSpan.className = 'badge badge-warning px-2 py-1';
            } else if (statut === 'terminée') {
                statutSpan.className = 'badge badge-success px-2 py-1';
            } else {
                statutSpan.className = 'badge badge-secondary px-2 py-1';
            }

            if (updatedAt) {
                document.getElementById('detail-maintenance-updated-at').textContent = updatedAt;
                document.getElementById('updated-at-wrapper').classList.remove('d-none');
            } else {
                document.getElementById('updated-at-wrapper').classList.add('d-none');
            }
        });
    });
});
