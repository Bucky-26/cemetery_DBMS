<?php
require '../model/conn.php';

$period = $_GET['period'] ?? 'daily';
$response = ['labels' => [], 'amounts' => []];

try {
    switch ($period) {
        case 'daily':
            // Get current month's data (1-31)
            $sql = "WITH RECURSIVE DateSequence AS (
                SELECT 1 as day
                UNION ALL
                SELECT day + 1
                FROM DateSequence
                WHERE day < DAY(LAST_DAY(CURRENT_DATE))
            )
            SELECT 
                d.day,
                COALESCE(SUM(p.amount), 0) as total
            FROM DateSequence d
            LEFT JOIN payment_summary p ON 
                DAY(p.payment_date) = d.day AND 
                MONTH(p.payment_date) = MONTH(CURRENT_DATE) AND 
                YEAR(p.payment_date) = YEAR(CURRENT_DATE)
            GROUP BY d.day
            ORDER BY d.day";
            break;
            
        case 'weekly':
            // Get current month's weeks (1-4)
            $sql = "WITH WeekNumbers AS (
                SELECT 1 as week_num
                UNION SELECT 2
                UNION SELECT 3
                UNION SELECT 4
            )
            SELECT 
                w.week_num,
                COALESCE(SUM(p.amount), 0) as total
            FROM WeekNumbers w
            LEFT JOIN payment_summary p ON 
                CEIL(DAY(p.payment_date)/7) = w.week_num AND
                MONTH(p.payment_date) = MONTH(CURRENT_DATE) AND
                YEAR(p.payment_date) = YEAR(CURRENT_DATE)
            GROUP BY w.week_num
            ORDER BY w.week_num";
            break;
            
        case 'monthly':
            // Get full year data (Jan-Dec)
            $sql = "WITH Months AS (
                SELECT 1 as month_num, 'January' as month_name UNION
                SELECT 2, 'February' UNION
                SELECT 3, 'March' UNION
                SELECT 4, 'April' UNION
                SELECT 5, 'May' UNION
                SELECT 6, 'June' UNION
                SELECT 7, 'July' UNION
                SELECT 8, 'August' UNION
                SELECT 9, 'September' UNION
                SELECT 10, 'October' UNION
                SELECT 11, 'November' UNION
                SELECT 12, 'December'
            )
            SELECT 
                m.month_num,
                m.month_name,
                COALESCE(SUM(p.amount), 0) as total
            FROM Months m
            LEFT JOIN payment_summary p ON 
                MONTH(p.payment_date) = m.month_num AND 
                YEAR(p.payment_date) = YEAR(CURRENT_DATE)
            GROUP BY m.month_num, m.month_name
            ORDER BY m.month_num";
            break;
    }

    $result = $conn->query($sql);
    $date = new DateTime();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            switch ($period) {
                case 'daily':
                    // Add leading zero for single digit days
                    $dayNum = str_pad($row['day'], 2, '0', STR_PAD_LEFT);
                    $response['labels'][] = "{$date->format('M')} {$dayNum}";
                    $response['amounts'][] = floatval($row['total']);
                    break;
                    
                case 'weekly':
                    $response['labels'][] = "Week {$row['week_num']}";
                    $response['amounts'][] = floatval($row['total']);
                    break;
                    
                case 'monthly':
                    $response['labels'][] = $row['month_name'];
                    $response['amounts'][] = floatval($row['total']);
                    break;
            }
        }
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>