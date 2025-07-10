<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug: Print all POST data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    $admin_id = $_POST['admin_id'];
    $employee_id = $_POST['employee_id'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $account_type = $_POST['account_type'];

    // Debug: Print specific variables
    echo "Account Type: " . $account_type;

    try {
        // Start transaction
        $conn->begin_transaction();

        // Check if username already exists for other users
        $check_username = $conn->prepare("SELECT id FROM accounts WHERE username = ? AND id != ?");
        $check_username->bind_param("si", $username, $admin_id);
        $check_username->execute();
        $result = $check_username->get_result();
        
        if ($result->num_rows > 0) {
            header("Location: /admin/adminuser.php?error=1"); // Username exists
            exit();
        }

        // Prepare update statement based on whether password is being changed
        if (!empty($password)) {
            // Check if passwords match
            if ($_POST['password'] !== $_POST['confirm_password']) {
                header("Location: /admin/adminuser.php?error=2"); // Passwords don't match
                exit();
            }
            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE accounts SET 
                    employee_id = ?,
                    email = ?,
                    username = ?,
                    password = ?,
                    account_type = ?
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssi", $employee_id, $email, $username, $hashed_password, $account_type, $admin_id);
        } else {
            // Update without changing password
            $sql = "UPDATE accounts SET 
                    employee_id = ?,
                    email = ?,
                    username = ?,
                    account_type = ?
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssi", $employee_id, $email, $username, $account_type, $admin_id);
        }

        // Execute the update
        if ($stmt->execute()) {
            $conn->commit();
            header("Location: /admin/adminuser.php?success=2"); // Success message for update
            exit();
        } else {
            throw new Exception("Error executing update");
        }

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: /admin/adminuser.php?error=3"); // Error updating account
        exit();
    } finally {
        $conn->close();
    }
} else {
    header("Location: /admin/adminuser.php");
    exit();
}
?> 