<?php
include 'model_session.php';

if(isset($_POST['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['delete_id']);
    
    $query = "DELETE FROM customer WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Customer deleted successfully";
        echo "success";
    } else {
        echo "error";
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
