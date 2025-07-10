<?php include 'model/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Plot</title>
    <?php include 'includes/header.php'; ?>
    <style>
        #map { 
            height: 80vh; 
            width: 100%; 
        }
        .plot-popup {
            text-align: center;
        }
        .plot-popup button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <input type="hidden" id="selectorMode" value="edit">
    
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
    <script src="assets/plot-selector_edit.js"></script>

</body>
</html> 