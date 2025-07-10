<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="addPaymentModalLabel">
                    <i class="material-symbols-rounded me-2">add_circle</i>Add New Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPaymentForm">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Reference Number</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" id="add_reference_no" name="reference_no" readonly>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">SOA ID</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="add_soa_display"  placeholder="Select an SOA..." style="flex: 1;">
                                <button type="button" class="btn btn-dark" onclick="openSOASelector('add')" style="width: 120px;">
                                    Select SOA
                                </button>
                                <input type="hidden" id="add_soa_id" name="soa_id" required>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Customer Name</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" id="add_customer_name" name="customer_name" readonly>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Payment Type</label>
                            <div class="input-group">
                                <select class="form-select p-2" id="add_payment_type" name="payment_type" required>
                                    <option value="">Select Payment Type</option>
                                    <option value="cash">Cash</option>
                                    <option value="e-wallet">E-Wallet</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="check">Check</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Amount</label>
                            <div class="input-group input-group-outline">
                                <input type="number" step="0.01" class="form-control" id="add_amount" name="amount" required>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Remarks</label>
                            <div class="input-group input-group-outline">
                                <textarea class="form-control" id="add_remarks" name="remarks" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">save</i>Save Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SOA Selection Modal -->


<script>

    async function getReferenceNumber() {
        const response = await fetch('/admin/ref_num_gen.php?type=payment');
        const data = await response.json();
        document.getElementById('add_reference_no').value = data.reference;
    }
$('#addPaymentForm').on('submit', function(e) {
    e.preventDefault();
    
    // Disable submit button and show loading state
    const submitButton = $(this).find('button[type="submit"]');
    const originalButtonText = submitButton.html();
    submitButton.prop('disabled', true);
    submitButton.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    
    // Create FormData object
    const formData = new FormData(this);
    
    // Send API request
    $.ajax({
        url: 'process/payment_create.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Create hidden iframe for printing
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = 'receipt_template.php?payment_id=' + response.payment_id + '&autoprint=true';
                document.body.appendChild(iframe);
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Payment recorded and receipt printed successfully',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Clear form and close modal
                    $('#addPaymentForm')[0].reset();
                    $('#addPaymentModal').modal('hide');
                    fetchPaymentData();
                    
                    // Remove iframe after printing
                    setTimeout(() => {
                        document.body.removeChild(iframe);
                    }, 2000);
                    window.location.reload();

                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            // Show error message for AJAX failures
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while processing your request. Please try again.',
                confirmButtonColor: '#3085d6'
            });
            console.error('Ajax Error:', error);
        },
        complete: function() {
            // Reset button state
            submitButton.prop('disabled', false);
            submitButton.html(originalButtonText);
        }
    });
});

// Add modal event handlers
$('#addPaymentModal').on('show.bs.modal', function () {
    // Clear all form fields when opening modal
    clearForm();
    // Fetch new reference number
    fetchReferenceNumber();
});

$('#addPaymentModal').on('hidden.bs.modal', function () {
    // Clear all form fields when closing modal
    clearForm();
});

// Function to clear all form fields
function clearForm() {
    $('#addPaymentForm')[0].reset();
    $('#add_soa_id').val('');
    $('#add_soa_display').val('');
    $('#add_customer_name').val('');
    $('#add_reference_no').val('');
    
    // Remove any error messages or validation states
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}

// Function to fetch reference number
function fetchReferenceNumber() {
    $.ajax({
        url: '/admin/ref_num_gen.php?type=payment',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response && response.success) {
                $('#add_reference_no').val(response.reference);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to generate reference number'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching reference number:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to generate reference number'
            });
        }
    });
}

// Function to handle SOA selection
function handleSOASelection(soaId, soaNumber, customerName) {
    $('#add_soa_id').val(soaId);
    $('#add_soa_display').val(soaNumber);
    $('#add_customer_name').val(customerName);
}

// Function to open SOA selector
function openSOASelector(mode) {
    // Store current mode (add/edit)
    window.currentSOAMode = mode;
    // Open SOA selector in new window
    window.open('soa-selector.php', 'SOA Selector', 'width=800,height=600');
}

// Function to receive SOA selection from popup
window.receiveSOASelection = function(soaId, soaNumber, customerName) {
    if (window.currentSOAMode === 'add') {
        handleSOASelection(soaId, soaNumber, customerName);
    }
}
</script> 