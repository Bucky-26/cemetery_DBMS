<?php
require_once(__DIR__ . '/../model/conn.php');

if(isset($_POST['burial_id'])) {
    $burialId = $_POST['burial_id'];
    
    $query = "SELECT b.*, p.title as plot_title 
              FROM burial b 
              LEFT JOIN plots p ON b.plot_id = p.id 
              WHERE b.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $burialId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        echo json_encode([
            'burial_id' => $row['id'],
            'deceased_name' => $row['name'],
            'burial_date' => $row['burial_date'],
            'plot_id' => $row['plot_id'],
            'plot_title' => $row['plot_title'],
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