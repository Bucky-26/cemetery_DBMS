<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "cemetery_dbms";

try {
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");
    
    // Enable error reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
} catch (Exception $e) {
    
    echo "<script>alert('Database connection error: " . $e->getMessage() . "');</script>";
    die("Database connection error: " . $e->getMessage());


}
?>