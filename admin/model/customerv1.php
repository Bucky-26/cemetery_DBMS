<?php
header('Content-Type: application/json; charset=UTF-8'); // Set the content type to JSON

include 'model_session.php'; // Include the database connection

$response = []; // Initialize the response array

try {
    // Get ID from request body
    $data = json_decode(file_get_contents('php://input'), true);
    $customer_id = isset($data['id']) ? mysqli_real_escape_string($conn, $data['id']) : null;

    // Modify query to include ID filter if provided
    $query = "SELECT id, fullname, address, contract_id, contact FROM customer";
    if ($customer_id) {
        $query .= " WHERE id = '$customer_id'";
    }
    $query .= " ORDER BY id ASC";
    
    $query_run = mysqli_query($conn, $query);

    // Check if the query runs successfully
    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            // Fetch records and build the response
            while ($row = mysqli_fetch_assoc($query_run)) {
                $response[] = [
                    'id' => htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'fullname' => htmlspecialchars($row['fullname'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'address' => htmlspecialchars($row['address'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'contract_id' => htmlspecialchars($row['contract_id'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'contact_number' => htmlspecialchars($row['contact'] ?? '', ENT_QUOTES, 'UTF-8'),
                ];
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No records found.';
        }
    } else {
        // Query execution failed
        $response['status'] = 'error';
        $response['message'] = 'Database query failed: ' . mysqli_error($conn);
    }
} catch (Exception $e) {
    // Catch unexpected errors
    $response['status'] = 'error';
    $response['message'] = 'An unexpected error occurred: ' . $e->getMessage();
}

// Return the response as JSON
echo json_encode($response, JSON_PRETTY_PRINT);
?>
