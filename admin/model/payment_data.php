<?php
header('Content-Type: application/json; charset=UTF-8'); // Set the content type to JSON

include 'model_session.php'; // Include the database connection

$response = []; // Initialize the response array

try {
    // Get ID from request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Modify query to include ID filter if provided
    $query = "SELECT * from payment_summary order by payment_id asc";
   
    
    $query_run = mysqli_query($conn, $query);

    // Initialize response structure
    $response = [
        'status' => 'success',
        'message' => '',
        'data' => []
    ];

    // Check if the query runs successfully
    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            while ($row = mysqli_fetch_assoc($query_run)) {
                $response['data'][] = [
                    'id' => htmlspecialchars($row['payment_id'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'fullname' => htmlspecialchars($row['customer_name'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'address' => htmlspecialchars($row['payment_date'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'contract_id' => htmlspecialchars($row['payment_method'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'payment_date' => htmlspecialchars($row['payment_date'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'payment_method' => htmlspecialchars($row['payment_method'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'amount' => htmlspecialchars($row['amount'] ?? '', ENT_QUOTES, 'UTF-8'),
                ];
            }
            $response['message'] = 'Data retrieved successfully';
        } else {
            // No error, but no data found
            $response['status'] = 'success';
            $response['message'] = 'No records found';
            $response['data'] = [];
        }
    } else {
        // Query execution failed
        $response['status'] = 'error';
        $response['message'] = 'Database query failed: ' . mysqli_error($conn);
        $response['data'] = [];
    }
} catch (Exception $e) {
    // Catch unexpected errors
    $response['status'] = 'error';
    $response['message'] = 'An unexpected error occurred: ' . $e->getMessage();
    $response['data'] = [];
}

// Return the response as JSON
echo json_encode($response, JSON_PRETTY_PRINT);
?>
