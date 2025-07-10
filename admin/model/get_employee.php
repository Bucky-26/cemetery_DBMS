<?php
include 'conn.php';

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "SELECT * FROM employee_info WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    
    echo json_encode($employee);
}
?>
