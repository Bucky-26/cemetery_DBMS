<?php
require_once '../../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Handle file upload if new photo is provided
        $photo_url = $_POST['current_photo_url'] ?? null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "../images/employee/";
            $file_extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo_url = 'admin/images/employee/' . $new_filename;
            }
        }

        $sql = "UPDATE employee SET 
            first_name = ?, middle_initial = ?, last_name = ?, job_title = ?,
            photo_url = ?, email = ?, phone_number = ?, hire_date = ?,
            birth_date = ?, gender = ?, address = ?, employment_status = ?,
            salary = ?
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
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
            $_POST['salary'],
            $_POST['employee_id']
        ]);

        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} 