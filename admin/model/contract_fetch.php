<?php
include 'model_session.php';

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $query = "SELECT * FROM contract WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    }else{
        echo json_encode(array('error' => 'No records found'));
    }
}
?> 