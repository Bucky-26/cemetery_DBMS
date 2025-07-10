<?php
include 'conn.php';

if(isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];
    $payment_date = $_POST['payment_date'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $customer_name = $_POST['customer_name'];
    $soa_date = $_POST['soa_date'];
    
    $query = "UPDATE payment_summary SET 
              payment_date = ?,
              amount = ?,
              payment_method = ?,
              customer_name = ?,
              soa_date = ?
              WHERE payment_id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdsssi", $payment_date, $amount, $payment_method, $customer_name, $soa_date, $payment_id);
    
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?> 