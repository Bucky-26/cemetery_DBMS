<?php
$conn = new mysqli("localhost", "root", "", "mapdbms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Draggable Image Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Add these styles */
.search-container {
    background: white;
    padding: 10px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

#searchPlot {
    width: 250px;
}

#searchResults {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-top: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.list-group-item-action {
    cursor: pointer;
}

.list-group-item-action:hover {
    background-color: #f8f9fa;
}

/* Style for the edit button in popup */
.edit-plot-btn {
    margin-top: 8px;
}
        .plot-available {
    fill: rgba(0, 255, 0, 0.2) !important;
    stroke: #00ff00 !important;
}

.plot-occupied {
    fill: rgba(255, 0, 0, 0.2) !important;
    stroke: #ff0000 !important;
}

.plot-maintenance {
    fill: rgba(255, 165, 0, 0.2) !important;
    stroke: #ffa500 !important;
}

#drawRect.active {
    background-color: #007bff;
            color: white;
        }
        .plot-available { background: rgba(0, 0, 255, 0.4); }
        .plot-occupied { background: rgba(255, 0, 0, 0.4); }
        .plot-maintenance { background: rgba(255, 255, 0, 0.4); }

.drawing {
    cursor: crosshair !important;
}

#drawRect.active {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Style for the cancel button */
#cancelPlot {
    margin-right: auto;
}

.overlap-warning {
    fill: rgba(255, 0, 0, 0.3)
}

.reset-view-btn {
    font-size: 18px;
    line-height: 24px;
    width: 30px;
    height: 30px;
    text-align: center;
    text-decoration: none;
    color: #666;
    background-color: white;
    display: block;
}

.reset-view-btn:hover {
    background-color: #f4f4f4;
    color: #333;
    text-decoration: none;
}
    </style>
