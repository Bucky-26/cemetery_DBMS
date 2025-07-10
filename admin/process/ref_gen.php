<?php
require_once '../model/model_session.php';

// Set the response header to JSON
header('Content-Type: application/json');

try {
    // Get the current date
    $currentDate = date('Ymd');
    
    // Query to get the latest reference number for today
    $sql = "SELECT ref_number 
            FROM payments 
            WHERE ref_number LIKE ? 
            ORDER BY ref_number DESC 
            LIMIT 1";
            
    $pattern = $currentDate . '%';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $pattern);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // If there's an existing reference number today, increment it
        $lastNumber = substr($row['ref_number'], -3); // Get last 3 digits
        $nextNumber = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // If no reference number exists for today, start with 001
        $nextNumber = '001';
    }
    
    // Generate new reference number (format: YYYYMMDD001)
    $newReference = $currentDate . $nextNumber;
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'reference' => $newReference
    ]);
    exit();
    
} catch (Exception $e) {
    // Return error JSON response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error generating reference number: ' . $e->getMessage()
    ]);
    exit();
}
?>