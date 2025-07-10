<?php
// Check for required extensions
if (!extension_loaded('mbstring')) {
    die('The mbstring extension is required. Please enable it in your php.ini file.');
}

require '../model/conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function getColumnName($index) {
    $dividend = $index;
    $columnName = '';
    
    while ($dividend > 0) {
        $modulo = ($dividend - 1) % 26;
        $columnName = chr(65 + $modulo) . $columnName;
        $dividend = floor(($dividend - $modulo) / 26);
    }
    
    return $columnName;
}

try {
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator('Garden of Life Eternal')
        ->setLastModifiedBy('Garden of Life Eternal')
        ->setTitle('Payment Report')
        ->setSubject('Payment Report')
        ->setDescription('Payment Report including daily, weekly, monthly, and transaction logs');

    // Style arrays
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '1A73E8'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ];

    $bodyStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ];

    // Function to create sheet with common formatting
    function createSheet($spreadsheet, $sheetTitle) {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($sheetTitle);
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(30);
        
        return $sheet;
    }

    // Daily Report Sheet
    $dailySheet = $spreadsheet->getActiveSheet();
    $dailySheet->setTitle('Daily Report');

    // Get current month and year
    $currentMonth = date('m');
    $currentYear = date('Y');
    $daysInMonth = date('t'); // Gets number of days in current month

    // Set headers for daily report (1-31 based on month)
    $dailySheet->setCellValue('A1', 'Description');
    for($i = 1; $i <= $daysInMonth; $i++) {
        $col = getColumnName($i);
        $dailySheet->setCellValue($col.'1', $i);
        $dailySheet->getColumnDimension($col)->setWidth(12);
    }
    $dailySheet->getStyle('A1:'.getColumnName($daysInMonth).'1')->applyFromArray($headerStyle);

    // Get daily data
    $sql = "SELECT 
            DAY(payment_date) as day,
            COUNT(*) as total_payments,
            SUM(amount) as total_amount
            FROM payments 
            WHERE MONTH(payment_date) = MONTH(CURRENT_DATE())
            AND YEAR(payment_date) = YEAR(CURRENT_DATE())
            GROUP BY DAY(payment_date)";
    $result = $conn->query($sql);

    // Initialize data arrays
    $payments = array_fill(1, $daysInMonth, 0);
    $amounts = array_fill(1, $daysInMonth, 0);

    while($data = $result->fetch_assoc()) {
        $payments[$data['day']] = $data['total_payments'];
        $amounts[$data['day']] = $data['total_amount'];
    }

    // Fill in the data
    $dailySheet->setCellValue('A2', 'Total Payments');
    $dailySheet->setCellValue('A3', 'Total Amount');
    
    for($i = 1; $i <= $daysInMonth; $i++) {
        $col = getColumnName($i);
        $dailySheet->setCellValue($col.'2', $payments[$i]);
        $dailySheet->setCellValue($col.'3', number_format($amounts[$i], 2));
    }

    // Weekly Report Sheet
    $weeklySheet = createSheet($spreadsheet, 'Weekly Report');
    $weeklySheet->setCellValue('A1', 'Description');
    for($i = 1; $i <= 4; $i++) {
        $col = getColumnName($i);
        $weeklySheet->setCellValue($col.'1', 'Week '.$i);
        $weeklySheet->getColumnDimension($col)->setWidth(15);
    }
    $weeklySheet->getStyle('A1:E1')->applyFromArray($headerStyle);

    // Get weekly data
    $sql = "SELECT 
            CEIL(DAY(payment_date)/7) as week_number,
            COUNT(*) as total_payments,
            SUM(amount) as total_amount
            FROM payments 
            WHERE MONTH(payment_date) = MONTH(CURRENT_DATE())
            AND YEAR(payment_date) = YEAR(CURRENT_DATE())
            GROUP BY CEIL(DAY(payment_date)/7)";
    $result = $conn->query($sql);

    // Initialize weekly arrays
    $weeklyPayments = array_fill(1, 4, 0);
    $weeklyAmounts = array_fill(1, 4, 0);

    while($data = $result->fetch_assoc()) {
        $weeklyPayments[$data['week_number']] = $data['total_payments'];
        $weeklyAmounts[$data['week_number']] = $data['total_amount'];
    }

    // Fill in weekly data
    $weeklySheet->setCellValue('A2', 'Total Payments');
    $weeklySheet->setCellValue('A3', 'Total Amount');
    
    for($i = 1; $i <= 4; $i++) {
        $col = getColumnName($i);
        $weeklySheet->setCellValue($col.'2', $weeklyPayments[$i]);
        $weeklySheet->setCellValue($col.'3', number_format($weeklyAmounts[$i], 2));
    }

    // Monthly Report Sheet
    $monthlySheet = createSheet($spreadsheet, 'Monthly Report');
    $monthlySheet->setCellValue('A1', 'Description');
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    // Set headers for monthly report
    for($i = 0; $i < 12; $i++) {
        $col = chr(66 + $i); // Start from B (66 is ASCII for 'B')
        $monthlySheet->setCellValue($col.'1', $months[$i]);
        $monthlySheet->getColumnDimension($col)->setWidth(15); // Increased width for better readability
    }
    $monthlySheet->getStyle('A1:M1')->applyFromArray($headerStyle);

    // Get monthly data
    $sql = "SELECT 
            MONTH(payment_date) as month,
            COUNT(*) as total_payments,
            SUM(amount) as total_amount
            FROM payments 
            WHERE YEAR(payment_date) = YEAR(CURRENT_DATE())
            GROUP BY MONTH(payment_date)
            ORDER BY month";
    $result = $conn->query($sql);

    // Initialize monthly arrays (using 0-based index to match months array)
    $monthlyPayments = array_fill(0, 12, 0);
    $monthlyAmounts = array_fill(0, 12, 0);

    while($data = $result->fetch_assoc()) {
        $monthIndex = $data['month'] - 1; // Convert 1-based month to 0-based index
        $monthlyPayments[$monthIndex] = $data['total_payments'];
        $monthlyAmounts[$monthIndex] = $data['total_amount'];
    }

    // Fill in monthly data
    $monthlySheet->setCellValue('A2', 'Total Payments');
    $monthlySheet->setCellValue('A3', 'Total Amount');
    
    for($i = 0; $i < 12; $i++) {
        $col = chr(66 + $i); // Start from B
        $monthlySheet->setCellValue($col.'2', $monthlyPayments[$i]);
        $monthlySheet->setCellValue($col.'3', number_format($monthlyAmounts[$i], 2));
    }

    // Add totals at the end
    $monthlySheet->setCellValue('N1', 'Total');
    $monthlySheet->setCellValue('N2', array_sum($monthlyPayments));
    $monthlySheet->setCellValue('N3', number_format(array_sum($monthlyAmounts), 2));
    $monthlySheet->getColumnDimension('N')->setWidth(15);
    $monthlySheet->getStyle('N1')->applyFromArray($headerStyle);

    // Apply formatting to amount cells
    $monthlySheet->getStyle('B3:N3')->getNumberFormat()->setFormatCode('#,##0.00');

    // Transaction Log Sheet
    $transactionSheet = createSheet($spreadsheet, 'Transaction');
    $transactionSheet->setCellValue('A1', 'Date');
    $transactionSheet->setCellValue('B1', 'Contract ID');
    $transactionSheet->setCellValue('C1', 'SOA ID');
    $transactionSheet->setCellValue('D1', 'Amount');
    $transactionSheet->setCellValue('E1', 'Notes');
    $transactionSheet->getStyle('A1:E1')->applyFromArray($headerStyle);

    // Get transaction data
    $sql = "SELECT * FROM transaction ORDER BY transaction_date DESC";
    $result = $conn->query($sql);

    $row = 2;
    while($data = $result->fetch_assoc()) {
        $transactionSheet->setCellValue('A'.$row, date('Y-m-d', strtotime($data['transaction_date'])));
        $transactionSheet->setCellValue('B'.$row, $data['contract_id']);
        $transactionSheet->setCellValue('C'.$row, $data['soa_id']);
        $transactionSheet->setCellValue('D'.$row, number_format($data['amount_paid'], 2));
        $transactionSheet->setCellValue('E'.$row, $data['notes']);
        $row++;
    }

    // Modify the save part to include proper headers and error handling
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="payment_report_' . date('Y-m-d_His') . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    error_log("Excel Generation Error: " . $e->getMessage());
    die("Error generating report: " . $e->getMessage());
}
