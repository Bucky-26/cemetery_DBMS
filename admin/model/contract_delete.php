<?php
include 'conn.php';

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // First check if there are any SOA records
        $check_soa = "SELECT COUNT(*) as count FROM soa WHERE contract_id = ?";
        $check_stmt = $conn->prepare($check_soa);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();
        
        if($row['count'] > 0) {
            // Delete SOA records first
            $soa_query = "DELETE FROM soa WHERE contract_id = ?";
            $soa_stmt = $conn->prepare($soa_query);
            $soa_stmt->bind_param("i", $id);
            $soa_stmt->execute();
        }
        
        // Then delete the contract
        $contract_query = "DELETE FROM contract WHERE id = ?";
        $contract_stmt = $conn->prepare($contract_query);
        $contract_stmt->bind_param("i", $id);
        $contract_stmt->execute();
        
        // If we got here, commit the transaction
        $conn->commit();
        echo "Contract and related records deleted successfully";
        
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Close all statements
        if(isset($check_stmt)) $check_stmt->close();
        if(isset($soa_stmt)) $soa_stmt->close();
        if(isset($contract_stmt)) $contract_stmt->close();
        $conn->close();
    }
} else {
    echo "No contract ID provided";
}
?>