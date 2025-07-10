<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="addAdminModalLabel">
                    <i class="material-symbols-rounded me-2">add_circle</i>Add New Admin
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAdminForm" action="/admin/model/admin_add.php" method="post">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Select Employee</label>
                            <div class="input-group">
                                <select class="form-select p-2" name="employee_id" required>
                                    <option value="" disabled selected>Select Employee</option>
                                    <?php
                                    require_once $_SERVER['DOCUMENT_ROOT'].'/admin/model/conn.php';
                                    $sql = "SELECT id, first_name, middle_initial, last_name FROM employee";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='{$row['id']}'>{$row['first_name']} {$row['middle_initial']} {$row['last_name']}</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled>No employees available</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Email</label>
                            <div class="input-group input-group-outline">
                                <input type="email" class="form-control" name="email" placeholder="Enter email" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Role</label>
                            <div class="input-group">
                                <select class="form-select p-2" name="role" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="front_office">Front Office</option>
                                    <option value="cashier">Cashier</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Username</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group input-group-outline">
                                <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <div class="input-group input-group-outline">
                                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">save</i>Save Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Account Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="editAdminModalLabel">
                    <i class="material-symbols-rounded me-2">edit</i>Edit Admin
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAdminForm" action="/admin/model/admin_edit.php" method="post">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <input type="hidden" id="edit_admin_id" name="admin_id">
                        <input type="hidden" id="edit_employee_id" name="employee_id">
                        
                        <!-- First Row -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Email</label>
                            <div class="input-group input-group-outline">
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Username</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="username" required>
                            </div>
                        </div>
                        
                        <!-- Second Row -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Account Type</label>
                            <div class="input-group">
                                <select class="form-select p-2" name="account_type" id="edit_account_type" required>
                                    <option value="" disabled>Select Account Type</option>
                                    <option value="admin">admin</option>
                                    <option value="manager">manager</option>
                                    <option value="front_office">front_office</option>
                                    <option value="cashier">cashier</option>
                                </select>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="col-12">
                            <div class="card mt-2">
                                <div class="card-header p-3">
                                    <h6 class="mb-0">Change Password</h6>
                                    <small class="text-muted">Leave blank to keep current password</small>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">New Password</label>
                                            <div class="input-group input-group-outline">
                                                <input type="password" class="form-control" name="password">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Confirm Password</label>
                                            <div class="input-group input-group-outline">
                                                <input type="password" class="form-control" name="confirm_password">
                                            </div>
                                        </div>
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
                        <i class="material-symbols-rounded me-2">save</i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Admin Modal -->
<div class="modal fade" id="deleteAdminModal" tabindex="-1" aria-labelledby="deleteAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAdminModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteAdminForm" action="/admin/model/admin_delete.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="admin_id" id="delete_admin_id">
                    <p>Are you sure you want to delete this admin account?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function editEmployee(id, username, email, employee_id, account_type) {
    try {
        // Set hidden fields
        document.getElementById('edit_admin_id').value = id;
        document.getElementById('edit_employee_id').value = employee_id;
        
        // Set form values
        const form = document.getElementById('editAdminForm');
        form.querySelector('[name="email"]').value = email;
        form.querySelector('[name="username"]').value = username;
        form.querySelector('[name="account_type"]').value = account_type;
        form.querySelector('[name="password"]').value = '';
        form.querySelector('[name="confirm_password"]').value = '';
        
        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('editAdminModal'));
        editModal.show();
        
    } catch (error) {
        console.error('Error in editEmployee:', error);
    }
}
</script>

    }
}
</script>

    }
}
</script>
