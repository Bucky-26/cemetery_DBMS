<?php
session_start();
include_once 'conn.php';

// Add input validation and sanitization
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    header('location:/cdbms/secure/login?error=empty');
    exit();
}

// Get user data by username only
$sql = "SELECT * FROM accounts WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify the password hash
    if (password_verify($password, $user['password'])) {
        $_SESSION['admin'] = $user['id'];  // Store user ID in session
        
        // Set cookie with account ID - expires in 30 days
        setcookie('account_id', $user['id'], [
            'expires' => time() + (86400 * 30),
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        
        header('location: ../index.php');
        exit();
    } else {
        // Wrong password
        header('location: /cdbms/secure/login?error=invalid_pass');
        exit();
    }
} else {
    // User not found
    header('location: /cdbms/secure/login?error=invalid_user');
    exit();
}
?>