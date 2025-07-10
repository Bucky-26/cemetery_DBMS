<?php 
include 'model_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $_name = $_POST['name'];
    $_address = $_POST['address'];
    $_contract = !empty($_POST['contract_id']) ? $_POST['contract_id'] : null;
    $_contact = $_POST['contact_number'];
    $_id = $_POST['id'];
    $sql = 'UPDATE customer SET fullname = ?, address = ?, contract_id = ?, contact = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssisi', $_name, $_address, $_contract , $_contact, $_id);

    if($stmt->execute()){
        $_SESSION['success'] = "Customer Updated";
    }else{
        $_SESSION['error'] = "Error occurred while updating the customer";
    }

    header('location: ../customer.php');
    exit();
}
?>