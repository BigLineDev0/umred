document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete-maintenance');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const maintenanceId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Confirmer la suppression',
                text: "Cette action est irréversible. Voulez-vous vraiment supprimer cette maintenance ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-maintenance-form-${maintenanceId}`).submit();
                }
            });
        });
    });
});
