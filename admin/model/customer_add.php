<?php
include 'model_session.php';

if(isset($_POST['submit'])) {
    // Get form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contract_id = $_POST['contract_id'];
    $contact_number = $_POST['contact_number'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO customers (name, address, contract_id, contact_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $address, $contract_id, $contact_number);

    // Execute the statement
    if($stmt->execute()) {
        header("Location: ../customer.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?> 