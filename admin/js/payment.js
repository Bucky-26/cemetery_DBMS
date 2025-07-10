// Function to generate reference number
function generateReferenceNumber() {
    const date = new Date();
    const year = date.getFullYear().toString().substr(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const random = Math.floor(Math.random() * 9999).toString().padStart(4, '0');
    return `PAY-${year}${month}${day}-${random}`;
}

// Initialize modals and form handling
$(document).ready(function() {
    // Auto-generate reference number when add payment modal opens
    $('#addPaymentModal').on('show.bs.modal', function () {
        $('#add_reference_no').val(generateReferenceNumber());
    });

    // Handle form submission
    $('#addPaymentForm').on('submit', function(e) {
        e.preventDefault();
        
        // Form validation
        if (!$('#add_soa_id').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing SOA',
                text: 'Please select an SOA first'
            });
            return;
        }

        // Submit form via AJAX
        $.ajax({
            url: 'process/payment_process.php',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Payment added successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            $('#addPaymentModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: result.message || 'Failed to add payment'
                        });
                    }
                } catch (error) {
                    console.error('Error parsing response:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to connect to server'
                });
            }
        });
    });
}); 