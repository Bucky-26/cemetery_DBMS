<?php 
include 'model_session.php';
header('Content-Type: application/json');

if(isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "SELECT * FROM customer WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode(
            [
                'id' => $data['id'],
                'fullname' => $data['fullname'],
                'address' => $data['address'],
                'contract_id' => $data['contract_id'],
                'contact' => $data['contact']
            ]
        );
    } else {
        echo json_encode(['error' => 'Query failed']);
    }

    $stmt->close();
}
?>