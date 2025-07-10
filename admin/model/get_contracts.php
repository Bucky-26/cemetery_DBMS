<?php
include 'conn.php';

if(isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $query = "SELECT id, con_date FROM contract WHERE customer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<option value=''>Select Contract</option>";
    while($row = $result->fetch_assoc()) {
        echo "<option value='".$row['id']."'>Contract #".$row['id']." (".date('Y-m-d', strtotime($row['con_date'])).")</option>";
    }
}
?> 