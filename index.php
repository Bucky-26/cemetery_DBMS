<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Left Section -->
        <div class="col-md-4 bg-gradient text-white min-vh-100 d-flex align-items-center custom-bg">
            <div class="p-5">
                <div class="mb-5">
                    <div class="logo-container mb-4">
                        <i class="fas fa-cross fa-3x"></i>
                    </div>
                    <h1 class="display-4 fw-bold mb-4">Puerto Princesa Memorial Park</h1>
                    <p class="lead mb-4">Cemetery Database Management System</p>
                    <div class="divider mb-4"></div>
                    <p class="mb-4 opacity-75">Providing dignified and peaceful resting places for your loved ones.</p>
                    <a href="#learn-more" class="btn btn-glass btn-lg px-4">
                        Learn More
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                
                <!-- Quick Stats -->
                <div class="row g-4 mt-5">
                    <div class="col-6">
                        <div class="stat-card">
                            <h3 class="h2 mb-1">2,500+</h3>
                            <p class="small opacity-75 mb-0">Plots Available</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <h3 class="h2 mb-1">24/7</h3>
                            <p class="small opacity-75 mb-0">Support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-md-8 bg-light">
            <div class="p-5">
                <h2 class="section-title mb-4">Our Services</h2>
                <!-- Featured Services -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="feature-card">
                            <div class="icon-box">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4>Plot Locator</h4>
                            <p>Easily locate and navigate through available plots using our interactive map system.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <div class="icon-box">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <h4>Online Booking</h4>
                            <p>Reserve and manage plot bookings through our streamlined online system.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <div class="icon-box">
                                <i class="fas fa-history"></i>
                            </div>
                            <h4>Records Management</h4>
                            <p>Access and maintain detailed records of plots and memorial services.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <div class="icon-box">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h4>Support Services</h4>
                            <p>24/7 assistance for all your memorial and maintenance needs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-bg {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
}

.logo-container {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.divider {
    width: 50px;
    height: 3px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.btn-glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    transition: all 0.3s ease;
}

.btn-glass:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    transform: translateY(-2px);
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.section-title {
    font-size: 2rem;
    font-weight: 600;
    color: #1e3c72;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
}

.section-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: #1e3c72;
    border-radius: 2px;
}

.feature-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
}

.icon-box {
    width: 60px;
    height: 60px;
    background: rgba(30, 60, 114, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.icon-box i {
    font-size: 24px;
    color: #1e3c72;
}

.feature-card h4 {
    color: #1e3c72;
    margin-bottom: 1rem;
    font-weight: 600;
}

.feature-card p {
    color: #6c757d;
    margin-bottom: 0;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .custom-bg {
        min-height: 50vh;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>