<!-- Add Contract Modal -->
<div class="modal fade" id="addManagementModal" tabindex="-1" aria-labelledby="addManagementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="addManagementModalLabel">
                    <i class="material-symbols-rounded me-2">add_circle</i>Add New Contract
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="model/contract_add.php" method="POST">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Reference Number</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       name="ref_number" 
                                       id="ref_number" 
                                       readonly 
                                       required>
                                <button type="button" 
                                        class="btn btn-dark" 
                                        onclick="generateReference()">
                                    Generate
                                </button>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Customer Name</label>
                            <div class="input-group">
                                <select class="form-select p-2" name="customer_id" id="customerSelect" required>
                                    <option value="" disabled selected>Select Customer Name</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Contract Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="contract_date" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Contract End</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="contract_end" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Total Amount</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" name="amount" placeholder="Enter total amount" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Down Payment</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" name="downpayment" placeholder="Enter down payment" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">No. of Installment</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" name="no_installment" placeholder="Enter number of installments" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" name="add_contract" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">save</i>Save Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editContractModal" tabindex="-1" aria-labelledby="editContractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="editContractModalLabel">
                    <i class="material-symbols-rounded me-2">edit</i>Edit Contract
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editContractForm">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <input type="hidden" id="edit_id" name="id">
                        
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Reference Number</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       name="ref_number" 
                                       id="edit_ref_number" 
                                       readonly 
                                       required>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Customer Name</label>
                            <div class="input-group">
                                <select class="form-select p-2" name="customer_id" id="edit_customer_id" readonly 
                                        style="pointer-events: none; background-color: #e9ecef;" required>
                                    <option value="" disabled selected>Select Customer Name</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Contract Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" id="edit_con_date" name="con_date" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Contract End</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" id="edit_con_end" name="con_end" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Total Amount</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" id="edit_amount" name="amount" 
                                       placeholder="Enter total amount" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Down Payment</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" id="edit_downpayment" name="downpayment" 
                                       placeholder="Enter down payment" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">No. of Installment</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" id="edit_no_installment" name="no_installment" 
                                       placeholder="Enter number of installments" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">save</i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteContractModal" tabindex="-1" aria-labelledby="deleteContractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-medium" id="deleteContractModalLabel">Delete Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete this contract? This action cannot be undone.</p>
                <input type="hidden" id="delete_contract_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Contract</button>
            </div>
        </div>
    </div>
</div>
<script>
// Function to fetch and populate customers
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
$('#addManagementModal').on('show.bs.modal', function () {
    loadCustomers();
    generateReference();
});

// Load customers when edit modal is shown
$('#editContractModal').on('show.bs.modal', function () {
    loadCustomers();
});

// Function to populate edit modal with contract data
function populateEditModal(contractData) {
    document.getElementById('edit_contract_id').value = contractData.contract_id;
    document.getElementById('edit_ref_number').value = contractData.ref_number;
    document.getElementById('edit_customer_id').value = contractData.customer_id;
    document.getElementById('edit_con_date').value = contractData.con_date;
    document.getElementById('edit_con_end').value = contractData.con_end;
    document.getElementById('edit_amount').value = contractData.amount;
    document.getElementById('edit_installment').value = contractData.installment;
    
    // Assuming you have a select element for customer
    const customerSelect = document.getElementById('edit_customer_select');
    if (customerSelect) {
        customerSelect.value = contractData.customer_id;
    }
}

// Update the edit click handler
$(document).on('click', 'button[data-action="edit"]', function() {
    const contractId = $(this).data('id');
    console.log('Edit clicked for contract ID:', contractId);
    
    // Fetch contract data
    $.ajax({
        url: 'model/get_contract.php',
        type: 'GET',
        data: { id: contractId },
        success: function(response) {
            console.log('Raw response:', response);
            try {
                const data = typeof response === 'string' ? JSON.parse(response) : response;
                console.log('Parsed data:', data);
                
                if (data.success) {
                    // Populate form fields
                    $('#edit_id').val(data.contract.contract_id);
                    $('#edit_ref_number').val(data.contract.ref_number || '');
                    $('#edit_customer_id').val(data.contract.customer_id);
                    $('#edit_customer_id_hidden').val(data.contract.customer_id);
                    $('#edit_con_date').val(data.contract.con_date?.split(' ')[0] || ''); // Get only the date part
                    $('#edit_con_end').val(data.contract.con_end?.split(' ')[0] || '');
                    $('#edit_amount').val(data.contract.amount || 0);
                    $('#edit_no_installment').val(data.contract.installment || 0);
                    
                    // Load customers and set selected
                    loadCustomers(data.contract.customer_id);
                    
                    // Show modal
                    $('#editContractModal').modal('show');
                } else {
                    console.error('Error:', data.message);
                    alert('Error loading contract data: ' + data.message);
                }
            } catch(e) {
                console.error('Error parsing response:', e);
                alert('Error loading contract data');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.error('Response:', xhr.responseText);
            alert('Error loading contract data');
        }
    });
});

// Update form submission
$('#editContractForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'model/contract_edit.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            $('#editContractModal').modal('hide');
            // Reload the contracts table or update the specific row
            location.reload();
        },
        error: function() {
            alert('Error updating contract');
        }
    });
});
</script>

