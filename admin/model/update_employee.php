<?php
require_once '../../includes/conn.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get form data and sanitize
        $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $job_title = mysqli_real_escape_string($conn, $_POST['job_title']);

        // Update query with proper escaping
        $sql = "UPDATE employee_info SET 
                firstname = '$firstname',
                lastname = '$lastname',
                username = '$username',
                email = '$email',
                job_title = '$job_title'
                WHERE id = '$employee_id'";

        if ($conn->query($sql)) {
            $response = [
                'status' => 'success',
                'message' => 'Employee updated successfully'
            ];
        } else {
            throw new Exception("Database error: " . $conn->error);
        }

    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Error updating employee: ' . $e->getMessage()
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method'
    ];
}

$conn->close();
echo json_encode($response);
?> 