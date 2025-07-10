<?php
include 'conn.php';

$query = "SELECT * FROM customercontract ORDER BY contract_id DESC";
$result = $conn->query($query);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='text-center text-sm'>".$row['customer_name']."</td>";
        echo "<td class='text-center text-sm'>".$row['con_date']."</td>";
        echo "<td class='text-center text-sm'>".$row['con_end']."</td>";
        echo "<td class='text-center text-sm'>₱".number_format($row['amount'], 2)."</td>";
        echo "<td class='text-center text-sm'>₱".number_format($row['installment'], 2)."</td>";
        echo "<td class='text-center text-sm'>₱".number_format($row['balance'], 2)."</td>";
        echo "<td class='text-center text-sm'>
                <button type='button' 
                        class='btn btn-link text-dark px-3 mb-0' 
                        data-action='edit' 
                        data-id='".$row['contract_id']."'>
                    <i class='fas fa-pencil-alt text-dark me-2'></i>Edit
                </button>
                <button type='button' 
                        class='btn btn-link text-danger px-3 mb-0 delete-contract' 
                        data-id='".$row['contract_id']."'>
                    <i class='fas fa-trash text-danger me-2'></i>Delete
                </button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
}
?> 