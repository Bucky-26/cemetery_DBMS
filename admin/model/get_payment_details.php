<?php
require('../vendor/fpdf186/fpdf.php');
include 'conn.php';

if(isset($_GET['id'])) {
    $transaction_id = $_GET['id'];
    
    $query = "SELECT t.*, c.id as contract_number, cu.fullname, cu.address, cu.contact,
              s.status, s.soa_date 
              FROM transaction t 
              JOIN contract c ON t.contract_id = c.id 
              JOIN customer cu ON c.customer_id = cu.id 
              LEFT JOIN soa s ON t.soa_id = s.id 
              WHERE t.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();
    
    // Create PDF
    $pdf = new FPDF('P', 'mm', array(80, 150)); // Receipt size paper
    $pdf->AddPage();
    
    // Add header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'PAYMENT RECEIPT', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 5, 'Transaction #' . $transaction_id, 0, 1, 'C');
    $pdf->Cell(0, 5, date('M d, Y h:i A', strtotime($payment['transaction_date'])), 0, 1, 'C');
    
    // Add customer details
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 5, 'Customer: ' . $payment['fullname'], 0, 1);
    $pdf->Cell(0, 5, 'Contract #: ' . $payment['contract_number'], 0, 1);
    
    // Add payment details
    $pdf->Ln(5);
    $pdf->Cell(0, 5, 'Payment Details:', 0, 1);
    $pdf->Cell(0, 5, 'Amount Paid: PHP ' . number_format($payment['amount_paid'], 2), 0, 1);
    $pdf->Cell(0, 5, 'Payment Type: ' . $payment['notes'], 0, 1);
    
    // Add footer
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 5, 'Thank you for your payment!', 0, 1, 'C');
    
    // Output PDF
    $pdf->Output('Receipt_'.$transaction_id.'.pdf', 'I');
}
?>
