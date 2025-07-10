<?php include 'model/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidenav.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container-fluid py-4 min-vh-85">
<?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
            <span class="alert-icon align-middle">
              <span class="material-icons text-md">check_circle</span>
            </span>
            <span class="alert-text"><?php echo $_SESSION['success']; ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php 
        unset($_SESSION['success']); // Clear the message after displaying
        endif; 
        ?>
    <div class="row">
        <div class="col-12">
            <h5 class="mb-4">Employee Management</h5>
            <div class="card shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white p-3 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0 align-middle align-content-center">Customer List</h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end gap-2 px-4 pt-3">
                            <div class="input-group input-group-outline my-3 w-35">
                                <label class="form-label text-gray-900">Search Customer</label>
                                <input type="text" class="form-control text-gray-900" id="searchInput">
                            </div>
                            <button class="btn btn-icon btn-3 bg-gray-900 h-25 my-3" type="button" data-bs-toggle="modal" data-bs-target="#new_employee_modal">
                                <span class="btn-inner--icon"><i class="material-symbols-rounded text-white">person_add</i></span>
                                <span class="btn-inner--text text-white">Add Employee</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr> 
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employee</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Title</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employment Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include 'model/employee_data.php'; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="assets/js/employee.js"></script>
<?php include 'modal/new_employee_modal.php'; ?>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>


