<?php
include '../model/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get form data
        $burial_id = $_POST['burial_id'];
        $deceased_name = $_POST['deceased_name'];
        $burial_date = $_POST['burial_date'];
        $plot_id = $_POST['plot_id'];
        $old_plot_id = $_POST['old_plot_id'];
        $remarks = $_POST['remarks'];

        // If plot_id is changed, update plot statuses
        if ($old_plot_id != $plot_id) {
            // Update old plot to available
            $update_old_plot = "UPDATE plots SET status = 'available' WHERE id = ?";
            $old_plot_update = $conn->prepare($update_old_plot);
            $old_plot_update->bind_param("i", $old_plot_id);
            $old_plot_update->execute();
            
            // Update new plot to occupied
            $update_new_plot = "UPDATE plots SET status = 'occupied' WHERE id = ?";
            $new_plot_update = $conn->prepare($update_new_plot);
            $new_plot_update->bind_param("i", $plot_id);
            $new_plot_update->execute();
        }

        // Update burial record
        $query = "UPDATE burial SET 
                  name = ?,
                  burial_date = ?,
                  plot_id = ?,
                  remarks = ?
                  WHERE id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssisi", $deceased_name, $burial_date, $plot_id, $remarks, $burial_id);

        if ($stmt->execute()) {
            header("Location: ../decease.php?success=Record updated successfully");
        } else {
            throw new Exception("Failed to update record");
        }

    } catch (Exception $e) {
        error_log("Error in edit_deceased.php: " . $e->getMessage());
        header("Location: ../decease.php?error=" . urlencode($e->getMessage()));
    }

    // Close all prepared statements
    if (isset($old_plot_stmt)) $old_plot_stmt->close();
    if (isset($old_plot_update)) $old_plot_update->close();
    if (isset($new_plot_update)) $new_plot_update->close();
    if (isset($stmt)) $stmt->close();
}

$conn->close();
?> 