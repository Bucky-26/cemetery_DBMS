<?php 
include 'model/session.php'; 
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'add';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Plot</title>
    <?php include 'includes/header.php'; ?>
    <style>
        #map { height: 80vh; width: 100%; }
        .plot-popup { text-align: center; }
        .plot-popup button { margin-top: 10px; }
    </style>
</head>
<body>
    <input type="hidden" id="selectorMode" value="<?php echo htmlspecialchars($mode); ?>">
    <div class="container-fluid p-4">
        <div class="row mb-3">
            <div class="col">
                <h4>Select Available Plot</h4>
                <div class="input-group">
                    <input type="text" class="form-control" id="searchPlot" 
                           placeholder="Search plot...">
                </div>
            </div>
        </div>
        <div class="card">
            <div id="map"></div>
        </div>
    </div>

    <?php include 'includes/script.php'; ?>
    <script src="assets/plot-selector.js"></script>
</body>
</html> 