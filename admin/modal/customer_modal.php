<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="addCustomerModalLabel">
                    <i class="material-symbols-rounded me-2">add_circle</i>Add New Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="model/customer.php" method="POST">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Full Name</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="name" placeholder="Enter full name" required>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Address</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="address" placeholder="Enter address" required>
                            </div>
                        </div>

                        <!-- Contract ID -->
                        <div class="col-md-6 mb-4">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Contract ID</label>
                                <input type="text" id="contract_id" name="contract_id" class="form-control">
                            </div>
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-6 mb-4">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Contact Number</label>
                                <input type="text" id="contact_number" name="contact_number" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">save</i>Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>  

<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="editCustomerModalLabel">
                    <i class="material-symbols-rounded me-2">edit</i>Edit Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="model/customer_update.php" method="POST">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Full Name</label>
                            <div class="input-group input-group-outline">
                                <input type="text" id="edit_fullname" class="form-control" name="name" placeholder="Enter full name" required>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Address</label>
                            <div class="input-group input-group-outline">
                                <input type="text" id="edit_address" name="address" class="form-control" placeholder="Enter address" required>
                            </div>
                        </div>

                        <!-- Contract ID -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Contract ID</label>
                            <div class="input-group input-group-outline">
                                <input type="text" id="edit_contract_id" name="contract_id" class="form-control" placeholder="Enter contract ID">
                            </div>
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Contact Number</label>
                            <div class="input-group input-group-outline">
                            <input type="text" id="edit_contact_number" name="contact_number" class="form-control" placeholder="Enter contact number" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">save</i>Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>  
    </div>
</div>  
    </div>
</div>  
</div>  
</div>  
    </div>
</div>  
</div>  