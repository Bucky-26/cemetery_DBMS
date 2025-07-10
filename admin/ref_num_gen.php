<?php

class ReferenceNumberGenerator {
    private const SOA_PREFIX = 'PPMPSOA';
    private const TXN_PREFIX = 'PPMPTXN';
    private const PYMT_PREFIX = 'PPMPPYMT';
    private const CON_PREFIX = 'PPMPCON';

    /**
     * Generate SOA reference number
     * Format: PPMPSOA + YYMMDD + XXXXX
     */
    public function generateSoaReference() {
        $date = date('ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return self::SOA_PREFIX . $date . $random;
    }

    /**
     * Generate Transaction reference number
     * Format: PPMPTXN + YYMMDD + XXXXX
     */
    public function generateTransactionReference() {
        $date = date('ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return self::TXN_PREFIX . $date . $random;
    }

    /**
     * Generate Payment reference number
     * Format: PPMPPYMT + YYMMDD + XXXXX
     */
    public function generatePaymentReference() {
        $date = date('ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return self::PYMT_PREFIX . $date . $random;
    }

    /**
     * Generate Contract reference number
     * Format: PPMPCON + YYMMDD + XXXXX
     */
    public function generateContractReference() {
        $date = date('ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return self::CON_PREFIX . $date . $random;
    }
}

// API endpoint example
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    
    $generator = new ReferenceNumberGenerator();
    $type = $_GET['type'] ?? '';
    
    $response = ['success' => true, 'reference' => ''];
    
    switch(strtolower($type)) {
        case 'soa':
            $response['reference'] = $generator->generateSoaReference();
            break;
        case 'transaction':
            $response['reference'] = $generator->generateTransactionReference();
            break;
        case 'payment':
            $response['reference'] = $generator->generatePaymentReference();
            break;
        case 'contract':
            $response['reference'] = $generator->generateContractReference();
            break;
        default:
            $response = ['success' => false, 'message' => 'Invalid reference type'];
    }
    
    echo json_encode($response);
    exit;
}
?>
