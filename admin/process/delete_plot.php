<?php
include '../model/conn.php';
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_POST['id'])) {
        throw new Exception("Plot ID is required");
    }

    $plot_id = $_POST['id'];
    
    // Start transaction
    $conn->begin_transaction();

    // Delete the plot
    $stmt = $conn->prepare("DELETE FROM plots WHERE id = ?");
    $stmt->bind_param("i", $plot_id);
    
    if ($stmt->execute()) {
        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => 'Plot deleted successfully'
        ]);
    } else {
        throw new Exception("Error deleting plot");
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