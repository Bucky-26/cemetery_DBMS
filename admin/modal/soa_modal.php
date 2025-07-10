<div class="modal fade" id="generateSOA" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="generateSOALabel">
                    <i class="material-symbols-rounded me-2">description</i>Generate Statement of Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="soaForm" action="model/gensoa.php" method="POST">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Select Customer</label>
                            <div class="input-group">
                                <select class="form-select p-2" name="customer_id" id="customer_select" required>
                                    <option value="" disabled selected>Select Customer</option>
                                    <?php
                                    $query = "SELECT DISTINCT c.id, c.fullname 
                                             FROM customer c 
                                             INNER JOIN contract ct ON c.id = ct.customer_id 
                                             ORDER BY c.fullname";
                                    $result = $conn->query($query);
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='".$row['id']."'>".$row['fullname']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold    ">Contract Details</label>
                            <div class="input-group">
                                <select class="form-select p-2" name="contract_id" id="contract_select" required>
                                    <option value="" disabled selected>Select Contract</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="contractDetails" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-3"><strong class="text-dark">Total Contract Amount:</strong> <span id="totalAmount"></span></p>
                                        <p class="mb-3"><strong class="text-dark">Down Payment:</strong> <span id="downPayment"></span></p>
                                        <p class="mb-3"><strong class="text-dark">Monthly Payment:</strong> <span id="monthlyPayment"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-3"><strong class="text-dark">Installments Paid:</strong> <span id="installmentsPaid"></span></p>
                                        <p class="mb-3"><strong class="text-dark">Remaining Balance:</strong> <span id="remainingBalance"></span></p>
                                        <p class="mb-3"><strong class="text-dark">Total Installments:</strong> <span id="totalInstallments"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">description</i>Generate SOA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    margin-top: 20px;
}

.card-body {
    padding: 1.25rem;
}

.text-dark {
    color: #344767;
}

.mb-3 {
    margin-bottom: 1rem;
}
</style>

<script>
document.getElementById('soaForm').addEventListener('submit', function() {
    setTimeout(function() {
        // Reset the form
        document.getElementById('soaForm').reset();
        
        // Hide contract details
        document.getElementById('contractDetails').classList.add('d-none');
        
        // Close the modal and remove backdrop
        var modal = bootstrap.Modal.getInstance(document.getElementById('generateSOA'));
        modal.hide();
        
        // Remove modal backdrop and modal-open class
        document.body.classList.remove('modal-open');
        document.querySelector('.modal-backdrop').remove();
        
    }, 1000);
});
</script>

