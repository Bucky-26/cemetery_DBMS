<?php
require_once '../../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Handle file upload if new photo is provided
        $photo_url = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "../images/employee/";
            $file_extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo_url = 'admin/images/employee/' . $new_filename;
                
                // Delete old photo if exists
                $stmt = $conn->prepare("SELECT photo_url FROM employee WHERE id = ?");
                $stmt->execute([$_POST['employee_id']]);
                $old_photo = $stmt->fetchColumn();
                if ($old_photo && file_exists("../../" . $old_photo)) {
                    unlink("../../" . $old_photo);
                }
            }
        }

        // Prepare SQL statement
        $sql = "UPDATE employee SET 
            first_name = :first_name,
            middle_initial = :middle_initial,
            last_name = :last_name,
            job_title = :job_title,
            email = :email,
            phone_number = :phone_number,
            hire_date = :hire_date,
            birth_date = :birth_date,
            gender = :gender,
            address = :address,
            employment_status = :employment_status,
            salary = :salary";

        // Add photo update only if new photo was uploaded
        if ($photo_url) {
            $sql .= ", photo_url = :photo_url";
        }

        $sql .= " WHERE id = :employee_id";

        $stmt = $conn->prepare($sql);

        // Bind parameters
        $params = [
            ':employee_id' => $_POST['employee_id'],
            ':first_name' => $_POST['first_name'],
            ':middle_initial' => $_POST['middle_initial'],
            ':last_name' => $_POST['last_name'],
            ':job_title' => $_POST['job_title'],
            ':email' => $_POST['email'],
            ':phone_number' => $_POST['phone_number'],
            ':hire_date' => $_POST['hire_date'],
            ':birth_date' => $_POST['birth_date'],
            ':gender' => $_POST['gender'],
            ':address' => $_POST['address'],
            ':employment_status' => $_POST['employment_status'],
            ':salary' => $_POST['salary']
        ];

        if ($photo_url) {
            $params[':photo_url'] = $photo_url;
        }

        $stmt->execute($params);

        echo json_encode(['success' => true]);

    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?> 