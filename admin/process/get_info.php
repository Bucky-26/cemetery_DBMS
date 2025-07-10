<?php
require_once(__DIR__ . '/../model/model_session.php');

header('Content-Type: application/json');

if(isset($_POST['employee_id'])) {
    $employeeId = $_POST['employee_id'];
    
    $query = "SELECT * FROM employee WHERE id = ? LIMIT 1";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        echo json_encode([
            'employee_id' => $row['id'],
            'first_name' => $row['first_name'],
            'middle_initial' => $row['middle_initial'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'phone_number' => $row['phone_number'],
            'job_title' => $row['job_title'],
            'employment_status' => $row['employment_status'],
            'hire_date' => $row['hire_date'],
            'salary' => $row['salary'],
            'birth_date' => $row['birth_date'],
            'gender' => $row['gender'],
            'address' => $row['address'],
            'photo_url' => $row['photo_url'] ? '/'.$row['photo_url'] : 'images/employee/defualt.png'
        ]);
    } else {
        echo json_encode(['error' => 'Record not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No ID provided']);
}

$conn->close();
?> 