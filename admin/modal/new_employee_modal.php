<div class="modal fade" id="new_employee_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white">
                    <i class="material-symbols-rounded me-2">add_circle</i>Add New Employee
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="employeeForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <!-- Image Upload -->
                        <div class="col-12 mb-4 text-center">
                            <div class="position-relative d-inline-block">
                                <img id="photoPreview" src="/admin/images/employee/defualt.png" 
                                     class="avatar avatar-xxl rounded-circle" alt="profile">
                                <label for="photo" class="position-absolute bottom-0 end-0">
                                    <div class="btn btn-sm bg-gradient-dark rounded-circle p-2">
                                        <i class="material-symbols-rounded">photo_camera</i>
                                    </div>
                                </label>
                                <input type="file" id="photo" name="photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">First Name</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Middle Initial</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="middle_initial" maxlength="1">
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Last Name</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Email</label>
                            <div class="input-group input-group-outline">
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Phone Number</label>
                            <div class="input-group input-group-outline">
                                <input type="tel" class="form-control" name="phone_number">
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Job Title</label>
                            <select class="form-select p-2" name="job_title" required>
                                <option value="" disabled selected>Select Job Title</option>
                                <option value="manager">Manager</option>
                                <option value="front_office">Front Office</option>
                                <option value="cashier">Cashier</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Employment Status</label>
                            <select class="form-select p-2" name="employment_status">
                                <option value="Active">Active</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Resigned">Resigned</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Hire Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="hire_date" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Salary</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" name="salary" step="0.01" required>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Birth Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="birth_date">
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Gender</label>
                            <select class="form-select p-2" name="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Address</label>
                            <div class="input-group input-group-outline">
                                <textarea class="form-control" name="address" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">save</i>Save Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- edit modal -->
<div class="modal fade" id="edit_employee_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white">
                    <i class="material-symbols-rounded me-2">edit</i>Edit Employee
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_employeeForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <!-- Image Upload -->
                        <div class="col-12 mb-4 text-center">
                            <div class="position-relative d-inline-block">
                                <img id="edit_photoPreview" src="/admin/images/employee/default.png" 
                                     class="avatar avatar-xxl rounded-circle" alt="profile">
                                <label for="edit_photo" class="position-absolute bottom-0 end-0">
                                    <div class="btn btn-sm bg-gradient-dark rounded-circle p-2">
                                        <i class="material-symbols-rounded">photo_camera</i>
                                    </div>
                                </label>
                                <input type="file" id="edit_photo" name="edit_photo" class="d-none" 
                                       accept="image/*" onchange="editPreviewImage(this)">
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">First Name</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="edit_first_name" required>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Middle Initial</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="edit_middle_initial" maxlength="1">
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Last Name</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control" name="edit_last_name" required>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Email</label>
                            <div class="input-group input-group-outline">
                                <input type="email" class="form-control" name="edit_email" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Phone Number</label>
                            <div class="input-group input-group-outline">
                                <input type="tel" class="form-control" name="edit_phone_number">
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Job Title</label>
                            <select class="form-select p-2" name="edit_job_title" required>
                                <option value="" disabled selected>Select Job Title</option>
                                <option value="manager">Manager</option>
                                <option value="front_office">Front Office</option>
                                <option value="cashier">Cashier</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Employment Status</label>
                            <select class="form-select p-2" name="edit_employment_status">
                                <option value="Active">Active</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Resigned">Resigned</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Hire Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="edit_hire_date" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Salary</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" name="edit_salary" step="0.01" required>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Birth Date</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" name="edit_birth_date">
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Gender</label>
                            <select class="form-select p-2" name="edit_gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Address</label>
                            <div class="input-group input-group-outline">
                                <textarea class="form-control" name="edit_address" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="edit_employee_id" name="edit_employee_id">
                <input type="hidden" id="old_photo_url" name="old_photo_url">
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="material-symbols-rounded me-2">close</i>Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="material-symbols-rounded me-2">save</i>Save Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
// Image preview function
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('#photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function editPreviewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('#edit_photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
// Form submission handler
document.getElementById('employeeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('model/employee_add.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('new_employee_modal'));
            modal.hide();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while saving the employee.'
        });
    });
});

// Fix for modal backdrop issue
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('new_employee_modal');
    
    modal.addEventListener('show.bs.modal', function () {
        document.body.classList.add('modal-open');
    });
    
    modal.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    });
});
</script>