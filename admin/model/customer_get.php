<?php
require_once 'conn.php';

$query = "SELECT * FROM customer ORDER BY id ASC";
$query_run = mysqli_query($conn, $query);

if(mysqli_num_rows($query_run) > 0) {
    while($row = mysqli_fetch_assoc($query_run)) {
        $id = htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8');
        $fullname = htmlspecialchars($row['fullname'] ?? '', ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($row['address'] ?? '', ENT_QUOTES, 'UTF-8');
        $contract_id = htmlspecialchars($row['contract_id'] ?? '', ENT_QUOTES, 'UTF-8');
        $contact_number = htmlspecialchars($row['contact'] ?? '', ENT_QUOTES, 'UTF-8');

        echo "<tr>
                <td class='align-middle text-center'> <h6 class='text-xs font-weight-bold' id='customer_id'>$id</h6></td>
                <td class='align-middle text-center'> <p class='mb-0 text-xs' id='customer_fullname'>$fullname</p></td>
                <td class='align-middle text-center'> <p class='text-xs' id='customer_address'>$address</p></td>
                <td class='align-middle text-center'> <p class='text-xs' id='customer_contract_id'>$contract_id</p></td>
                <td class='align-middle text-center'> <p class='text-xs' id='customer_contact_number'>$contact_number</p></td>
                <td class='align-middle text-center'>
                <div class='ms-auto'>
                    <button type='button' class='btn btn-link text-dark px-3 mb-0' onclick='editCustomer($id)' data-bs-toggle='modal' data-bs-target='#editCustomerModal'>
                        <i class='fas fa-pencil-alt text-dark me-2'></i>Edit
                    </button>
                    <a href='javascript:void(0)' onclick='deleteCustomer($id)' class='btn btn-link text-danger px-3 mb-0'>
                        <i class='far fa-trash-alt me-2'></i>Delete
                    </a>
                </div>
            </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
}
?>
