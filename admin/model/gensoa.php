<?php
require('../vendor/fpdf186/fpdf.php');
include 'model_session.php';

if(isset($_POST['contract_id'])) {
    $contract_id = $_POST['contract_id'];
    
    // Get SOA reference number
    $ref_response = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/admin/ref_num_gen.php?type=soa');
    $ref_data = json_decode($ref_response, true);
    $soa_reference = $ref_data['reference'] ?? 'SOA-' . uniqid();
    
    // Get contract and customer details
    $query = "SELECT c.*, cu.fullname, cu.address, cu.contact,
              (SELECT COUNT(*) FROM soa WHERE contract_id = c.id) as installment_paid 
              FROM contract c 
              JOIN customer cu ON c.customer_id = cu.id 
              WHERE c.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $contract_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    // Calculate payments
    $monthly_payment = ($data['amount'] - $data['downpayment']) / $data['installment'];
    $total_paid = ($monthly_payment * $data['installment_paid']) + $data['downpayment'];
    $remaining_balance = $data['amount'] - $total_paid;
    
    // Create PDF with custom styling
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(20, 20, 20);

    // Header Section - Centered Statement of Account
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Statement of Account', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 5, 'Reference Number: ' . $soa_reference, 0, 1, 'L');
    $pdf->Cell(0, 10, 'As of ' . date('F d, Y'), 0, 1, 'L');
    $pdf->Ln(5);

    // Customer Information Section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Customer Information', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 11);

    // Add bottom border to cells
    $pdf->SetDrawColor(220, 220, 220); // Light gray color for borders

    // Customer Info Table
    $pdf->Cell(40, 10, 'Name:', 'B', 0, 'L');
    $pdf->Cell(130, 10, $data['fullname'], 'B', 1, 'L');

    $pdf->Cell(40, 10, 'Address:', 'B', 0, 'L');
    $pdf->Cell(130, 10, $data['address'], 'B', 1, 'L');

    $pdf->Cell(40, 10, 'Contact:', 'B', 0, 'L');
    $pdf->Cell(130, 10, $data['contact'], 'B', 1, 'L');

    $pdf->Ln(10);

    // Contract Details Section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Contract Details', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 11);

    // Contract Details Table
    $details = array(
        'Contract #' => $contract_id,
        'Total Contract Amount' => 'PHP ' . number_format($data['amount'], 2),
        'Down Payment' => 'PHP ' . number_format($data['downpayment'], 2),
        'Monthly Payment' => 'PHP ' . number_format($monthly_payment, 2),
        'Number of Installments' => $data['installment'],
        'Installments Paid' => $data['installment_paid'],
        'Total Amount Paid' => 'PHP ' . number_format($total_paid, 2),
        'Remaining Balance' => 'PHP ' . number_format($remaining_balance, 2)
    );

    foreach($details as $label => $value) {
        $pdf->Cell(60, 10, $label . ':', 'B', 0, 'L');
        $pdf->Cell(110, 10, $value, 'B', 1, 'L');
    }

    $pdf->Ln(10);

    // Add Transaction History
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Transaction History:', 0, 1);
    
    // Add table headers
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(50, 8, 'Date', 1);
    $pdf->Cell(50, 8, 'Amount Paid', 1);
    $pdf->Cell(90, 8, 'Notes', 1);
    $pdf->Ln();
    
    // Get transaction history
    $query = "SELECT * FROM transaction 
              WHERE contract_id = ? 
              ORDER BY transaction_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $contract_id);
    $stmt->execute();
    $transactions = $stmt->get_result();
    
    // Add transaction rows
    $pdf->SetFont('Arial', '', 11);
    while($trans = $transactions->fetch_assoc()) {
        $pdf->Cell(50, 8, date('M d, Y', strtotime($trans['transaction_date'])), 1);
        $pdf->Cell(50, 8, 'PHP '.number_format($trans['amount_paid'], 2), 1);
        $pdf->Cell(90, 8, $trans['notes'], 1);
        $pdf->Ln(); 
    }
    
    // Add footer
    $pdf->Ln(20);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'This is a computer-generated document. No signature required.', 0, 1, 'C');
    
    // First, insert the record into database
    try {
        // Insert SOA record first
        $query = "INSERT INTO soa (ref_number, soa_date, amount, customer_id, contract_id) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            die("Prepare failed: " . $conn->error);
        }
        
        $current_date = date('Y-m-d'); // Get current date in proper format
        
        $stmt->bind_param("ssdii", 
            $soa_reference,
            $current_date,
            $monthly_payment, 
            $data['customer_id'], 
            $contract_id
        );
        
        $insert_result = $stmt->execute();
        
        if (!$insert_result) {
            error_log("Execute failed: " . $stmt->error);
            die("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
        
        // Then generate and output PDF
        $pdf->Output('SOA_'.$contract_id.'.pdf', 'D');
        
    } catch (Exception $e) {
        error_log("SOA Generation Error: " . $e->getMessage());
        die("Error: " . $e->getMessage());
    }
}

// Add this at the end to ensure proper connection closure
if (isset($conn)) {
    $conn->close();
}
?>
