function editContract(id, fullname, con_date, con_end, amount, downpayment, no_installment) {
    // Get the customer ID select element
    const edit_customer_id = document.getElementById('edit_customer_id');

    // Create and append a new option for the customer
    const option = document.createElement('option');
    option.value = id;
    option.textContent = fullname;
    option.selected = true;

    // Clear existing options and add the new one
    edit_customer_id.innerHTML = '';
    edit_customer_id.appendChild(option);

    // Format dates to match the input format (yyyy-mm-dd)
    const formattedConDate = formatDate(con_date);
    const formattedConEnd = formatDate(con_end);

    // Set values for all other fields
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_con_date').value = formattedConDate;
    document.getElementById('edit_con_end').value = formattedConEnd;
    document.getElementById('edit_amount').value = amount;
    document.getElementById('edit_downpayment').value = downpayment;
    document.getElementById('edit_no_installment').value = no_installment;
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0]; // Returns YYYY-MM-DD format
}

// Add event listeners to all delete buttons
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to all delete buttons
    const deleteButtons = document.querySelectorAll('.delete-contract');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const contractId = this.getAttribute('data-id');
            deleteContract(contractId);
        });
    });
});

function deleteContract(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! This will also delete related SOA records.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('model/contract_delete.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes("successfully")) {
                        Swal.fire(
                            'Deleted!',
                            'Contract has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Something went wrong: ' + data,
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


///

function loadCustomers(selectedCustomerId = null) {
    fetch('../admin/model/customerv1.php')
        .then(response => response.json())
        .then(customers => {
            const addSelect = document.getElementById('customerSelect');
            const editSelect = document.getElementById('edit_customer_id');

            // Clear existing options (except the first default option)
            while (addSelect.options.length > 1) addSelect.remove(1);
            while (editSelect.options.length > 1) editSelect.remove(1);




            // Add new options to both selects
            customers.forEach(customer => {
                // For add modal
                const addOption = document.createElement('option');
                addOption.value = customer.id;
                addOption.textContent = `${customer.fullname} - ${customer.contact_number}`;
                addSelect.appendChild(addOption);

                // For edit modal
                const editOption = document.createElement('option');
                editOption.value = customer.id;
                editOption.textContent = `${customer.fullname} - ${customer.contact_number}`;
                editSelect.appendChild(editOption);
            });

            // If we have a selected customer ID, set it in the edit modal
            if (selectedCustomerId) {
                editSelect.value = selectedCustomerId;
            }
        })
        .catch(error => console.error('Error loading customers:', error));
}

// Function to auto-generate reference number
async function generateReference() {
    try {
        const response = await fetch('ref_num_gen.php?type=contract');
        const data = await response.json();

        if (data.success) {
            document.getElementById('ref_number').value = data.reference;
        } else {
            console.error('Error generating reference:', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Generate reference when modal opens
$('#addManagementModal').on('show.bs.modal', function() {
    loadCustomers();
    generateReference();
});

// Load customers when edit modal is shown
$('#editContractModal').on('show.bs.modal', function() {
    loadCustomers();
});