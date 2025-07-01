document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.annuler-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const reservationId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Voulez-vous vraiment annuler cette réservation ?',
                text: "Cette action est irréversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, annuler',
                cancelButtonText: 'Non'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`annuler-form-${reservationId}`).submit();
                }
            });
        });
    });
});

