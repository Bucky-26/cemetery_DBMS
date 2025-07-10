<?php
require_once(__DIR__ . '/../model/model_session.php');

header('Content-Type: application/json');

try {
    // Check if employee_id is provided
    if (!isset($_POST['employee_id'])) {
        throw new Exception('Employee ID is required');
    }

    // Sanitize and validate employee_id
    $employee_id = filter_var($_POST['employee_id'], FILTER_VALIDATE_INT);
    if ($employee_id === false) {
        throw new Exception('Invalid Employee ID');
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // First check if employee exists
        $check_query = "SELECT id FROM employee WHERE id = ?";
        $check_stmt = $conn->prepare($check_query);
        if (!$check_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $check_stmt->bind_param("i", $employee_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Employee not found");
        }

        $check_stmt->close();

        // Delete the employee
        $delete_query = "DELETE FROM employee WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        if (!$delete_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $delete_stmt->bind_param("i", $employee_id);

        if (!$delete_stmt->execute()) {
            throw new Exception("Delete failed: " . $delete_stmt->error);
        }

        if ($delete_stmt->affected_rows === 0) {
            throw new Exception("No employee was deleted");
        }

        $delete_stmt->close();

        // If we get here, commit the transaction
        $conn->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Employee deleted successfully'
        ]);

    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        throw $e;
    }

} catch (Exception $e) {
    error_log("Error in employee_delete.php: " . $e->getMessage());
} 