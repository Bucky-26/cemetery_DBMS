<?php
require_once(__DIR__ . '/../model/model_session.php');

function generateUniqueReference() {
    global $conn;
    
    do {
        // Format: YYYYMMDD + 5 random digits
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $reference = $date . $random;
        
        // Check if reference exists
        $sql = "SELECT COUNT(*) as count FROM payments WHERE ref_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $reference);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->fetch_assoc()['count'] > 0;
        $stmt->close();
        
    } while($exists);
    
    return $reference;
}

try {
    $reference = generateUniqueReference();
    echo $reference;
} catch (Exception $e) {
    http_response_code(500);
    echo "Error generating reference number";
}
?>