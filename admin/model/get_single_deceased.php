<?php
include 'model_session.php';

header('Content-Type: application/json');

if (isset($_POST['burial_id'])) {
    $burial_id = $_POST['burial_id'];
    
    try {
        $query = "SELECT b.*, p.title as plot_title 
                 FROM burial b 
                 LEFT JOIN plots p ON b.plot_id = p.id 
                 WHERE b.id = ?";
                 
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $burial_id);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            if ($data) {
                echo json_encode([
                    'burial_id' => $data['id'],
                    'burial_name' => $data['name'],
                    'burial_date' => $data['burial_date'],
                    'plot_id' => $data['plot_id'],
                    'plot_title' => $data['plot_title'],
                    'remarks' => $data['remarks']
                ]);
            } else {
                echo json_encode(['error' => 'Record not found']);
            }
        } else {
            echo json_encode(['error' => 'Query failed']);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}

$conn->close();
?>
