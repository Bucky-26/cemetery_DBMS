<?php
include 'model/session.php';
include 'model/conn.php';

// Base query without search (get all records)
$query = "SELECT * FROM unpaid_soa ORDER BY soa_date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.php'; ?>
<style>
    body {
        background: #f8f9fa;
    }
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
    }
    .search-box {
        margin-bottom: 20px;
        position: relative;
    }
    .search-box input {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }
    .search-box input:focus {
        outline: none;
        border-color: #666;
    }
    .table {
        background: white;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .table thead th {
        background: #343a40;
        color: white;
        border: none;
        padding: 12px;
    }
    .table tbody tr:hover {
        background-color: #f5f5f5;
    }
    .btn-select {
        background: #343a40;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 3px;
        font-size: 13px;
    }
    .btn-select:hover {
        background: #23272b;
    }
    .hide {
        display: none;
    }
</style>
<body>
    <div class="container">
        <h4 class="mb-4">Select Statement of Account</h4>
        
        <div class="search-box">
            <input type="text" 
                   id="searchInput" 
                   placeholder="Search by Customer Name, SOA ID, or Contract ID"
                   onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SOA ID</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Contract ID</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Balance</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['soa_id']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['soa_date'])); ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['contract_id']; ?></td>
                        <td class="text-end">₱ <?php echo number_format($row['soa_amount'], 2); ?></td>
                        <td class="text-end">₱ <?php echo number_format($row['contract_balance'], 2); ?></td>
                        <td class="text-center">
                            <button class="btn-select" 
                                    onclick="selectSOA('<?php echo $row['soa_id']; ?>', 
                                                     '<?php echo $row['customer_name']; ?>', 
                                                     <?php echo $row['soa_amount']; ?>)">
                                Select
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const tbody = document.getElementById('tableBody');
        const rows = tbody.getElementsByTagName('tr');

        for (let row of rows) {
            const soaId = row.cells[0].textContent;
            const customerName = row.cells[2].textContent;
            const contractId = row.cells[3].textContent;
            
            if (soaId.toLowerCase().includes(filter) || 
                customerName.toLowerCase().includes(filter) || 
                contractId.toLowerCase().includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    function selectSOA(soaId, customerName, soaAmount) {
        window.opener.document.getElementById('add_soa_id').value = soaId;
        window.opener.document.getElementById('add_soa_display').value = "SOA No. " + soaId;
        window.opener.document.getElementById('add_customer_name').value = customerName;
        window.opener.document.getElementById('add_amount').value = soaAmount;
        window.close();
    }
    </script>

    <?php include 'includes/script.php'; ?>
</body>
</html>