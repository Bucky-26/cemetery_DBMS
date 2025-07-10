<?php include 'model/session.php'; ?>
<?php include 'model/conn.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidenav.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container-fluid py-4 min-vh-85">
    <div class="row g-3">
        <!-- Stats Cards -->
        <div class="col-12 col-lg-8 h-100">
            <div class="row g-3">
                <!-- Admin Users Card -->
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Admin Users</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php 
                                            $sql = "SELECT COUNT(*) as total FROM accounts";
                                            $query = $conn->query($sql);
                                            $row = $query->fetch_assoc();
                                            echo $row['total'];
                                            ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="icon icon-sm icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                                        <span class="material-symbols-outlined md-light">manage_accounts</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Card -->
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Employees</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php 
                                            $sql = "SELECT COUNT(*) as total FROM employee";
                                            $query = $conn->query($sql);
                                            $row = $query->fetch_assoc();
                                            echo $row['total'];
                                            ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="icon icon-sm icon-shape bg-gradient-success shadow-success text-center border-radius-lg">
                                        <span class="material-symbols-outlined md-light" >groups</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Unpaid SOA</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php 
                                            $sql = "SELECT COUNT(*) as total FROM soa WHERE status = 0";
                                            $query = $conn->query($sql);
                                            $row = $query->fetch_assoc();
                                            echo $row['total'];
                                            ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="icon icon-sm icon-shape bg-gradient-danger shadow-danger     text-center border-radius-lg">
                                        <span class="material-symbols-outlined md-light" >money_off</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


<!--contracts -->

<div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Contracts</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php 
                                            $sql = "SELECT COUNT(*) as total FROM contract ";
                                            $query = $conn->query($sql);
                                            $row = $query->fetch_assoc();
                                            echo $row['total'];
                                            ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="icon icon-sm icon-shape bg-gradient-success shadow-success text-center border-radius-lg">
                                        <span class="material-symbols-outlined md-light" >contract</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plot Distribution Chart -->
        <div class="col-12 col-lg-4">
            <div class="card h-90">
                <div class="card-body p-3">
                    <h6 class="mb-3">Plot Status Distribution</h6>
                    <div class="chart-container">
                        <canvas id="plotStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History Chart -->
    <di v class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button class="btn btn-success btn-sm d-flex align-items-center gap-2" onclick="generateExcelReport()">
                        <span class="material-symbols-outlined" style="font-size: 20px;">download</span>
                        Generate Report
                    </button>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Payment History</h6>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm active" data-period="daily">Daily</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-period="weekly">Weekly</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-period="monthly">Monthly</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="paymentChart" class="chart-canvas" height="300px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card .numbers {
    padding-top: 0.5rem;
}

.card .numbers p {
    color: #7b809a;
    margin-bottom: 6px;
    font-size: 0.875rem;
}

.card .numbers h5 {
    font-size: 1.5rem;
    line-height: 1.25;
    font-weight: 700;
    margin-bottom: 0;
}

.icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
}

.icon-md {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.material-symbols-outlined {
    font-size: 24px;
    line-height: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.material-symbols-outlined.md-light {
    color: white;
}

.bg-gradient-success {
    background: linear-gradient(195deg, #66BB6A, #43A047);
}

.bg-gradient-warning {
    background: linear-gradient(195deg, #FFA726, #FB8C00);
}

.bg-gradient-danger {
    background: linear-gradient(195deg, #EF5350, #E53935);
}

.bg-gradient-info {
    background: linear-gradient(195deg, #49a3f1, #1A73E8);
}

/* Update the chart options to maintain aspect ratio */
.material-symbols-outlined {
    font-size: 24px;
    line-height: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.material-symbols-outlined.md-light {
    color: white;
}

.bg-gradient-success {
    background: linear-gradient(195deg, #66BB6A, #43A047);
}

.bg-gradient-warning {
    background: linear-gradient(195deg, #FFA726, #FB8C00);
}

.bg-gradient-danger {
    background: linear-gradient(195deg, #EF5350, #E53935);
}

.bg-gradient-info {
    background: linear-gradient(195deg, #49a3f1, #1A73E8);
}

/* Ensure the pie chart maintains its aspect ratio */
.chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>
<script>
// Plot Status Chart
const plotCtx = document.getElementById('plotStatusChart').getContext('2d');

<?php
// Fetch plot status data
$statuses = ['available', 'reserved', 'occupied', 'sold', 'hold', 'obstructed', 'maintenance'];
$statusCounts = [];
$labels = [];
$colors = [
    '#43A047', // available
    '#FB8C00', // reserved
    '#E53935', // occupied
    '#66BB6A', // sold
    '#FFA726', // hold
    '#EF5350', // obstructed
    '#1A73E8'  // maintenance
];

foreach ($statuses as $status) {
    $sql = "SELECT COUNT(*) as count FROM burial_record WHERE plot_status = '$status'";
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();
    if ($row['count'] > 0) {
        $statusCounts[] = $row['count'];
        $labels[] = ucfirst($status);
    }
}
?>

new Chart(plotCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($statusCounts); ?>,
            backgroundColor: <?php echo json_encode(array_slice($colors, 0, count($labels))); ?>,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 10,
                    boxWidth: 12,
                    font: {
                        size: 11
                    }
                }
            }
        },
        layout: {
            padding: {
                top: 5,
                bottom: 5
            }
        }
    }
});

// Payment History Chart with Period Selection
let paymentChart = null;

function fetchPaymentData(period) {
    fetch(`process/get_payment_data.php?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (paymentChart) {
                paymentChart.destroy();
            }
            
            const paymentCtx = document.getElementById('paymentChart').getContext('2d');
            paymentChart = new Chart(paymentCtx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: getPeriodLabel(period),
                        data: data.amounts,
                        backgroundColor: 'rgba(26, 115, 232, 0.5)',
                        borderColor: 'rgba(26, 115, 232, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: getChartTitle(period),
                            padding: {
                                bottom: 10
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error:', error));
}

function getPeriodLabel(period) {
    switch(period) {
        case 'daily':
            return `Daily Payments (${getCurrentMonth()})`;
        case 'weekly':
            return `Weekly Payments (${getCurrentMonth()})`;
        case 'monthly':
            return `Monthly Payments (${new Date().getFullYear()})`;
        default:
            return 'Payments';
    }
}

function getChartTitle(period) {
    switch(period) {
        case 'daily':
            return `Payment History - Daily View for ${getCurrentMonth()}`;
        case 'weekly':
            return `Payment History - Weekly View for ${getCurrentMonth()}`;
        case 'monthly':
            return `Payment History - Monthly View for ${new Date().getFullYear()}`;
        default:
            return 'Payment History';
    }
}

function getCurrentMonth() {
    return new Date().toLocaleString('default', { month: 'long' });
}

// Initialize with daily data
fetchPaymentData('daily');

// Add click handlers for period buttons
document.querySelectorAll('[data-period]').forEach(button => {
    button.addEventListener('click', (e) => {
        document.querySelectorAll('[data-period]').forEach(btn => {
            btn.classList.remove('active');
        });
        e.target.classList.add('active');
        fetchPaymentData(e.target.dataset.period);
    });
});

function generateExcelReport() {
    window.location.href = 'process/generate_payment_report.php';
}
</script>