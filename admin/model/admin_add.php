<?php
include 'model_session.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $account_type = $_POST['role'];
    $employee_id = $_POST['employee_id'];

    // Check if passwords match
    if($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_email = $conn->prepare("SELECT id FROM accounts WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $email_result = $check_email->get_result();
    
    if($email_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit();
    }

    // Existing username check
    $check = $conn->prepare("SELECT id FROM accounts WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();
    
    if($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit();
    }

    $sql = "INSERT INTO accounts (username, password, email, account    _type, employee_id, date_added) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $hashed_password, $email, $account_type, $employee_id);

    if ($stmt->execute()) {
        header('Location: ../adminuser.php');
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding admin: ' . $conn->error]);
    }
    exit();
}else{
    
}
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
?> 