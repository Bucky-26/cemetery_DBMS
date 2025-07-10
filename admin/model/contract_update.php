<?php
include 'model_session.php';

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    $customer_id = $_POST['customer_id'];
    $con_date = $_POST['con_date'];
    $con_end = $_POST['con_end'];
    $amount = $_POST['amount'];
    $installment = $_POST['installment'];
    $balance = $_POST['balance'];
    $paid_amount = $_POST['paid_amount'];
    
    $query = "UPDATE contract SET 
              customer_id = ?,
              con_date = ?,
              con_end = ?,
              amount = ?,
              installment = ?,
              balance = ?,
              paid_amount = ?
              WHERE id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssdddi", $customer_id, $con_date, $con_end, $amount, $installment, $balance, $paid_amount, $id);
    
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?> 