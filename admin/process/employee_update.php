<?php
require_once(__DIR__ . '/../model/model_session.php');

header('Content-Type: application/json');

try {
    // Get employee_id from hidden field
    $employee_id = isset($_POST['edit_employee_id']) ? (int)$_POST['edit_employee_id'] : 0;
    if (!$employee_id) {
        throw new Exception("Employee ID is required");
    }

    // Sanitize inputs using htmlspecialchars
    function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    // Initialize photo_url variable
    $photo_url = null;
    $update_photo = false;

    // Handle file upload if a new photo is provided
    if (isset($_FILES['edit_photo']) && $_FILES['edit_photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['edit_photo'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            throw new Exception("Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Generate unique filename without spaces
        $new_filename = uniqid() . '.' . $file_extension;
        
        // Correct path construction
        $upload_dir = dirname(dirname(__FILE__)) . '/images/employee/';
        $upload_path = $upload_dir . $new_filename;

        // Debug log
        error_log("Upload directory: " . $upload_dir);
        error_log("Full upload path: " . $upload_path);
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $photo_url = 'admin/images/employee/' . $new_filename;
            $update_photo = true;

            // Delete old photo if it exists and is not the default image
            if (isset($_POST['old_photo_url']) && !empty($_POST['old_photo_url'])) {
                $old_photo = __DIR__ . '/../' . $_POST['old_photo_url'];
                if (file_exists($old_photo) && strpos($old_photo, 'default.png') === false) {
                    unlink($old_photo);
                }
            }
        }
    }

    // Prepare data array with sanitized inputs
    $data = [
        'first_name' => sanitize($_POST['edit_first_name']),
        'middle_initial' => sanitize($_POST['edit_middle_initial']),
        'last_name' => sanitize($_POST['edit_last_name']),
        'email' => filter_var($_POST['edit_email'], FILTER_SANITIZE_EMAIL),
        'phone_number' => sanitize($_POST['edit_phone_number']),
        'job_title' => sanitize($_POST['edit_job_title']),
        'employment_status' => sanitize($_POST['edit_employment_status']),
        'hire_date' => sanitize($_POST['edit_hire_date']),
        'salary' => filter_var($_POST['edit_salary'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'birth_date' => sanitize($_POST['edit_birth_date']),
        'gender' => sanitize($_POST['edit_gender']),
        'address' => sanitize($_POST['edit_address'])
    ];

    // Start building the query
    $query = "UPDATE employee SET 
        first_name = ?,
        middle_initial = ?,
        last_name = ?,
        email = ?,
        phone_number = ?,
        job_title = ?,
        employment_status = ?,
        hire_date = ?,
        salary = ?,
        birth_date = ?,
        gender = ?,
        address = ?";

    // Add photo_url to query if new photo was uploaded
    $params = array_values($data);
    $types = "ssssssssdsssi"; // Base types for all fields + id

    if ($update_photo) {
        $query .= ", photo_url = ?";
        $params[] = $photo_url;
        $types = "ssssssssdssssi"; // Add 's' for photo_url
    }

    $query .= " WHERE id = ?";
    $params[] = $employee_id;

    // Debug log
    error_log("Query: " . $query);
    error_log("Params: " . print_r($params, true));
    error_log("Types: " . $types);

    // Prepare and execute the statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Employee updated successfully',
            'photo_url' => $update_photo ? $photo_url : null
        ]);
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Error in employee_update.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$conn->close(); 