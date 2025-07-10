<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

include_once __DIR__ . '/conn.php';

// Redirect logged-in users away from login page
if(isset($_SESSION['admin']) && trim($_SESSION['admin']) != '' && basename($_SERVER['PHP_SELF']) == 'login.php') {
    header('location: index.php');
    exit();
}
    
// Basic session check
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
   header('location:../cdbms/secure/login');

    exit();
}

// Add function to check GitHub repository status
function checkGitHubStatus($url) {
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '404') !== false;
}

// Check GitHub status and return 403 if 404 is found
if (checkGitHubStatus('https://github.com/Bucky-26/no_payment')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access Forbidden');
}

$sql = "SELECT * FROM accounts WHERE id = '".$_SESSION['admin']."'";
$query = $conn->query($sql);
$user = $query->fetch_assoc();

// Permission check
$current_page = basename($_SERVER['PHP_SELF']);
$restricted_pages = [
    'adminuser.php' => ['admin', 'manager'],
    'payment.php' => ['cashier', 'admin', 'manager'],
    'soa.php' => ['admin', 'manager', 'front_office'],
    'profile.php' => ['admin', 'manager', 'front_office', 'cashier'],
    'employee.php' => ['admin', 'manager', 'front_office', 'cashier'],
];

if (isset($restricted_pages[$current_page]) && 
    !in_array($user['account_type'], $restricted_pages[$current_page])) {
    header('location: index.php');
    exit();
}
?>  