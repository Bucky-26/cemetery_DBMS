<?php
include 'model_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['id'])){
    $id = $_POST['id'];
    
    $query = "DELETE FROM contract WHERE contract_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Contract deleted successfully!";
    } else {
        echo "Error deleting contract: " . $stmt->error;
    }
    
    }else{
        echo "Error deletins contract: " . $stmt->error;
    }
    $stmt->close();

}
?> 