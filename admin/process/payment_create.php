<?php
require_once(__DIR__ . '/../model/model_session.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Start transaction
        $conn->begin_transaction();

        // Set timezone and get payment date
        date_default_timezone_set('Asia/Manila');
        $payment_date = date('Y-m-d');

        // Get payment details from POST
        $amount = $_POST['amount'];
        $soa_id = $_POST['soa_id'];
        $payment_method = $_POST['payment_type'];
        $payment_ref_number = $_POST['reference_no'];   
        $remarks = $_POST['remarks'];

        // Get transaction reference number
        $txn_ref_response = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/admin/ref_num_gen.php?type=transaction');
        $txn_ref_data = json_decode($txn_ref_response, true);
        if (!$txn_ref_data['success']) {
            throw new Exception("Failed to generate transaction reference number");
        }
        $transaction_ref_number = $txn_ref_data['reference'];

        // Validate required fields
        if (empty($amount) || empty($soa_id) || empty($payment_method) || empty($payment_ref_number)) {
            throw new Exception("All required fields must be filled");
        }

        // Get contract_id and amount from SOA
        $soa_sql = "SELECT contract_id, amount FROM soa WHERE id = ?";
        $soa_stmt = $conn->prepare($soa_sql);
        $soa_stmt->bind_param("i", $soa_id);
        $soa_stmt->execute();
        $soa_result = $soa_stmt->get_result();
        $soa_data = $soa_result->fetch_assoc();
        
        if (!$soa_data) {
            throw new Exception("SOA not found");
        }

        $contract_id = $soa_data['contract_id'];
        $soa_amount = $soa_data['amount'];

        // Insert payment
        $sql = "INSERT INTO payments (payment_date, amount, soa_id, payment_method, ref_number, remarks) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdisss", $payment_date, $amount, $soa_id, $payment_method, $payment_ref_number, $remarks);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert payment");
        }

        // Get the last inserted payment ID
        $payment_id = $conn->insert_id;

        // After successful payment insert, add transaction record
        $transaction_sql = "INSERT INTO transaction (contract_id, soa_id, transaction_date, amount_paid, notes, ref_number) 
                           VALUES (?, ?, ?, ?, ?, ?)";
        $transaction_stmt = $conn->prepare($transaction_sql);
        $transaction_notes = "Payment for SOA #" . $soa_id;
        $transaction_stmt->bind_param("iisdss", $contract_id, $soa_id, $payment_date, $amount, $transaction_notes, $transaction_ref_number);

        if (!$transaction_stmt->execute()) {
            throw new Exception("Failed to record transaction");
        }

        // Update contract balance
        $update_contract_sql = "UPDATE contract
                              SET balance = balance - ?, installment_paid = installment_paid + 1
                              WHERE id = ?";
        $update_contract_stmt = $conn->prepare($update_contract_sql);
        $update_contract_stmt->bind_param("di", $soa_amount, $contract_id);
        
        if (!$update_contract_stmt->execute()) {
            throw new Exception("Failed to update contract balance");
        }
        $soa_update_sql = "UPDATE soa SET status = 1 WHERE id = ?";
        $soa_update_stmt = $conn->prepare($soa_update_sql);
        $soa_update_stmt->bind_param("i", $soa_id);
        $soa_update_stmt->execute();
        // Commit transaction
        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'payment_id' => $payment_id
        ]);
        

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?> 