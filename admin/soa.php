<?php include 'model/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidenav.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container-fluid py-4 min-vh-85">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-4">Statement of Account</h5>
            <div class="card shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white p-3 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                        <div class="col-md-6">
                            <h6 class="mb-0">Statement of Account List</h6>
                        </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-icon btn-3 bg-gray-900" data-bs-toggle="modal" data-bs-target="#generateSOA">
                                <span class="btn-inner--icon"><i class="fas fa-file-pdf text-white"></i></span>
                                <span class="btn-inner--text text-white">Generate SOA</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-center text-xs font-weight-bold">Contract ID</th>
                                    <th class="text-center text-center text-xs font-weight-bold">Reference Number</th>
                                    <th class="text-center text-center text-xs font-weight-bold">Customer Name</th>
                                    <th class="text-center text-center text-xs font-weight-bold">Amount</th>
                                    <th class="text-center text-center pe-4 text-xs font-weight-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT soa.*, customer.fullname FROM soa LEFT JOIN customer ON soa.customer_id = customer.id";
                                $result = $conn->query($query);
                                while($row = $result->fetch_assoc()) {
                               
                                    ?>
                                    <tr>
                                        <td class="text-center font-weight-bold"><?php echo $row['id']; ?></td>
                                        <td class="text-center font-weight-bold"><?php echo $row['ref_number']; ?></td>
                                        <td class="text-center font-weight-bold"><?php echo $row['fullname']; ?></td>
                                        <td class="text-center font-weight-bold">PHP <?php echo number_format($row['amount'], 2); ?></td>
                                        <td class="text-center font-weight-bold">
                                            <?php if ($row['status'] == 1 || strtolower($row['status']) == 'paid'): ?>
                                                <span class="badge badge-sm bg-success">Paid</span>
                                            <?php else: ?>
                                                <span class="badge badge-sm bg-danger">Unpaid</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/soa.js"></script>
<?php include 'modal/soa_modal.php'; ?>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>

