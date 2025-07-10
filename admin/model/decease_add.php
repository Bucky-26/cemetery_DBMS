<?php
include 'model_session.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $deceased_name = $_POST['deceased_name'];
        $burial_date = $_POST['burial_date'];
        $plot_id = $_POST['plot_id'];
        $plot_title = $_POST['plot_title'];
        $remarks = $_POST['remarks'];

        // Log the received data
        error_log("Received POST data: " . print_r($_POST, true));
        
        // Insert into burial table
        $sql = "INSERT INTO burial (burial_name, burial_date, plot_id, plot_title, remarks) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssss", 
            $deceased_name,
            $burial_date,
            $plot_id,
            $plot_title,
            $remarks
        );
        
        if ($stmt->execute()) {
            // Update plot status to Occupied
            $update_sql = "UPDATE plots SET status = 'occupied' WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            if (!$update_stmt) {
                throw new Exception("Prepare update failed: " . $conn->error);
            }

            $update_stmt->bind_param("i", $plot_id);
            
            if (!$update_stmt->execute()) {
                throw new Exception("Failed to update plot status: " . $update_stmt->error);
            }
            
            $_SESSION['success'] = "Burial record added successfully";
        } else {
            throw new Exception("Failed to add burial record: " . $stmt->error);
        }
        
        $stmt->close();
        if (isset($update_stmt)) {
            $update_stmt->close();
        }

        header("Location: ../decease.php");
        exit();

    } catch (Exception $e) {
        error_log("Error in decease_add.php: " . $e->getMessage());
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../decease.php");
        exit();
    }
}
?> 