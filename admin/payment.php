<?php include 'model/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidenav.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container-fluid py-4 min-vh-85">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-4">Payment Management</h5>
            <div class="card shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white p-3 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0">Payment List</h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end gap-2 px-4 pt-3">
                            <div class="input-group input-group-outline my-3 w-35">
                                <label class="form-label">Search Payment</label>
                                <input type="text" class="form-control" id="searchInput" onfocus="this.parentElement.classList.add('focused')" onblur="if(!this.value) this.parentElement.classList.remove('focused')">
                            </div>
                            <button class="btn btn-icon btn-3 bg-gray-900 h-25 my-3" type="button" onclick="getReferenceNumber()" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                                <span class="btn-inner--icon"><i class="material-symbols-rounded text-white">add</i></span>
                                <span class="btn-inner--text text-white">Create Payment</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-xs font-weight-bold">PAYMENT ID</th>
                                    <th class="text-center text-xs font-weight-bold">CUSTOMER NAME</th>
                                    <th class="text-center text-xs font-weight-bold">PAYMENT DATE</th>
                                    <th class="text-center text-xs font-weight-bold">PAYMENT METHOD</th>
                                    <th class="text-center text-xs font-weight-bold">AMOUNT</th>
                                    <th class="text-center pe-4 text-xs font-weight-bold">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="paymentTableBody">
                                <!-- Updated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let input = this.value.toLowerCase();
    let tbody = document.querySelector('table tbody');
    let rows = tbody.getElementsByTagName('tr');

    for (let row of rows) {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    }
});

// Add new function to fetch and display payment data
function fetchPaymentData() {
    fetch('/admin/model/payment_data.php')  
        .then(response => response.json())
        .then(response => {
            const tableBody = document.getElementById('paymentTableBody');
            tableBody.innerHTML = '';
            
            if (response.status === 'success') {
                if (response.data.length > 0) {
                    response.data.forEach(payment => {
                        tableBody.innerHTML += `
                            <tr>
                                <td class="text-center">${payment.id}</td>
                                <td class="text-center">${payment.fullname}</td>
                                <td class="text-center">${new Date(payment.payment_date).toLocaleDateString()}</td>
                                <td class="text-center">${payment.payment_method || 'N/A'}</td>
                                <td class="text-center">₱${parseFloat(payment.amount).toLocaleString()}</td>
                                <td class="text-center">
                                    <button class="btn btn-link text-secondary mb-0" 
                                            onclick="editPayment(${payment.id})">
                                        <i class="material-icons text-sm">edit</i>
                                    </button>
                                    <button class="btn btn-link text-secondary mb-0" 
                                            onclick="deletePayment(${payment.id})">
                                        <i class="material-icons text-sm">delete</i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center">No payment records found</td>
                        </tr>
                    `;
                }
            } else {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">
                            An error occurred while fetching payment data.
                        </td>
                    </tr>
                `;
            }
        });
}

// Add this line to load the data when the page loads
document.addEventListener('DOMContentLoaded', fetchPaymentData);
</script>

<?php include 'modal/payment_modal.php'; ?>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>
