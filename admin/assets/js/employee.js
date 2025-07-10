document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');

    const form = document.querySelector('#edit_employeeForm');
    console.log('Form found:', form); // Check if form is found

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted'); // Debug log

            const formData = new FormData(this);

            // Debug: Log all form data
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Make sure this path matches your project structure
            $.ajax({
                url: 'process/employee_update.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Response:', response);
                    if (response.status === 'success') {
                        // Close the modal
                        $('#edit_employee_modal').modal('hide');

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Employee updated successfully'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to update employee'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating the employee.'
                    });
                }
            });
        });
    } else {
        console.error('Form not found');
    }
});

function editEmployee(id) {
    const formData = new FormData();
    formData.append('employee_id', id);

    fetch('process/get_info.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data); // Debug log

            // Handle photo first
            const photoPreview = document.querySelector('#edit_photoPreview');
            if (photoPreview) {
                if (data.photo_url) {
                    // Remove any leading slashes and ensure correct path
                    const photoPath = data.photo_url;
                    photoPreview.src = photoPath;
                    console.log('Setting photo preview to:', photoPath); // Debug log
                } else {
                    photoPreview.src = 'images/employee/defualt.png';
                }
            }

            // Set hidden input for old photo
            const oldPhotoInput = document.querySelector('[name="old_photo_url"]');
            if (oldPhotoInput && data.photo_url) {
                oldPhotoInput.value = data.photo_url;
            }

            // Set other form fields...
            const fieldMappings = {
                'employee_id': 'edit_employee_id',
                'first_name': 'edit_first_name',
                'middle_initial': 'edit_middle_initial',
                'last_name': 'edit_last_name',
                'email': 'edit_email',
                'phone_number': 'edit_phone_number',
                'job_title': 'edit_job_title',
                'employment_status': 'edit_employment_status',
                'hire_date': 'edit_hire_date',
                'salary': 'edit_salary',
                'birth_date': 'edit_birth_date',
                'gender': 'edit_gender',
                'address': 'edit_address'
            };

            // Loop through the mappings and set values
            Object.entries(fieldMappings).forEach(([dataKey, inputName]) => {
                const input = document.querySelector(`[name="${inputName}"]`);
                if (input && data[dataKey]) {
                    input.value = data[dataKey];
                    // Add is-filled class to parent input-group if it exists
                    const inputGroup = input.closest('.input-group');
                    if (inputGroup) {
                        inputGroup.classList.add('is-filled');
                    }
                }
            });

            // Show the modal
            const editModal = new bootstrap.Modal(document.getElementById('edit_employee_modal'));
            editModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Failed to load employee data', 'error');
        });
}

// Image preview function
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const previewId = input.id === 'edit_photo' ? 'edit_photoPreview' : 'photoPreview';

        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
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

function deleteEmployee(id) {
    // Show confirmation dialog
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
            formData.append('employee_id', id);

            // Send delete request
            $.ajax({
                url: 'process/employee_delete.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Response:', response);
                    if (response.status === 'success') {
                        Swal.fire(
                            'Deleted!',
                            'Employee has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete employee',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the employee.',
                        'error'
                    );
                }
            });
        }
    });
}