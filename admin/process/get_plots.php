<?php
require '../model/conn.php';

try {
    // First, let's check what data we have
    $sql = "SELECT * FROM burial_record WHERE coordinates IS NOT NULL";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $plots = [];
    while($row = $result->fetch_assoc()) {
        // Clean up the coordinates string by removing escaped quotes and backslashes
        $coordinates = $row['coordinates'];
        if ($coordinates) {
            // Remove any surrounding quotes
            $coordinates = trim($coordinates, '"');
            // Remove escaped backslashes
            $coordinates = stripslashes($coordinates);
        }
        
        $plots[] = [
            'id' => $row['plot_id'],
            'description' => $row['plot_description'],
            'title' => $row['plot_title'],
            'status' => $row['plot_status'],
            'coordinates' => $coordinates,
            'burial_id' => $row['burial_id'],
            'burial_name' => $row['burial_name'],
            'burial_date' => $row['burial_date'],
            'remarks' => $row['remarks'],
            'owner_id' => $row['owner_id'],
            'owner_name' => $row['owner_name'],
            'owner_contact' => $row['owner_contact'],
            'owner_address' => $row['owner_address']

        ];
    }

    // Log the final array
    error_log("Sending plots: " . print_r($plots, true));

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true, 
        'data' => $plots,
        'debug' => [
            'count' => count($plots),
            'sample' => !empty($plots) ? $plots[0] : null
        ]
    ]);

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}

$stmt->close();
$conn->close(); 