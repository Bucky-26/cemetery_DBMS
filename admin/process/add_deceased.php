<?php
include '../model/conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Function to send API response
    function sendResponse($success, $message, $data = null, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }

    // Enhanced input validation
    $errors = [];
    $input_data = []; // Store sanitized input for debugging
    
    if (empty($_POST['plot_id']) || !is_numeric($_POST['plot_id'])) {
        $errors[] = "Please select a valid plot";
    }
    
    if (empty($_POST['deceased_name']) || strlen($_POST['deceased_name']) > 255) {
        $errors[] = "Please enter a valid name (maximum 255 characters)";
    }
    
    if (empty($_POST['burial_date']) || !strtotime($_POST['burial_date'])) {
        $errors[] = "Please enter a valid burial date";
    }
    
    if (!empty($errors)) {
        sendResponse(false, "Validation failed", [
            'errors' => $errors,
            'received_data' => $_POST
        ], 400);
    }

    // Sanitize inputs
    $input_data = [
        'name' => trim($_POST['deceased_name']),
        'burial_date' => $_POST['burial_date'],
        'plot_id' => (int)$_POST['plot_id'],
        'remarks' => isset($_POST['remarks']) ? trim($_POST['remarks']) : ''
    ];

    $name = mysqli_real_escape_string($conn, $input_data['name']);
    $burial_date = mysqli_real_escape_string($conn, $input_data['burial_date']);
    $plot_id = $input_data['plot_id'];
    $remarks = mysqli_real_escape_string($conn, $input_data['remarks']);

    try {
        // Start transaction
        $conn->begin_transaction();

        // Improved plot verification query
        $check_plot = "SELECT status, burial_id FROM plots WHERE id = ?";
        $stmt = $conn->prepare($check_plot);
        $stmt->bind_param("i", $plot_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $plot = $result->fetch_assoc();

        if (!$plot) {
            throw new Exception("Plot not found");
        }

        // Check if plot is available (status must be 'available' and no burial_id)
        if ($plot['status'] !== 'available' || $plot['burial_id'] !== null) {
            sendResponse(false, "Plot is not available for burial", [
                'plot_status' => $plot['status'],
                'plot_id' => $plot_id,
                'burial_id' => $plot['burial_id'],
                'reason' => $plot['burial_id'] !== null ? 
                    'Plot already has a burial record' : 
                    "Plot status is '{$plot['status']}'"
            ], 400);
        }

        // Insert burial record
        $query = "INSERT INTO burial (name, burial_date, plot_id, remarks) 
                 VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssis", $name, $burial_date, $plot_id, $remarks);

        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }

        $burial_id = $conn->insert_id;
        
        // Update plot status and burial_id
        $update_plot = "UPDATE plots SET status = 'occupied', burial_id = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_plot);
        $update_stmt->bind_param("ii", $burial_id, $plot_id);
        
        if (!$update_stmt->execute()) {
            throw new Exception("Error updating plot: " . $update_stmt->error);
        }

        $conn->commit();
        
        // Success response
        sendResponse(true, "Burial record added successfully", [
            'burial_id' => $burial_id,
            'plot_id' => $plot_id,
            'name' => $name,
            'burial_date' => $burial_date
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        sendResponse(false, $e->getMessage(), [
            'input_data' => $input_data,
            'error_details' => [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]
        ], 500);
    }
}

// Handle invalid request method
sendResponse(false, "Invalid request method", ['allowed_method' => 'POST'], 405);
?> 