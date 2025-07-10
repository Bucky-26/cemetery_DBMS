<?php include 'model/session.php'; ?>
<?php include 'model/conn.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidenav.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container-fluid py-4 min-vh-85">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-5">Burial Management</h5>

        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-0 align-middle align-content-center">Burial Records</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end gap-2 px-4 pt-3">
                    <div class="input-group input-group-outline my-3 w-45">
                           <label class="form-label text-gray-900"> Search Record</label>
                           <input type="text" class="form-control text-gray-900" id="searchInput">
                       </div>
                       <button class="btn btn-icon btn-3 bg-gray-900 h-25 my-3" type="button" data-bs-toggle="modal" data-bs-target="#addDeceaseModal"  >
                       <span class="btn-inner--icon"><i class="material-symbols-rounded text-white">person_add</i></span>
                       <span class="btn-inner--text text-white">Add Burial Record</span>
                    </button>
                    </div>
                </div>
            </div>
                <div class="card-body">
                     <!-- Table -->
                       <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="deceaseTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">PLOT TITLE</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NAME</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">BURIAL DATE</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">REMARKS</th>
                                    <th class="text-secondary text-center opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include 'model/get_deceased.php'; ?>
                            </tbody>
                          
                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Add JavaScript for Search Functionality -->
<script src="assets/js/deceased.js"></script>
<script src="assets/plot-selector.js"></script>
<script src="assets/plot-selector_edit.js"></script>
<?php include 'modal/decease_modal.php'; ?>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>
<script src="assets/js/deceased.js"></script>
