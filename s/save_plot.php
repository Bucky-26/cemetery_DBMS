<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the received data
file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Received POST: ' . print_r($_POST, true) . "\n", FILE_APPEND);

$conn = new mysqli("localhost", "root", "", "mapdbms");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => "Connection failed: " . $conn->connect_error]));
}

try {
    // Get and validate inputs
    $id = isset($_POST['id']) && $_POST['id'] !== 'null' ? $_POST['id'] : null;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    $coordinates = $_POST['coordinates'];
    $owner_id = $_POST['owner_id'];
    // Validate required fields
    if (empty($title)) {
        throw new Exception("Title is required");
    }

    if (!in_array($status, ['available', 'occupied', 'maintenance'])) {
        throw new Exception("Invalid status value");
    }

    // Start transaction
    $conn->begin_transaction();

    if ($id) {
        // Update existing plot
        $stmt = $conn->prepare("UPDATE plots SET title=?, description=?, status=?, coordinates=?, owner_id=? WHERE id=?");
        $stmt->bind_param("sssssi", $title, $description, $status, $coordinates, $owner_id, $id);
    } else {
        // Insert new plot
        $stmt = $conn->prepare("INSERT INTO plots (title, description, status, coordinates, owner_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $title, $description, $status, $coordinates, $owner_id);
    }

    $result = $stmt->execute();
    
    if ($result) {
        $conn->commit();
        $newId = $id ?? $stmt->insert_id;
        echo json_encode([
            'success' => true,
            'id' => $newId,
            'message' => $id ? 'Plot updated successfully' : 'Plot created successfully'
        ]);
    } else {
        throw new Exception($stmt->error);
    }

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    // Log the error
    file_put_contents('error.log', date('Y-m-d H:i:s') . ' - Error: ' . $e->getMessage() . "\n", FILE_APPEND);
} finally {
    $conn->close();
}
?> 