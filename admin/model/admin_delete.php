<?php
session_start();
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_id'])) {
    $admin_id = $_POST['admin_id'];
    
    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM accounts WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Admin account successfully deleted.";
    } else {
        $_SESSION['error'] = "Error deleting admin account: " . $conn->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

$conn->close();
header("Location: /admin/adminuser.php");
exit();
?> 