</head>
<body>
    <div class="container-fluid">
        <button id="addPlot" class="btn btn-primary mt-2 mb-2">Add Plot</button>
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="plotModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plot Details</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="plotForm">
                        <input type="hidden" id="plotId">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" class="form-control" required>
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="savePlot">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this search input to your HTML -->
    <div class="search-container" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
        <input type="text" id="searchPlot" class="form-control" placeholder="Search plot by title...">
        <div id="searchResults" class="list-group" style="display:none; position: absolute; width: 100%; max-height: 200px; overflow-y: auto;"></div>
    </div>

    <script>
        var map = L.map('map', {
            crs: L.CRS.Simple,
            minZoom: -1
        });

        var bounds = [[0, 0], [1000, 1000]]; // Adjust these values based on your image dimensions
        var image = L.imageOverlay('map1.png', bounds).addTo(map);
        map.fitBounds(bounds);

        var drawingRect = false;
        var rectangle = null;
        var plots = [];
        var plotLayers = [];

        // Load existing plots
        loadPlots();

        function loadPlots() {
            if (plotLayers) {
                plotLayers.forEach(layer => {
                    if (map.hasLayer(layer)) {
                        map.removeLayer(layer);
                    }
                });
            }
            plotLayers = [];
            
            
        }

        function getColorForStatus(status) {
            switch(status) {
                case 'available':
                    return '#00ff00'; // Green
                case 'occupied':
                    return '#ff0000'; // Red
                case 'maintenance':
                    return '#ffa500'; // Orange
                default:
                    return '#808080'; // Gray
            }
        }

        $('#addPlot').click(function() {
            drawingRect = true;
            map.dragging.disable();
            
            map.once('mousedown', function(e) {
                const startPoint = e.latlng;
                
                map.once('mouseup', function(e) {
                    const endPoint = e.latlng;
                    rectangle = L.rectangle([startPoint, endPoint]).addTo(map);
                    drawingRect = false;
                    map.dragging.enable();
                    
                    $('#plotModal').modal('show');
                });
            });
        });

        var isSaving = false;
        var saveXhr = null; // Track the save AJAX request

        $('#savePlot').click(function(e) {
            e.preventDefault();
            
            // Prevent double submission
            if (isSaving) {
                console.log('Save in progress, preventing duplicate request');
                return;
            }
            
            // Abort any existing save request
            if (saveXhr && saveXhr.state() === 'pending') {
                saveXhr.abort();
            }
            
            isSaving = true;
            $('#savePlot').prop('disabled', true);
            
            let coordinates;
            const plotId = $('#plotId').val();
            let bounds;
            
            if (plotId) {
                const plotLayer = plotLayers.find(layer => layer.options.id == plotId);
                bounds = plotLayer.getBounds();
            } else {
                if (!rectangle) {
                    alert('Please draw a rectangle first');
                    isSaving = false;
                    $('#savePlot').prop('disabled', false);
                    return;
                }
                bounds = rectangle.getBounds();
            }

            const plotData = {
                id: plotId || null,
                title: $('#title').val().trim(),
                description: $('#description').val().trim(),
                status: $('#status').val(),
                coordinates: JSON.stringify({
                    _southWest: {
                        lat: bounds.getSouth(),
                        lng: bounds.getWest()
                    },
                    _northEast: {
                        lat: bounds.getNorth(),
                        lng: bounds.getEast()
                    }
                })
            };

            if (!plotData.title) {
                alert('Please enter a title');
                isSaving = false;
                $('#savePlot').prop('disabled', false);
                return;
            }

            // Store the AJAX request
        
        });

        function showPlotDetails(plot) {
            $('#plotId').val(plot.id);
            $('#title').val(plot.title);
            $('#description').val(plot.description);
            $('#status').val(plot.status);
            $('#plotModal').modal('show');
        }

        // Load plots when the page loads
        $(document).ready(function() {
            loadPlots();
        });

        var currentMarker = null;
        var searchTimeout = null;

        // Add this search functionality
        $('#searchPlot').on('input', function() {
            clearTimeout(searchTimeout);
            const searchText = $(this).val().trim();
            
            if (searchText.length < 1) {
                $('#searchResults').hide();
                return;
            }

            searchTimeout = setTimeout(function() {
                const results = plots.filter(plot => 
                    plot.title.toLowerCase().includes(searchText.toLowerCase())
                );

                displaySearchResults(results);
            }, 300);
        });

        function displaySearchResults(results) {
            const $results = $('#searchResults');
            $results.empty();

            if (results.length === 0) {
                $results.append('<div class="list-group-item">No results found</div>');
            } else {
                results.forEach(plot => {
                    $results.append(`
                        <a href="#" class="list-group-item list-group-item-action" 
                           data-plot-id="${plot.id}">
                            ${plot.title}
                        </a>
                    `);
                });
            }

            $results.show();
        }

        $(document).on('click', '#searchResults .list-group-item-action', function(e) {
            e.preventDefault();
            const plotId = $(this).data('plot-id');
            const plot = plots.find(p => p.id == plotId);
            
            if (plot) {
                zoomToPlot(plot);
            }
            
            $('#searchResults').hide();
            $('#searchPlot').val(plot.title);
        });

        // Close search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-container').length) {
                $('#searchResults').hide();
            }
        });

        function zoomToPlot(plot) {
            const coords = JSON.parse(plot.coordinates);
            const bounds = [
                [coords._southWest.lat, coords._southWest.lng],
                [coords._northEast.lat, coords._northEast.lng]
            ];
            
            if (currentMarker) {
                map.removeLayer(currentMarker);
            }
            
            const center = [
                (coords._southWest.lat + coords._northEast.lat) / 2,
                (coords._southWest.lng + coords._northEast.lng) / 2
            ];
            
            currentMarker = L.marker(center).addTo(map);
            currentMarker.bindPopup(`
                <strong>${plot.title}</strong><br>
                Status: ${plot.status}<br>
                <div class="btn-group mt-2" role="group">
                    <button class="btn btn-sm btn-primary" onclick="editPlot(${plot.id})">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deletePlot(${plot.id})">
                        Delete
                    </button>
                </div>
            `).openPopup();
            
            map.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 2
            });
        }

        function editPlot(plotId) {
            const plot = plots.find(p => p.id == plotId);
            if (!plot) return;
            
            // Fill the modal with plot data
            $('#plotId').val(plot.id);
            $('#title').val(plot.title);
            $('#description').val(plot.description);
            $('#status').val(plot.status);
            
            // Get the plot's rectangle from plotLayers
            const plotLayer = plotLayers.find(layer => layer.options.id == plotId);
            if (plotLayer) {
                rectangle = plotLayer;
            }
            
            // Show the modal
            $('#plotModal').modal('show');
        }

        // Add delete function
        function deletePlot(plotId) {
            if (confirm('Are you sure you want to delete this plot?')) {
                $.ajax({
                    url: 'delete_plot.php',
                    method: 'POST',
                    data: { id: plotId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            if (currentMarker) {
                                map.removeLayer(currentMarker);
                            }
                            loadPlots();
                        } else {
                            alert('Error deleting plot: ' + (response.error || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting plot:', error);
                        alert('Error deleting plot: ' + error);
                    }
                });
            }
        }

        // Modify your loadPlots function to store the plots data globally
        function loadPlots() {
            if (plotLayers) {
                plotLayers.forEach(layer => {
                    if (map.hasLayer(layer)) {
                        map.removeLayer(layer);
                    }
                });
            }
            plotLayers = [];
            
            $.ajax({
                url: 'get_plots.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Loaded plots:', data);
                    plots = data; // Store globally
                    plots.forEach(plot => {
                        try {
                            const coords = JSON.parse(plot.coordinates);
                            const bounds = [
                                [coords._southWest.lat, coords._southWest.lng],
                                [coords._northEast.lat, coords._northEast.lng]
                            ];
                            
                            const rect = L.rectangle(bounds, {
                                className: `plot-${plot.status}`,
                                id: plot.id,
                                color: getColorForStatus(plot.status),
                                weight: 2,
                                fillOpacity: 0.3
                            }).addTo(map);

                            rect.bindTooltip(plot.title);
                            
                            rect.on('click', function() {
                                zoomToPlot(plot); // Change this line to use zoomToPlot
                            });
                            
                            plotLayers.push(rect);
                        } catch (e) {
                            console.error('Error creating plot:', e, plot);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading plots:', error);
                }
            });
        }

        // Modify the drawing functionality
        var drawingRect = false;
        var rectangle = null;
        var startPoint = null;

        // Update the draw button click handler
        $('#drawRect').click(function() {
            drawingRect = !drawingRect; // Toggle drawing mode
            $(this).toggleClass('active');
            
            if (drawingRect) {
                map.getContainer().style.cursor = 'crosshair';
            } else {
                map.getContainer().style.cursor = '';
                if (rectangle && !rectangle.options.id) {
                    map.removeLayer(rectangle);
                    rectangle = null;
                }
            }
        });

        // Clear existing map event handlers and add new ones
        map.off('mousedown');
        map.off('mousemove');
        map.off('mouseup');

        map.on('mousedown', function(e) {
            if (!drawingRect) return;
            
            startPoint = e.latlng;
            
            // Clear existing rectangle if it's a temporary one
            if (rectangle && !rectangle.options.id) {
                map.removeLayer(rectangle);
            }
            
            // Create new rectangle
            rectangle = L.rectangle([startPoint, startPoint], {
                color: '#3388ff',
                weight: 2,
                fillOpacity: 0.3
            }).addTo(map);
            
            // Add mousemove handler
            map.on('mousemove', updateRectangle);
        });

        function updateRectangle(e) {
            if (!startPoint || !rectangle) return;
            
            const newBounds = L.latLngBounds(startPoint, e.latlng);
            rectangle.setBounds(newBounds);
            
            // Check and highlight overlap
            highlightOverlap(newBounds);
        }

        map.on('mouseup', function(e) {
            if (!drawingRect || !startPoint || !rectangle) return;
            
            map.off('mousemove', updateRectangle);
            const bounds = rectangle.getBounds();
            
            // Check if rectangle is too small
            if (bounds.getNorth() === bounds.getSouth() || bounds.getEast() === bounds.getWest()) {
                map.removeLayer(rectangle);
                rectangle = null;
                startPoint = null;
                return;
            }
            
            // Check for overlap
            if (checkOverlap(bounds)) {
                alert('Plots cannot overlap! Please draw in a different location.');
                map.removeLayer(rectangle);
                rectangle = null;
                startPoint = null;
                return;
            }
            
            // Reset rectangle style
            rectangle.setStyle({
                color: '#3388ff',
                fillColor: '#3388ff',
                fillOpacity: 0.3
            });
            
            startPoint = null;
            $('#plotForm')[0].reset();
            $('#plotId').val('');
            $('#plotModal').modal('show');
        });

        // Update save plot handler
        $('#savePlot').click(function(e) {
            e.preventDefault();
            
            let coordinates;
            const plotId = $('#plotId').val();
            let bounds;
            
            if (plotId) {
                // Editing existing plot
                const plotLayer = plotLayers.find(layer => layer.options.id == plotId);
                bounds = plotLayer.getBounds();
            } else {
                // New plot
                if (!rectangle) {
                    alert('Please draw a rectangle first');
                    return;
                }
                bounds = rectangle.getBounds();
            }

            // Format coordinates
            coordinates = JSON.stringify({
                _southWest: {
                    lat: bounds.getSouth(),
                    lng: bounds.getWest()
                },
                _northEast: {
                    lat: bounds.getNorth(),
                    lng: bounds.getEast()
                }
            });

            const plotData = {
                id: plotId,
                title: $('#title').val(),
                description: $('#description').val(),
                status: $('#status').val(),
                coordinates: coordinates
            };

            // Validate form
            if (!plotData.title) {
                alert('Please enter a title');
                return;
            }

            $.ajax({
                url: 'save_plot.php',
                method: 'POST',
                data: plotData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#plotModal').modal('hide');
                        if (rectangle) {
                            map.removeLayer(rectangle);
                            rectangle = null;
                        }
                        if (currentMarker) {
                            map.removeLayer(currentMarker);
                        }
                        loadPlots();
                        // Keep drawing mode active if the button is still active
                        if (!$('#drawRect').hasClass('active')) {
                            drawingRect = false;
                            map.getContainer().style.cursor = '';
                        }
                    } else {
                        alert('Error saving plot: ' + (response.error || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error saving plot:', error);
                    alert('Error saving plot: ' + error);
                }
            });
        });

        // Update modal events
        $('#plotModal').on('hidden.bs.modal', function () {
            $('#plotForm')[0].reset();
            $('#plotId').val('');
            
            // Only remove rectangle if it's a temporary one
            if (rectangle && !rectangle.options.id) {
                map.removeLayer(rectangle);
                rectangle = null;
            }
        });

        // Add a cancel button to the modal
        $('#plotModal .modal-footer').prepend(`
            <button type="button" class="btn btn-secondary" id="cancelPlot">Cancel</button>
        `);

        $('#cancelPlot').click(function() {
            $('#plotModal').modal('hide');
            if (rectangle && !rectangle.options.id) {
                map.removeLayer(rectangle);
                rectangle = null;
            }
            startPoint = null;
            drawingRect = false;
            $('#drawRect').removeClass('active');
        });

        // Add this function to check for overlapping rectangles
        function checkOverlap(newBounds) {
            for (let layer of plotLayers) {
                const existingBounds = layer.getBounds();
                if (newBounds.overlaps(existingBounds)) {
                    return true;
                }
            }
            return false;
        }

        // Add this function to highlight overlapping area
        function highlightOverlap(newBounds) {
            if (rectangle) {
                if (checkOverlap(newBounds)) {
                    rectangle.setStyle({ color: '#ff0000', fillColor: '#ff0000' });
                    return true;
                } else {
                    rectangle.setStyle({ color: '#3388ff', fillColor: '#3388ff' });
                    return false;
                }
            }
            return false;
        }

        // Add these functions to handle map state
        function saveMapState() {
            const center = map.getCenter();
            const zoom = map.getZoom();
            const bounds = map.getBounds();
            
            const mapState = {
                center: {
                    lat: center.lat,
                    lng: center.lng
                },
                zoom: zoom,
                bounds: {
                    southWest: {
                        lat: bounds.getSouthWest().lat,
                        lng: bounds.getSouthWest().lng
                    },
                    northEast: {
                        lat: bounds.getNorthEast().lat,
                        lng: bounds.getNorthEast().lng
                    }
                }
            };
            
            localStorage.setItem('mapState', JSON.stringify(mapState));
        }

        function loadMapState() {
            const savedState = localStorage.getItem('mapState');
            if (savedState) {
                const mapState = JSON.parse(savedState);
                map.setView([mapState.center.lat, mapState.center.lng], mapState.zoom);
                const bounds = L.latLngBounds(
                    L.latLng(mapState.bounds.southWest.lat, mapState.bounds.southWest.lng),
                    L.latLng(mapState.bounds.northEast.lat, mapState.bounds.northEast.lng)
                );
                map.fitBounds(bounds);
            }
        }

        // Add this function to handle adding new plots to the map
        function addPlotToMap(plot) {
            try {
                const coords = JSON.parse(plot.coordinates);
                const bounds = [
                    [coords._southWest.lat, coords._southWest.lng],
                    [coords._northEast.lat, coords._northEast.lng]
                ];
                
                const rect = L.rectangle(bounds, {
                    className: `plot-${plot.status}`,
                    id: plot.id,
                    color: getColorForStatus(plot.status),
                    weight: 2,
                    fillOpacity: 0.3
                }).addTo(map);

                rect.bindTooltip(plot.title);
                
                rect.on('click', function() {
                    showPlotDetails(plot);
                });
                
                plotLayers.push(rect);
            } catch (e) {
                console.error('Error adding plot to map:', e, plot);
            }
        }
    </script>
</body>
</html> 