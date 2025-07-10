<?php
include '../model/conn.php';

$sql = "SELECT a.*, e.firstname, e.lastname 
        FROM accounts a 
        LEFT JOIN employee e ON a.employee_id = e.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='text-center'>" . $row['id'] . "</td>";
        echo "<td class='text-center'>" . $row['username'] . "</td>";
        echo "<td class='text-center'>" . $row['firstname'] . " " . $row['lastname'] . "</td>";
        echo "<td class='text-center'>" . $row['email'] . "</td>";
        echo "<td class='text-center'>" . $row['date_added'] . "</td>";
        echo "<td class='text-center'>
                <button onclick='editAdmin(\"{$row['id']}\", \"{$row['employee_id']}\", \"{$row['email']}\", \"{$row['account_type']}\", \"{$row['username']}\")' class='btn btn-link text-secondary mb-0'>
                    <i class='material-icons text-sm me-2'>edit</i>Edit
                </button>
                <a href='javascript:void(0);' class='btn btn-link text-secondary mb-0' 
                   onclick='deleteAdmin(\"".$row['id']."\")'>
                    <i class='material-icons text-sm'>delete</i>
                </a>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
}

$conn->close();
?> 