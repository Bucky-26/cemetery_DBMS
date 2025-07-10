<?php
require_once '../../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    try {
        // Get photo URL before deleting
        $stmt = $conn->prepare("SELECT photo_url FROM employee WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $photo_url = $stmt->fetchColumn();

        // Delete the employee record
        $stmt = $conn->prepare("DELETE FROM employee WHERE id = ?");
        $stmt->execute([$_POST['id']]);

        // Delete the photo file if it exists
        if ($photo_url && file_exists("../../" . $photo_url)) {
            unlink("../../" . $photo_url);
        }

        echo json_encode(['success' => true]);

    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?> 