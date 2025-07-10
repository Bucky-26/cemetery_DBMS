<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $customer_id = $_POST['customer_id'];
    $con_date = $_POST['con_date'];
    $con_end = $_POST['con_end'];
    $amount = $_POST['amount'];
    $downpayment = $_POST['downpayment'];
    $no_installment = $_POST['no_installment'];
    
    // Calculate new values
    $remaining_balance = $amount - $downpayment;
    $installment = $remaining_balance / $no_installment;
    $balance = $amount - $downpayment;
    
    $query = "UPDATE contract SET 
              customer_id = ?,
              con_date = ?, 
              con_end = ?, 
              amount = ?, 
              downpayment = ?,
              installment = ?, 
              balance = ?
              WHERE id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issddddi", 
        $customer_id,
        $con_date, 
        $con_end, 
        $amount, 
        $downpayment,
        $installment, 
        $balance, 
        $id
    );
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Contract updated successfully!');
                window.location.href='../contract.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating contract: " . $stmt->error . "');
                window.location.href='../contract.php';
              </script>";
    }
    
    $stmt->close();
}
?> 