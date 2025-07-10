<?php
include '../model/conn.php';
session_start();

header('Content-Type: application/json');

try {
    $id = isset($_POST['id']) && $_POST['id'] !== 'null' ? $_POST['id'] : null;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    $coordinates = $_POST['coordinates'];
    $owner_id = isset($_POST['owner_id']) && !empty($_POST['owner_id']) ? $_POST['owner_id'] : null;

    if (empty($title)) {
        throw new Exception("Title is required");
    }

    if (!in_array($status, ['available', 'reserved', 'occupied', 'maintenance', 'sold', 'hold', 'obstructed'])) {
        throw new Exception("Invalid status value");
    }

    $conn->begin_transaction();

    if ($id) {
        $stmt = $conn->prepare("UPDATE plots SET title=?, description=?, status=?, coordinates=?, owner_id=? WHERE id=?");
        $stmt->bind_param("ssssii", $title, $description, $status, $coordinates, $owner_id, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO plots (title, description, status, coordinates, owner_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $title, $description, $status, $coordinates, $owner_id);
    }

    if ($stmt->execute()) {
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
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?> 