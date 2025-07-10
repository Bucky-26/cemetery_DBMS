<?php
require_once 'model_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Handle file upload
        $photo_url = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "../images/employee/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['photo']['type'], $allowed_types)) {
                throw new Exception('Invalid file type. Only JPG, PNG and GIF are allowed.');
            }
            
            // Generate unique filename
            $file_extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo_url = 'admin/images/employee/' . $new_filename;
            }
        }

        $sql = "INSERT INTO employee (
            first_name, middle_initial, last_name, job_title, photo_url,
            email, phone_number, hire_date, birth_date, gender,
            address, employment_status, salary
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            $_POST['first_name'],
            $_POST['middle_initial'],
            $_POST['last_name'],
            $_POST['job_title'],
            $photo_url,
            $_POST['email'],
            $_POST['phone_number'],
            $_POST['hire_date'],
            $_POST['birth_date'],
            $_POST['gender'],
            $_POST['address'],
            $_POST['employment_status'],
            $_POST['salary']
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Employee added successfully']);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?> 