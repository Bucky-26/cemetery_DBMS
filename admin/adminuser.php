<?php include './model/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidenav.php'; ?>
<?php include 'includes/navbar.php'; ?>
<div class="container-fluid py-4 min-vh-85">
    <?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
        <span class="alert-icon align-middle">
          <span class="material-icons text-md"></span>
        </span>
        <span class="alert-text">
            <?php 
                switch($_GET['success']) {
                    case '1':
                        echo 'Account added successfully!';
                        break;
                    case '2':
                        echo 'Account updated successfully!';
                        break;
                    default:
                        echo 'Operation completed successfully!';
                }
            ?>
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Add this script right after the alert -->
    <script>
        // Remove success parameter from URL after page load
        window.addEventListener('load', function() {
            if (window.history.replaceState) {
                // Remove URL parameters without reloading the page
                const url = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, url);
            }
        });

        // Auto-hide alert after 3 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) closeButton.click();
            }
        }, 3000);
    </script>
    <?php endif; ?>
    
    <?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
        <span class="alert-icon align-middle">
            <span class="material-icons text-md">error</span>
        </span>
        <span class="alert-text">
            <?php 
                switch($_GET['error']) {
                    case '1':
                        echo 'Username already exists!';
                        break;
                    case '2':
                        echo 'Passwords do not match!';
                        break;
                    case '3':
                        echo 'Error updating account. Please try again.';
                        break;
                    case '4':
                        echo 'Cannot delete your own account!';
                        break;
                    case '5':
                        echo 'Error deleting account. Please try again.';
                        break;
                    case '6':
                        echo 'Cannot delete the last admin account!';
                        break;
                    default:
                        echo 'An unknown error occurred.';
                }
            ?>
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
        <h5 class="mb-0">User Management</h5>
        </div>
      <div class="card">
      <div class="card-header bg-white p-3 border-bottom">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-0 align-middle align-content-center">Admin Users</h6>
            </div>
            <div class="col-md-6 d-flex justify-content-end gap-2 px-4 pt-3">

            <div class="input-group input-group-outline my-3 w-35">
                                <label class="form-label text-gray-900">Search Account</label>
                                <input type="text" class="form-control text-gray-900" id="searchInput" onfocus="this.parentElement.classList.add('focused')" onblur="if(!this.value) this.parentElement.classList.remove('focused')">
            </div>
            <button class="btn btn-icon btn-3 bg-gray-900 h-25 my-3" type="button" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                                <span class="btn-inner--icon"><i class="material-symbols-rounded text-white">person_add</i></span>
                    <span class="btn-inner--text text-white">Add Admin</span>
                </button>
            </div>
        </div>



    </div>
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ID</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Username</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date-Added</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                </thead>

                <tbody>
                    <?php include "controller/admin_user.php"; ?>
                </tbody>
            </table>
        </div>
      </div>
                
    </div>
</div>

<!-- Add Admin Modal -->
<?php include 'modal/admin_modal.php'; ?>

<script src="assets/js/adminuser.js"></script>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>
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

// Edit button handler
function editEmployee(id, username, email, employee_id, account_type) {
    // Debug
    console.log('Function called with:', { id, username, email, employee_id, account_type });

    try {
        // Get the form
        const form = document.getElementById('editAdminForm');
        if (!form) {
            console.error('Form not found');
            return;
        }

        // Set basic values first
        document.getElementById('edit_admin_id').value = id || '';
        document.getElementById('edit_employee_id').value = employee_id || '';

        // Find the account type select element
        const accountSelect = document.getElementById('edit_account_type');
        if (accountSelect) {
            accountSelect.value = account_type || 'admin';
        } else {
            console.error('Account type select not found!');
        }

        // Set other fields
        form.querySelector('[name="email"]').value = email;
        form.querySelector('[name="username"]').value = username;
        form.querySelector('[name="password"]').value = '';

        // Debug: Log all form values before showing modal
        console.log('Form values:', {
            admin_id: form.querySelector('[name="admin_id"]').value,
            employee_id: form.querySelector('[name="employee_id"]').value,
            email: form.querySelector('[name="email"]').value,
            username: form.querySelector('[name="username"]').value,
            account_type: form.querySelector('[name="account_type"]').value
        });

        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('editAdminModal'));
        editModal.show();
    } catch (error) {
        console.error('Error in editEmployee function:', error);
    }
}

// Delete button handler
function deleteEmployee(id) {
    document.getElementById('delete_admin_id').value = id;
    new bootstrap.Modal(document.getElementById('deleteAdminModal')).show();
}
</script>

