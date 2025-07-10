<?php 
include 'model_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $_name = $_POST['name'];
    $_address = $_POST['address'];
    $_contract = !empty($_POST['contract_id']) ? $_POST['contract_id'] : null;
    $_contact = $_POST['contact_number'];
    
    $sql = 'INSERT INTO customer(fullname, address, contract_id, contact) VALUES(?,?,?,?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssis', $_name, $_address, $_contract , $_contact);

    if($stmt->execute()){
        $_SESSION['success'] = "Customer Added";
    }else{
        $_SESSION['error'] = "Error Occur while adding the customer";
    }

    header('location: ../customer.php');
    exit();
}
?>