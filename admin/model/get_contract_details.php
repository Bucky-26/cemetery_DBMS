<?php
include 'model_session.php';

if(isset($_POST['contract_id'])) {
    $contract_id = $_POST['contract_id'];
    
    $query = "SELECT c.*, 
              (SELECT COUNT(*) FROM soa WHERE contract_id = c.id) as installment_paid
              FROM contract c 
              WHERE c.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $contract_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $contract = $result->fetch_assoc();
    
    // Calculate monthly payment and other details
    $contract['monthly_payment'] = ($contract['amount'] - $contract['downpayment']) / $contract['installment'];
    $contract['total_paid'] = ($contract['monthly_payment'] * $contract['installment_paid']) + $contract['downpayment'];
    $contract['balance'] = $contract['amount'] - $contract['total_paid'];
    
    echo json_encode($contract);
}
?> 