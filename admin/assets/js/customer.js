function editCustomer(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('model/customer_row.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data);

            // Set the customer ID in the hidden input
            document.getElementById('edit_id').value = id;

            // Populate the edit modal with the data and ensure proper Material Design styling
            const inputGroups = document.querySelectorAll('#editCustomerModal .input-group');

            // Set values and add is-filled class
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_fullname').value = data.fullname;
            document.getElementById('edit_address').value = data.address;
            document.getElementById('edit_contract_id').value = data.contract_id;
            document.getElementById('edit_contact_number').value = data.contact;

            inputGroups.forEach(group => {
                group.classList.add('is-filled');
            });

            // Show the modal
            const editModal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
            editModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    let searchValue = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('table tbody tr');

    tableRows.forEach(row => {
        let name = row.cells[1].textContent.toLowerCase();
        let address = row.cells[2].textContent.toLowerCase();

        if (name.includes(searchValue) || address.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Get the edit modal element
    const editModal = document.getElementById('editCustomerModal');

    // Add hidden.bs.modal event listener
    editModal.addEventListener('hidden.bs.modal', function() {
        // Reset the form
        const form = editModal.querySelector('form');
        form.reset();

        // Clear all input values
        document.getElementById('edit_id').value = '';
        document.getElementById('edit_fullname').value = '';
        document.getElementById('edit_address').value = '';
        document.getElementById('edit_contract_id').value = '';
        document.getElementById('edit_contact_number').value = '';

        // Remove is-filled class from all input groups
        const inputGroups = editModal.querySelectorAll('.input-group');
        inputGroups.forEach(group => {
            group.classList.remove('is-filled');
        });

        // Remove modal backdrop and restore scrolling
        document.body.classList.remove('modal-open');
        document.body.classList.remove('bg-gray-100'); // Remove the gray background class
        const modalBackdrop = document.querySelector('.modal-backdrop.fade.show');
        if (modalBackdrop) {
            modalBackdrop.remove();
        }
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });
});

function closeModal() {
    const editModal = bootstrap.Modal.getInstance(document.getElementById('editCustomerModal'));
    if (editModal) {
        editModal.hide();
    }

    // Remove modal backdrop and restore scrolling
    document.body.classList.remove('modal-open');
    document.body.classList.remove('bg-gray-100'); // Remove the gray background class
    const modalBackdrop = document.querySelector('.modal-backdrop.fade.show');
    if (modalBackdrop) {
        modalBackdrop.remove();
    }
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

function deleteCustomer(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('delete_id', id);

            fetch('model/customer_delete.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        Swal.fire(
                            'Deleted!',
                            'Customer has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                });
        }
    });
}