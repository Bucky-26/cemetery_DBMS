<?php
require_once 'model/session.php';  // Adjust path as needed
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Owner</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="p-3">
    <h4>Select Owner</h4>
    <table id="customerTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, fullname, contact, address FROM customer";
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                echo "<td>
                        <button type='button' 
                                class='btn btn-sm btn-primary' 
                                onclick='selectOwner(" . json_encode($row['id']) . ", " . json_encode($row['fullname']) . ")'>
                            Select
                        </button>
                    </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customerTable').DataTable();
        });

        function selectOwner(id, fullname) {
            try {
                if (!window.opener) {
                    throw new Error('Parent window not accessible');
                }

                const ownerIdField = window.opener.document.querySelector('#owner_id');
                const ownerNameField = window.opener.document.querySelector('#owner_name');

                if (!ownerIdField || !ownerNameField) {
                    throw new Error('Could not find owner input fields');
                }

                ownerIdField.value = id;
                ownerNameField.value = fullname;

                window.close();
            } catch (error) {
                console.error('Error in selectOwner:', error);
                alert('Error setting owner: ' + error.message);
            }
        }
    </script>
</body>
</html>
