document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.btn-detail-reservation');

    detailButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = document.getElementById('modal-detail-reservation');

            // Remplissage dynamique
            document.getElementById('detail-user').textContent = button.dataset.user;
            document.getElementById('detail-email').textContent = button.dataset.email;
            document.getElementById('detail-date').textContent = button.dataset.date;
            document.getElementById('detail-labo').textContent = button.dataset.labo;
            document.getElementById('detail-objectif').textContent = button.dataset.objectif;
            document.getElementById('detail-equipements').textContent = button.dataset.equipements || 'Aucun équipement';
            document.getElementById('detail-horaires').textContent = button.dataset.horaires;
            document.getElementById('detail-created').textContent = button.dataset.created;
            document.getElementById('detail-updated').textContent = button.dataset.updated;

            // Statut dynamique avec badge coloré
            const statut = button.dataset.statut.toLowerCase();
            const statutElement = document.getElementById('detail-statut');
            statutElement.textContent = button.dataset.statut;
            statutElement.className = 'badge ' + (
                statut === 'confirmée' ? 'badge-success' :
                statut === 'annulée' ? 'badge-danger' :
                statut === 'terminée' ? 'badge-secondary' :
                'badge-warning'
            );
        });
    });
});
