<?php
require_once '../model/conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $query = "SELECT cc.*, c.fullname as customer_name 
                 FROM customercontract cc 
                 JOIN customer c ON cc.customer_id = c.id 
                 WHERE cc.contract_id = ?";
                 
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($contract = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'contract' => [
                    'contract_id' => $contract['contract_id'] ?? null,
                    'ref_number' => $contract['ref_number'] ?? null,
                    'customer_id' => $contract['customer_id'] ?? null,
                    'customer_name' => $contract['customer_name'] ?? '',
                    'con_date' => $contract['con_date'] ?? null,
                    'con_end' => $contract['con_end'] ?? null,
                    'amount' => $contract['amount'] ?? null,
                    'downpayment' => $contract['downpayment'] ?? null,
                    'installment' => $contract['installment'] ?? null
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Contract not found'
            ]);
        }
    } catch (Exception $e) {
        error_log("Error in get_contract.php: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No contract ID provided'
    ]);
} 