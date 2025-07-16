document.addEventListener('DOMContentLoaded', function () {
    const terminerButtons = document.querySelectorAll('.btn-terminer-maintenance');

    terminerButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const maintenanceId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Confirmer la fin de maintenance',
                text: "Voulez-vous vraiment marquer cette maintenance comme terminée ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, Terminer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`terminer-form-${maintenanceId}`).submit();
                }
            });
        });
    });
});