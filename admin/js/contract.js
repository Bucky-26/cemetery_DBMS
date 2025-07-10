function editContract(id) {
    $.ajax({
        url: 'model/contract_fetch.php',
        type: 'POST',
        data: {id: id},
        success: function(response) {
            var data = JSON.parse(response);
            $('#edit_id').val(data.id);
            $('#edit_customer_id').val(data.customer_id);
            $('#edit_con_date').val(data.con_date.replace(' ', 'T'));
            $('#edit_con_end').val(data.con_end.replace(' ', 'T'));
            $('#edit_amount').val(data.amount);
            $('#edit_installment').val(data.installment);
            $('#edit_balance').val(data.balance);
            $('#edit_paid_amount').val(data.paid_amount);
            $('#editContractModal').modal('show');
        }
    });
}

$(document).on('submit', '#editContractForm', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'model/contract_update.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if(response == 'success') {
                $('#editContractModal').modal('hide');
                // Refresh the contracts table
                loadContracts();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Contract updated successfully!'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update contract!'
                });
            }
        }
    });
});

// Add this function to refresh the contracts table
function loadContracts() {
    $.ajax({
        url: 'model/contract_get.php',
        type: 'GET',
        success: function(response) {
            $('#contractTableBody').html(response);
        }
    });
} 