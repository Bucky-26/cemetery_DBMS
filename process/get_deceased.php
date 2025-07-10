<?php
require_once('../model/conn.php');

if(isset($_POST['burial_id'])) {
    $burialId = $_POST['burial_id'];
    
    $query = "SELECT * FROM burial_records WHERE burial_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $burialId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        echo json_encode([
            'burial_id' => $row['burial_id'],
            'deceased_name' => $row['deceased_name'],
            'burial_date' => $row['burial_date'],
            'plot_id' => $row['plot_id'],
            'plot_title' => $row['plot_title'],
            'plot_id' => $row['plot_id'],
            'remarks' => $row['remarks']
        ]);
    } else {
        echo json_encode(['error' => 'Record not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No ID provided']);
}

$conn->close();
?> 