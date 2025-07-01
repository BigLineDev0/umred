document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.btn-detail-labo');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const nom = this.dataset.nom;
            const description = this.dataset.description;
            const statut = this.dataset.statut;
            const createdAt = this.dataset.created_at;
            const updatedAt = this.dataset.updated_at;
            const laboratoires = this.dataset.laboratoires;

            // Infos texte
            document.getElementById('detail-labo-nom').textContent = nom;
            document.getElementById('detail-labo-description').textContent = description || 'Aucune description';
            document.getElementById('detail-labo-created-at').textContent = createdAt;
            document.getElementById('detail-labo-updated-at').textContent = updatedAt;

            // Affichage laboratoires
            const laboSpan = document.getElementById('detail-labo-laboratoires');
            if (laboratoires && laboratoires.length > 0) {
                laboSpan.textContent = laboratoires.split('|').join(', ');
            } else {
                laboSpan.textContent = 'Aucun laboratoire associé';
            }

            // Affichage conditionnel si modifié
            const updatedWrapper = document.getElementById('updated-at-wrapper');
            if (createdAt !== updatedAt) {
                updatedWrapper.classList.remove('d-none');
            } else {
                updatedWrapper.classList.add('d-none');
            }

            // Badge statut
            const statutElement = document.getElementById('detail-labo-statut');
            statutElement.textContent = statut;
            statutElement.className = 'badge'; // reset
            if (statut === 'disponible') {
                statutElement.classList.add('badge-success');
            } else if (statut === 'reserve') {
                statutElement.classList.add('badge-warning');
            } else if (statut === 'maintenance') {
                statutElement.classList.add('badge-danger');
            } else {
                statutElement.classList.add('badge-secondary');
            }

            // Image par défaut
            document.getElementById('detail-labo-photo').src = `{{ asset('images/equipements/default.png') }}`;
        });
    });
});
