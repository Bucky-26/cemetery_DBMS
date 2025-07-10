<?php
include '../model/conn.php';

header('Content-Type: application/json');

if (!isset($_POST['burial_id'])) {
    echo json_encode(['success' => false, 'message' => 'No burial ID provided']);
    exit;
}

$burial_id = intval($_POST['burial_id']);

$query = "DELETE FROM burial_only_record WHERE burial_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $burial_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$stmt->close();
$conn->close();
?> 