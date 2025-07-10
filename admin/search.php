<?php
include 'model/conn.php';
$query = isset($_GET['query']) ? $_GET['query'] : '';

error_log("Incoming search query: " . $query);

if ($query) {
    $sql = "SELECT box_id, name, details FROM plots WHERE LOWER(details) LIKE LOWER(?)";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("s", $searchTerm);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'box_id' => $row['box_id'],
                'name' => $row['name'],
                'details' => $row['details']
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        error_log("SQL execution error: " . $stmt->error);
        http_response_code(500);
        echo json_encode(['error' => 'Database query failed.']);
    }
    
    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No search query provided.']);
}

$conn->close();
?>