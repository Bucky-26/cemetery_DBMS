<?php include 'model/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidenav.php'; ?>
<?php include 'includes/navbar.php'; ?>

<?php
// At the top of the file, check if we're in selection mode
$selectMode = isset($_GET['select_mode']) && $_GET['select_mode'] === 'true';

// At the top after your existing includes
$isSelectionMode = isset($_GET['mode']) && $_GET['mode'] === 'select';

$plotId = isset($_GET['plot_id']) ? $_GET['plot_id'] : null;
?>

<!-- Add this CSS if you're in selection mode -->
<?php if ($selectMode): ?>
<style>
.plot-available {
    cursor: pointer;
}
.plot-available:hover {
    opacity: 0.8;
    transform: scale(1.05);
    transition: all 0.2s;
}

<!-- Add this CSS for selection mode -->
.plot[data-status='Available'] {
    cursor: pointer;
    transition: all 0.2s;
}
.plot[data-status='Available']:hover {
    transform: scale(1.05);
    opacity: 0.8;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
}

<!-- Add this CSS for clickable plots -->
<style>
.leaflet-interactive {
    cursor: pointer !important;
}

.plot-popup {
    text-align: center;
}

.plot-popup button {
    margin-top: 10px;
    padding: 5px 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.plot-popup button:hover {
    background: #0056b3;
}
</style>
<?php endif; ?>

<style>
.status-badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin: 0 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.status-icon {
    font-size: 1.1rem;
}
</style>

<div class="container-fluid p-4 bg-light">
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 1px; padding-top: 1px;">
        <div>
            <h4 class="mb-0">Cemetery Plot Management</h4>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-3 gap-3">
        <div class="position-relative" style="min-width: 300px;">
            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" 
                   class="form-control bg-gray-100 border-0 ps-5" 
                   id="searchPlot" 
                   placeholder="Search plot..."
                   style="padding: 0.75rem 1rem; border-radius: 0.75rem;">
            <div class="position-absolute w-100 mt-1 shadow-lg bg-white rounded-3" 
                 id="searchResults" 
                 style="max-height: 230px; overflow-y: auto; z-index: 1000; display: none;">
            </div>
        </div>
        <button class="btn btn-icon btn-3 btn-dark rounded-3" id="addPlot" 
                style="height: 45px; display: flex; align-items: center; margin-top: 11px;">
            <i class="fas fa-plus-circle me-2"></i>Add Plot
        </button>
    </div>
    <div class="card shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-white p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3 align-items-center">
                    <div class="status-badges-container d-flex flex-wrap gap-2">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill" id="clearHighlight" style="display: none;">
                        <i class="fas fa-eye-slash me-1"></i>Clear Highlight
                    </button>
                </div>
                <?php include 'includes/floatnav.php'; ?>

            </div>
        </div>
        <div class="h-90">
            <div class="card shadow h-90">
                <div id="map" style="min-height: 90vh; border-radius: 0.375rem;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const API_ENDPOINTS = {
        GET_PLOTS: 'process/get_plots.php',
        SAVE_PLOT: 'process/save_plot.php',
        DELETE_PLOT: 'process/delete_plot.php'
    };

    const INITIAL_STATE = {
        plotId: <?php echo json_encode($plotId); ?>
    };
</script>
<script src="assets/scripy.js"></script>
<?php include 'modal/plot.php'; ?>
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/script.php'; ?>    

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const plotId = <?php echo json_encode($plotId); ?>;
        if (plotId) {
            window.initialPlotId = plotId;
        }
    });
    </script>

