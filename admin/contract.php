<?php include 'includes/header.php'; ?>
<?php include 'includes/sidenav.php'; ?>
<?php include 'includes/navbar.php'; ?>
    <div class="row">
        <div class="col-12">
            <h5 class="mb-4">Customer Management</h5>
            <div class="card shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white p-3 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0">Contract List</h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end gap-2 px-4 pt-3">
                            <div class="input-group input-group-outline my-3 w-35">
                                <label class="form-label">Search Customer</label>
                                <input type="text" class="form-control" id="searchInput" onfocus="this.parentElement.classList.add('focused')" onblur="if(!this.value) this.parentElement.classList.remove('focused')">
                            </div>
                            <button class="btn btn-icon btn-3 bg-gray-900 h-25 my-3" type="button" data-bs-toggle="modal" onclick="loadCustomers()" data-bs-target="#addManagementModal">
                                <span class="btn-inner--icon"><i class="material-symbols-rounded text-white">add</i></span>
                                <span class="btn-inner--text text-white">Add Record</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-xs font-weight-bold">Customer Name</th>
                                    <th class="text-center text-xs font-weight-bold">Contract Date</th>
                                    <th class="text-center text-xs font-weight-bold">Contract End</th>
                                    <th class="text-center text-xs font-weight-bold">Amount</th>
                                    <th class="text-center text-xs font-weight-bold">Installment</th>
                                    <th class="text-center text-xs font-weight-bold">Balance</th>
                                    <th class="text-center pe-4 text-xs font-weight-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include 'model/contract_get.php'; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
    function editContract(contractId) {
        // Fetch contract data
        $.ajax({
            url: 'model/get_contract.php',
            type: 'GET',
            data: { id: contractId },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // Populate the modal fields
                        $('#edit_ref_number').val(data.contract.ref_number);
                        $('#customer_select').val(data.contract.customer_id);
                        $('#edit_con_date').val(data.contract.con_date);
                        $('#edit_con_end').val(data.contract.con_end);
                        $('#edit_amount').val(data.contract.amount);
                        $('#edit_downpayment').val(data.contract.downpayment);
                        $('#edit_installment').val(data.contract.installment);
                        
                        // Show the modal
                        $('#editContractModal').modal('show');
                    } else {
                        alert('Error loading contract data');
                    }
                } catch(e) {
                    console.error('Error parsing response:', e);
                    alert('Error loading contract data');
                }
            },
            error: function() {
                alert('Error loading contract data');
            }
        });
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
                    if(data.includes("successfully")) {
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
</script>

        <script src="assets/js/contract.js"></script>
<?php include 'modal/contract.php'; ?>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>

