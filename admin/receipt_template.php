<?php
require_once(__DIR__ . '/model/session.php');

// Get payment ID from URL
$payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : null;

if (!$payment_id) {
    die('Payment ID not provided');
}

// SQL query
$sql = "SELECT p.*, s.amount as soa_amount, p.payment_method,
        c.fullname as customer_name,
        ct.balance, ct.id
        FROM payments p
        JOIN soa s ON p.soa_id = s.id
        JOIN contract ct ON s.contract_id = ct.id
        JOIN customer c ON ct.customer_id = c.id
        WHERE p.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    die('Payment not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }
        body {
            font-family: monospace;
            margin: 0;
            padding: 10px;
            font-size: 12px;
            width: 80mm;
            white-space: pre-line;
        }
    </style>
</head>
<body>
<?php
// Create the receipt content with exact spacing and formatting
$receipt = "<center>GARDEN OF LIFE ETERNAL<br>
        Memorial Park<br>
Contact: (123) 456-7890<br>
Email: info@gole.com<br>
</center>
----------------------------------------

OFFICIAL RECEIPT

----------------------------------------

Receipt No: {$payment['ref_number']}
Date: " . date('Y-m-d h:i A', strtotime($payment['payment_date'])) . "
Contract No: 1
Customer: {$payment['customer_name']}

----------------------------------------

Payment Method: " . ucfirst($payment['payment_method']) . "
Amount Paid: P{$payment['amount']}.00
Remaining Balance: P{$payment['balance']}.00

----------------------------------------

*** Thank you for your payment! ***
Please keep this receipt for your records.
" . date('Y-m-d H:i:s');

echo $receipt;
?>
<script>
    window.onload = function() {
        if (window.location.search.includes('autoprint=true')) {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 1000);
            }, 500);
        }
    }
</script>
</body>
</html> 