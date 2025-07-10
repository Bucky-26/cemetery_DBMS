$(document).ready(function() {
    $('#customer_select').change(function() {
        const customerId = $(this).val();
        if (customerId) {
            $.ajax({
                url: 'model/get_contracts.php',
                type: 'POST',
                data: { customer_id: customerId },
                success: function(response) {
                    $('#contract_select').html(response);
                }
            });
        } else {
            $('#contract_select').html('<option value="">Select Contract</option>');
            $('#contractDetails').addClass('d-none');
        }
    });

    $('#contract_select').change(function() {
        const contractId = $(this).val();
        if (contractId) {
            $.ajax({
                url: 'model/get_contract_details.php',
                type: 'POST',
                data: { contract_id: contractId },
                success: function(response) {
                    const data = JSON.parse(response);
                    $('#totalAmount').text(parseFloat(data.amount).toLocaleString());
                    $('#downPayment').text(parseFloat(data.downpayment).toLocaleString());
                    $('#monthlyPayment').text(parseFloat(data.installment).toLocaleString());
                    $('#installmentsPaid').text(data.installment_paid);
                    $('#remainingBalance').text(parseFloat(data.balance).toLocaleString());
                    $('#totalInstallments').text(data.monthly_payment);
                    $('#contractDetails').removeClass('d-none');
                }
            });
        } else {
            $('#contractDetails').addClass('d-none');
        }
    });
});