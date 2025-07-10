<?php
include 'conn.php';

$sql = "SELECT * FROM employee";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_all(MYSQLI_ASSOC);

foreach ($employee as $row) {
    // Determine badge color based on status
    $statusClass = match($row['employment_status']) {
        'Active' => 'bg-success',
        'On Leave' => 'bg-warning',
        'Resigned' => 'bg-danger',
        default => 'bg-secondary'
    };

    echo "<tr data-id='{$row['id']}'>
            <td>
                <div class='d-flex align-items-center'>
                    <img src='" . ($row['photo_url'] ? '/'.$row['photo_url'] : "/admin/images/employee/defualt.png") . "' 
                         class='rounded-circle' 
                         style='width: 35px; height: 35px;'>    
                    <div class='ms-3'>
                        <h6 class='mb-0 text-sm'>{$row['first_name']} {$row['middle_initial']} {$row['last_name']}</h6>
                        <span class='text-muted text-xs'>
                            {$row['email']}
                        </span>
                    </div>
                </div>
            </td>
            <td>
                <div class='d-flex flex-column'>
                    <h6 class='mb-0 text-sm'>{$row['job_title']}</h6>
                    <span class='text-muted text-xs'>
                        <i class='fas fa-building me-1'></i>
                        PPMP
                    </span>
                </div>
            </td>
            <td class='text-center'>
                <span class='badge {$statusClass} rounded-pill px-3'>{$row['employment_status']}</span>
            </td>
            <td class='text-end'>
                <a href='javascript:void(0)' class='text-secondary font-weight-bold text-xs me-3' data-bs-toggle='modal' data-bs-target='#edit_employee_modal' onclick='editEmployee({$row['id']})'>
                    <i class='fas fa-pencil-alt me-1'></i>Edit
                </a>
                <a href='javascript:void(0)' class='text-danger font-weight-bold text-xs' onclick='deleteEmployee({$row['id']})'>
                    <i class='far fa-trash-alt me-1'></i>Delete
                </a>
            </td>
        </tr>";
}
?>
