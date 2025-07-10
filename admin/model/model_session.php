<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/conn.php';

// Check if user is authenticated
if (!isset($_SESSION['admin']) || trim($_SESSION['admin']) == '') {
    http_response_code(404);
    echo json_encode(array('error' => 'Resource not found'));
    exit();
}

// Get user data
$sql = "SELECT * FROM accounts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['admin']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If user data couldn't be fetched
if (!$user) {
    http_response_code(403);
    echo json_encode(array('error' => 'Invalid user session'));
    exit();
}
?> 