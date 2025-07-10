<?php
include 'conn.php';

if(isset($_POST['add_contract'])) {
    $customer_id = $_POST['customer_id'];
    $contract_date = $_POST['contract_date'];
    $contract_end = $_POST['contract_end'];
    $amount = $_POST['amount'];
    $downpayment = $_POST['downpayment'];
    $no_installment = $_POST['no_installment'];
    $ref_number = $_POST['ref_number'];
    $remaining_balance = $amount - $downpayment;
    $installment = $remaining_balance / $no_installment;
    $balance = $amount - $downpayment;
    
    $query = "INSERT INTO contract (customer_id, con_date, con_end, amount, downpayment, installment, balance, ref_number) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssdddds ", 
        $customer_id, 
        $contract_date, 
        $contract_end, 
        $amount, 
        $downpayment,
        $no_installment,
        $balance,
        $ref_number
    );
    
    if($stmt->execute()) {
        echo "<script>
                alert('Contract added successfully!');
                window.location.href='../contract.php';
              </script>";
    } else {
        echo "<script>
                alert('Something went wrong! Error: " . $stmt->error . "');
                window.location.href='../contract.php';
              </script>";
    }
}
?> 