$(document).ready(function() {
            // Status colors
            const STATUS_COLORS = {
                available: '#28a745',
                reserved: '#ffc107',
                occupied: '#0d6efd',
                sold: '#0dcaf0',
                hold: '#74b9ff',
                obstructed: '#dc3545',
                maintenance: '#ffc107'
            };

            const DRAWING_COLOR = '#2196F3';

            // Initialize map
            var map = L.map('map', {
                crs: L.CRS.Simple,
                minZoom: 1.0
            });

            var bounds = [
                [0, 0],
                [1000, 1000]
            ];
            var image = L.imageOverlay('images/map1.png', bounds).addTo(map);
            map.fitBounds(bounds);

            // Add the legend control here
            const legendControl = L.control({ position: 'topright' });

            legendControl.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'legend-control');
                div.innerHTML = `
                    <div class="legend-container">
                        <div class="status-list">
                            <div class="status-item">
                                <span class="color-box available"></span>
                                <span class="status-text">Available</span>
                            </div>
                            <div class="status-item">
                                <span class="color-box reserved"></span>
                                <span class="status-text">Reserved</span>
                            </div>
                            <div class="status-item">
                                <span class="color-box occupied"></span>
                                <span class="status-text">Occupied</span>
                            </div>
                            <div class="status-item">
                                <span class="color-box sold"></span>
                                <span class="status-text">Sold</span>
                            </div>
                            <div class="status-item">
                                <span class="color-box hold"></span>
                                <span class="status-text">Hold</span>
                            </div>
                            <div class="status-item">
                                <span class="color-box obstructed"></span>
                                <span class="status-text">Obstructed</span>
                            </div>
                            <div class="status-item">
                                <span class="color-box maintenance"></span>
                                <span class="status-text">Maintenance</span>
                            </div>
                        </div>
                    </div>
                `;
                return div;
            };

            // Add the legend styles
            const legendStyles = `
            <style>
            .legend-container {
                background: white;
                padding: 8px 12px;
                border-radius: 4px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                margin: 10px;
                min-width: 120px;
            }

            .status-list {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .status-item {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 12px;
            }

            .color-box {
                width: 12px;
                height: 12px;
                border: 1px solid rgba(0,0,0,0.1);
            }

            .color-box.available {
                background-color: #28a745;
            }

            .color-box.reserved {
                background-color: #ffc107;
            }

            .color-box.occupied {
                background-color: #0d6efd;
            }

            .color-box.sold {
                background-color: #0dcaf0;
            }

            .color-box.hold {
                background-color: #74b9ff;
            }

            .color-box.obstructed {
                background-color: #dc3545;
            }

            .color-box.maintenance {
                background-color: #ffc107;
            }

            .status-text {
                color: #333;
                font-weight: 500;
            }

            .legend-container {
                animation: fadeIn 0.3s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            </style>
            `;

            // Add the styles to the document head
            $('head').append(legendStyles);

            // Add the legend to the map
            legendControl.addTo(map);

            var drawingRect = false;
            var rectangle = null;
            var plots = [];
            var plotLayers = [];

            var searchMarker = null;
            var searchResults = $('<div>').addClass('search-results');

            // Add the search results container after the search input
            $('#searchPlot').after(searchResults);

            // Add this CSS to your existing styles
            const searchStyles = `
    <style>
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .search-result-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .search-result-item:hover {
        background: #f5f5f5;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }
    </style>
    `;
            $('head').append(searchStyles);

            // Enable drawing function
            function enableDrawing() {
                if (drawingRect) return;

                drawingRect = true;
                map.dragging.disable();

                let startPoint = null;
                let drawingLayer = null;

                function onMouseMove(e) {
                    if (!startPoint) return;

                    if (drawingLayer) {
                        map.removeLayer(drawingLayer);
                    }

                    drawingLayer = L.rectangle([startPoint, e.latlng], {
                        color: DRAWING_COLOR,
                        weight: 2,
                        fillOpacity: 0.3
                    }).addTo(map);
                }

                function onMouseDown(e) {
                    startPoint = e.latlng;
                    map.on('mousemove', onMouseMove);
                }

                function onMouseUp(e) {
                    if (!startPoint) return;

                    map.off('mousemove', onMouseMove);

                    if (drawingLayer) {
                        map.removeLayer(drawingLayer);
                    }

                    rectangle = L.rectangle([startPoint, e.latlng], {
                        color: DRAWING_COLOR,
                        weight: 2,
                        fillOpacity: 0.3
                    }).addTo(map);

                    // Store coordinates for the new plot
                    currentPlot = {
                        coordinates: JSON.stringify({
                            southWest: startPoint,
                            northEast: e.latlng
                        })
                    };

                    drawingRect = false;
                    map.dragging.enable();

                    $('#plotForm')[0].reset();
                    $('#plotId').val('');
                    $('#plotModal').modal('show');
                }

                map.once('mousedown', onMouseDown);
                map.once('mouseup', onMouseUp);
            }

            // Load plots function
            function loadPlots() {
                $.ajax({
                    url: 'process/get_plots.php',
                    success: function(response) {
                        if (response.success) {
                            plotLayers.forEach(layer => map.removeLayer(layer));
                            plotLayers = [];
                            plots = response.data;

                            // First, create all plot layers
                            plots.forEach(plot => {
                                try {
                                    const coords = JSON.parse(plot.coordinates);
                                    const bounds = [
                                        [coords.southWest.lat, coords.southWest.lng],
                                        [coords.northEast.lat, coords.northEast.lng]
                                    ];

                                    const plotLayer = L.rectangle(bounds, {
                                        color: STATUS_COLORS[plot.status] || '#999',
                                        weight: 2,
                                        fillOpacity: 0.3
                                    }).addTo(map);

                                    const popupContent = `
                                <div class="plot-popup">
                                    <div class="plot-details-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">${plot.title}</h6>
                                    </div>
                                    <div class="plot-details-content">
                                        <p>Status: <span class="badge bg-${getStatusClass(plot.status)}">${plot.status}</span></p>
                                        <p>${plot.description || ''}</p>
                                    </div>
                                    <div class="plot-details-footer d-flex gap-2">
                                        <button onclick="showPlotDetails(${plot.id})" class="btn btn-sm btn-info">
                                            <i class="fas fa-info-circle"></i> Details
                                        </button>
                                        <button onclick="editPlot(${plot.id})" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button onclick="deletePlot(${plot.id})" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            `;

                                    plotLayer.bindPopup(popupContent);
                                    plotLayer.plotId = plot.id;
                                    plotLayers.push(plotLayer);
                                } catch (e) {
                                    console.error('Error processing plot:', e);
                                }
                            });

                            // Check for plot_id parameter after plots are loaded
                            const urlParams = new URLSearchParams(window.location.search);
                            const plotId = urlParams.get('plot_id');
                            if (plotId) {
                                const plot = plots.find(p => p.id === parseInt(plotId));
                                if (plot) {
                                    const coords = JSON.parse(plot.coordinates);
                                    const bounds = [
                                        [coords.southWest.lat, coords.southWest.lng],
                                        [coords.northEast.lat, coords.northEast.lng]
                                    ];

                                    // Center point for marker
                                    const center = [
                                        (coords.northEast.lat + coords.southWest.lat) / 2,
                                        (coords.northEast.lng + coords.southWest.lng) / 2
                                    ];

                                    // Remove existing marker if any
                                    if (searchMarker) {
                                        map.removeLayer(searchMarker);
                                    }

                                    // Add new marker
                                    searchMarker = L.marker(center).addTo(map);

                                    // Smooth zoom with animation
                                    map.flyToBounds(bounds, {
                                        padding: [50, 50],
                                        duration: 1.5,
                                        easeLinearity: 0.25
                                    });

                                    // Highlight selected plot
                                    plotLayers.forEach(layer => {
                                        if (layer.plotId === parseInt(plotId)) {
                                            layer.setStyle({
                                                opacity: 1,
                                                fillOpacity: 0.6,
                                                weight: 3
                                            });
                                        } else {
                                            layer.setStyle({
                                                opacity: 0.2,
                                                fillOpacity: 0.1,
                                                weight: 1
                                            });
                                        }
                                    });

                                    // Show the clear button
                                    $('#clearHighlight').show();
                                }
                            }
                        }
                    }
                });
            }

            // Event handlers
            $('#addPlot').click(enableDrawing);

            let currentPlot = null;

            window.editPlot = function(plotId) {
                const plot = plots.find(p => p.id === plotId);
                if (!plot) return;

                currentPlot = plot;

                // Populate modal fields
                $('#plotId').val(plot.id);
                $('#title').val(plot.title);
                $('#description').val(plot.description);
                $('#status').val(plot.status);

                // Show modal
                $('#plotModal').modal('show');
            };

            window.deletePlot = function(plotId) {
                if (confirm('Are you sure you want to delete this plot?')) {
                    $.ajax({
                        url: 'process/delete_plot.php',
                        method: 'POST',
                        data: { id: plotId },
                        success: function(response) {
                            if (response.success) {
                                loadPlots(); // Refresh plots
                                alert(response.message);
                            } else {
                                alert('Error: ' + (response.error || 'Unknown error'));
                            }
                        }
                    });
                }
            };

            // Handle save plot
            $('#savePlot').click(function() {
                if (!currentPlot) return;

                // Create FormData object
                const formData = new FormData();

                // Add all the plot data
                formData.append('id', $('#plotId').val());
                formData.append('title', $('#title').val());
                formData.append('description', $('#description').val());
                formData.append('status', $('#status').val());
                formData.append('owner_id', $('#owner_id').val());
                formData.append('coordinates', JSON.stringify(currentPlot.coordinates));

                // Debug log
                console.log('Sending data:', {
                    id: $('#plotId').val(),
                    title: $('#title').val(),
                    description: $('#description').val(),
                    status: $('#status').val(),
                    owner_id: $('#owner_id').val(),
                    coordinates: currentPlot.coordinates
                });

                $.ajax({
                    url: 'process/save_plot.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Server response:', response);
                        if (response.success) {
                            $('#plotModal').modal('hide');
                            if (rectangle) {
                                map.removeLayer(rectangle);
                                rectangle = null;
                            }
                            loadPlots();
                            currentPlot = null;
                            alert(response.message);
                        } else {
                            alert('Error: ' + (response.error || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax error:', xhr.responseText);
                        alert('Error saving plot: ' + error);
                    }
                });
            });

            // Initialize
            loadPlots();

            // Replace the existing search functionality with this
            $('#searchPlot').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                searchResults.empty();

                if (!searchTerm) {
                    searchResults.hide();
                    if (searchMarker) {
                        map.removeLayer(searchMarker);
                    }
                    plotLayers.forEach(layer => {
                        layer.setStyle({ opacity: 1, fillOpacity: 0.3, weight: 2 });
                    });
                    return;
                }

                const matches = plots.filter(plot =>
                    plot.title.toLowerCase().includes(searchTerm)
                );

                matches.forEach(plot => {
                    const resultItem = $('<div>')
                        .addClass('search-result-item')
                        .text(plot.title)
                        .click(function() {
                            const coords = JSON.parse(plot.coordinates);
                            const bounds = [
                                [coords.southWest.lat, coords.southWest.lng],
                                [coords.northEast.lat, coords.northEast.lng]
                            ];

                            // Center point for marker
                            const center = [
                                (coords.northEast.lat + coords.southWest.lat) / 2,
                                (coords.northEast.lng + coords.southWest.lng) / 2
                            ];

                            // Remove existing marker if any
                            if (searchMarker) {
                                map.removeLayer(searchMarker);
                            }

                            // Add new marker
                            searchMarker = L.marker(center).addTo(map);

                            // Smooth zoom with animation
                            map.flyToBounds(bounds, {
                                padding: [50, 50],
                                duration: 1.5,
                                easeLinearity: 0.25
                            });

                            // Highlight selected plot
                            plotLayers.forEach(layer => {
                                if (layer.plotId === plot.id) {
                                    layer.setStyle({
                                        opacity: 1,
                                        fillOpacity: 0.6,
                                        weight: 3
                                    });
                                } else {
                                    layer.setStyle({
                                        opacity: 0.2,
                                        fillOpacity: 0.1,
                                        weight: 1
                                    });
                                }
                            });

                            searchResults.hide();
                            $('#clearHighlight').show();
                        });
                    searchResults.append(resultItem);
                });

                searchResults.show();
            });

            // Close search results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-results, #searchPlot').length) {
                    searchResults.hide();
                }
            });

            // Update the plot popup creation
            function createPlotPopup(plot) {
                return `
            <div class="plot-popup">
                <h6>${plot.title}</h6>
                <p class="mb-1"><strong>Status:</strong> <span class="badge bg-${getStatusBadgeClass(plot.status)}">${plot.status}</span></p>
                ${plot.description ? `<p class="mb-2">${plot.description}</p>` : ''}
                <div class="popup-actions">
                    <button class="btn btn-sm btn-primary edit-btn" onclick="editPlot(${JSON.stringify(plot)})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" onclick="deletePlot(${plot.id})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        `;
    }

   

    function getStatusClass(status) {
        const classes = {
            available: 'success',
            reserved: 'warning',
            occupied: 'primary',
            sold: 'info',
            hold: 'secondary',
            obstructed: 'danger',
            maintenance: 'warning'
        };
        return classes[status] || 'secondary';
    }

    // Add this function near the start of your document.ready
    function zoomToPlot(plotId) {
        if (!plotId || !plots.length) return;

        const plot = plots.find(p => p.id === parseInt(plotId));
        if (!plot) return;

        try {
            const coords = JSON.parse(plot.coordinates);
            const bounds = [
                [coords.southWest.lat, coords.southWest.lng],
                [coords.northEast.lat, coords.northEast.lng]
            ];
            
            // Calculate center
            const center = [
                (coords.northEast.lat + coords.southWest.lat) / 2,
                (coords.northEast.lng + coords.southWest.lng) / 2
            ];

            // Remove existing marker if any
            if (searchMarker) {
                map.removeLayer(searchMarker);
            }

            // Add new marker
            searchMarker = L.marker(center).addTo(map);

            // Zoom to plot
            map.flyToBounds(bounds, {
                padding: [50, 50],
                duration: 1.5
            });
        } catch (e) {
            console.error('Error zooming to plot:', e);
        }
    }

    // Add this new function at the start of your document.ready
    function handlePlotHighlight(plotId) {
        console.log("Handling plot highlight for ID:", plotId); // Debug log

        const plot = plots.find(p => p.id === parseInt(plotId));
        if (!plot) {
            console.log("Plot not found");
            return;
        }

        try {
            console.log("Found plot:", plot); // Debug log
            const coords = JSON.parse(plot.coordinates);
            
            // Calculate center point
            const center = [
                (coords.northEast.lat + coords.southWest.lat) / 2,
                (coords.northEast.lng + coords.southWest.lng) / 2
            ];

            // Remove existing marker
            if (searchMarker) {
                map.removeLayer(searchMarker);
            }

            // Add marker
            searchMarker = L.marker(center).addTo(map);
            console.log("Added marker at:", center); // Debug log

            // Zoom to plot
            const bounds = [
                [coords.southWest.lat, coords.southWest.lng],
                [coords.northEast.lat, coords.northEast.lng]
            ];
            
            map.flyToBounds(bounds, {
                padding: [50, 50],
                duration: 1.5
            });
            console.log("Zoomed to bounds:", bounds); // Debug log

        } catch (error) {
            console.error("Error in handlePlotHighlight:", error);
        }
    }

    let initialPlotId = null;
    function zoomToPlotById(plotId) {
        if (!plotId || !plots.length) return;
        
        const plot = plots.find(p => p.id === parseInt(plotId));
        if (!plot) {
            console.log('Plot not found:', plotId);
            return;
        }

        try {
            const coords = JSON.parse(plot.coordinates);
            const bounds = [
                [coords.southWest.lat, coords.southWest.lng],
                [coords.northEast.lat, coords.northEast.lng]
            ];
            
            // Center point for marker
            const center = [
                (coords.northEast.lat + coords.southWest.lat) / 2,
                (coords.northEast.lng + coords.southWest.lng) / 2
            ];

            // Remove existing marker if any
            if (searchMarker) {
                map.removeLayer(searchMarker);
            }

            // Add new marker
            searchMarker = L.marker(center).addTo(map);

            // Zoom to plot
            map.flyToBounds(bounds, {
                padding: [50, 50],
                duration: 1.5
            });
        } catch (error) {
            console.error("Error in zoomToPlotById:", error);
        }
    }

    function clearHighlight() {
        if (searchMarker) {
            map.removeLayer(searchMarker);
            searchMarker = null;
        }

        plotLayers.forEach(layer => {
            layer.setStyle({ 
                opacity: 1, 
                fillOpacity: 0.3,
                weight: 2
            });
        });

        map.fitBounds(bounds);

        $('#clearHighlight').hide();

        const newUrl = window.location.pathname;
        window.history.pushState({}, '', newUrl);
    }

    $(document).ready(function() {

        $('#clearHighlight').click(function() {
            clearHighlight();
        });

        searchResults.on('click', '.search-result-item', function() {
            $('#clearHighlight').show();
        });
    });

    // Update the showPlotDetails function
    window.showPlotDetails = function(plotId) {
        const plot = plots.find(p => p.id === plotId);
        if (!plot) return;

        // Update header
        $('#settingsOffcanvasLabel').text(plot.title || 'Plot Details');
        $('#offcanvasSubtitle').text('Plot Information');
        
        // Create the complete details content
        const detailsContent = `
            <!-- Plot Status -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Status</h6>
                <div id="plotStatus">
                    <span class="badge bg-${getStatusClass(plot.status)}">${plot.status}</span>
                </div>
            </div>

            <!-- Plot Description -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Description</h6>
                <p class="text-muted">${plot.description || 'No description available'}</p>
            </div>

            <!-- Burial Information -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Burial Information</h6>
                <div class="card border">
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> ${plot.burial_name || 'N/A'}</p>
                        <p class="mb-1"><strong>Date:</strong> ${plot.burial_date ? new Date(plot.burial_date).toLocaleDateString() : 'N/A'}</p>
                        <p class="mb-0"><strong>Remarks:</strong> ${plot.remarks || 'None'}</p>
                    </div>
                </div>
            </div>

            <!-- Owner Information -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Owner Information</h6>
                <div class="card border">
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> ${plot.owner_name     || 'N/A'}</p>
                        <p class="mb-1"><strong>Contact:</strong> ${plot.owner_contact || 'N/A'}</p>
                        <p class="mb-0"><strong>Address:</strong> ${plot.owner_address || 'N/A'}</p>
                    </div>
                </div>
            </div>
        `;

        // Update the offcanvas content
        $('#settingsOffcanvas .offcanvas-body').html(detailsContent);

        // Show the offcanvas
        const offcanvas = new bootstrap.Offcanvas(document.getElementById('settingsOffcanvas'));
        offcanvas.show();
    };

    // Reset to default settings when offcanvas is hidden
    document.getElementById('settingsOffcanvas').addEventListener('hidden.bs.offcanvas', function () {
        $('#defaultSettings').show();
        $('#plotDetails').hide();
        $('#settingsOffcanvasLabel').text('Material UI Configurator');
        $('.offcanvas-header p').text('See our dashboard options.');
    });

    // Settings panel toggle
    $('.fixed-plugin-button-nav').click(function() {
        $('.fixed-plugin .card').toggleClass('show');
    });

    // Close button handler
    $('.fixed-plugin-close-button').click(function() {
        $('.fixed-plugin .card').removeClass('show');
    });
});