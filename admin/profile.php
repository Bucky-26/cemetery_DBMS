<?php
session_start();
include 'includes/header.php';
include 'includes/sidenav.php';
include 'includes/navbar.php';
include 'model/conn.php';

// Initialize profile array with default values
$profile = [
    'photo_url' => 'default.png',
    'first_name' => '',
    'middle_initial' => '',
    'last_name' => '',
    'job_title' => '',
    'email' => '',
    'username' => '',
    'account_type' => ''
];

try {
    // Update query to use the logged-in user's ID (assuming you have a session)
    $sql = "SELECT * FROM accounts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Get the logged-in user's ID from session (adjust this according to your session structure)
    $user_id = $_SESSION['user_id'] ?? 1; // fallback to 1 if not set
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        // Merge the database results with default values
        $profile = array_merge($profile, $result->fetch_assoc());
    }

} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    echo "<div class='alert alert-danger'>Error loading profile data</div>";
}
?>

<!-- Custom Styles -->
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2ecc71;
        --background-light: #f4f6f7;
        --text-dark: #2c3e50;
    }

    body {
        background-color: var(--background-light);
        font-family: 'Inter', sans-serif;
    }

    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.07);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
    }

    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .profile-image:hover {
        transform: scale(1.05);
    }

    .profile-details {
        background-color: white;
        padding: 2rem;
        position: relative;
        z-index: 1;
    }

    .info-block {
        background-color: var(--background-light);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: background-color 0.3s ease;
    }

    .info-block:hover {
        background-color: #e9ecef;
    }

    .info-block label {
        color: var(--text-dark);
        opacity: 0.7;
        margin-bottom: 0.5rem;
    }

    .info-block span {
        color: var(--text-dark);
        font-weight: 600;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
    }
</style>

<!-- Main Content -->
<div class="container-fluid min-vh-85">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card profile-card shadow-sm">
                <!-- Profile Header -->
                <div class="profile-header text-center">
                    <img src='<?php echo ($profile['photo_url'] ? '/'.$profile['photo_url'] : '/admin/images/employee/defualt.png'); ?>' 
                         class='profile-image mb-3' 
                         alt='Profile Photo'>
                    <h2 class="text-white mb-2"><?php echo htmlspecialchars($profile['first_name'] . ' ' . $profile['middle_initial'] . ' ' . $profile['last_name']); ?></h2>
                    <p class="text-white-50 mb-3"><?php echo htmlspecialchars($profile['job_title']); ?></p>
                    <span class="badge bg-white text-primary"><?php echo htmlspecialchars($profile['account_type']); ?></span>
                </div>

                <!-- Profile Details -->
                <div class="profile-details">
                    <div class="row g-4">
                        <div class="col-md-4 fade-in" style="animation-delay: 0.2s;">
                            <div class="info-block">
                                <label class="d-block small mb-1">First Name</label>
                                <span><?php echo htmlspecialchars($profile['first_name']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 fade-in" style="animation-delay: 0.4s;">
                            <div class="info-block">
                                <label class="d-block small mb-1">Last Name</label>
                                <span><?php echo htmlspecialchars($profile['last_name']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 fade-in" style="animation-delay: 0.6s;">
                            <div class="info-block">
                                <label class="d-block small mb-1">Username</label>
                                <span><?php echo htmlspecialchars($profile['username']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 fade-in" style="animation-delay: 0.8s;">
                            <div class="info-block">
                                <label class="d-block small mb-1">Email Address</label>
                                <span><?php echo htmlspecialchars($profile['email']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 fade-in" style="animation-delay: 1.0s;">
                            <div class="info-block">
                                <label class="d-block small mb-1">Job Title</label>
                                <span><?php echo htmlspecialchars($profile['job_title']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 fade-in" style="animation-delay: 1.2s;">
                            <div class="info-block">
                                <label class="d-block small mb-1">Account Type</label>
                                <span><?php echo htmlspecialchars($profile['account_type']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 
<?php include 'includes/script.php'; ?>