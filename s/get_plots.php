<?php
$conn = new mysqli("localhost", "root", "", "mapdbms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM plots";
$result = $conn->query($sql);
$plots = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $plots[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($plots);
$conn->close();
?> 