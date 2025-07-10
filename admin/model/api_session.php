<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/conn.php';

// API Session Security Check
function validateApiSession() {
    // Check if user is logged in
    if (!isset($_SESSION['admin']) || trim($_SESSION['admin']) == '') {
        return false;
    }

    // Verify user exists and is active in database
    global $conn;
    $stmt = $conn->prepare("SELECT id, acc_type, status FROM accounts WHERE id = ?");
    $stmt->bind_param("s", $_SESSION['admin']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Verify user exists and is active
    if (!$user || $user['status'] !== 'active') {
        return false;
    }

    // Optional: Add additional security checks here
    // For example: Check IP, user agent, or session age
    
    return $user;
}

// Handle unauthorized access
function sendUnauthorized() {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'error' => 'Unauthorized access'
    ]);
    exit();
} 