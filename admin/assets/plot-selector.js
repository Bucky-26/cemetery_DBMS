$(document).ready(function() {
    // Status colors
    const STATUS_COLORS = {
        available: '#28a745',
        occupied: '#dc3545',
        maintenance: '#ffc107',
        sold: '#1A73E8',
        hold: '#74b9ff',
        obstructed: '#dc3545'
    };

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

    var plotLayers = [];
    var plots = [];

    // Load plots function
    function loadPlots() {
        $.ajax({
            url: 'process/get_plots.php',
            success: function(response) {
                if (response.success) {
                    plotLayers.forEach(layer => map.removeLayer(layer));
                    plotLayers = [];
                    plots = response.data;

                    plots.forEach(plot => {
                        try {
                            const coords = JSON.parse(plot.coordinates);
                            const bounds = [
                                [coords.southWest.lat, coords.southWest.lng],
                                [coords.northEast.lat, coords.northEast.lng]
                            ];

                            const plotLayer = L.rectangle(bounds, {
                                color: STATUS_COLORS[plot.status],
                                weight: 2,
                                fillOpacity: 0.3
                            }).addTo(map);

                            plotLayer.on('click', function() {
                                updatePlotSelection(plot.id, plot.title);
                            });

                            plotLayers.push(plotLayer);
                        } catch (e) {
                            console.error('Error processing plot:', e);
                        }
                    });
                }
            }
        });
    }

    // Initialize
    loadPlots();

    // Search functionality
    $('#searchPlot').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();

        if (!searchTerm) {
            plotLayers.forEach(layer => {
                layer.setStyle({ opacity: 1, fillOpacity: 0.3 });
            });
            return;
        }

        plots.forEach((plot, index) => {
            if (plot.title.toLowerCase().includes(searchTerm)) {
                plotLayers[index].setStyle({ opacity: 1, fillOpacity: 0.6 });
            } else {
                plotLayers[index].setStyle({ opacity: 0.2, fillOpacity: 0.1 });
            }
        });
    });
});

function updatePlotSelection(plotId, plotTitle) {
    const mode = document.getElementById('selectorMode').value;
    if (mode === 'edit') {
        prefix = 'edit_';
    } else {
        prefix = '';
    }

    if (window.opener) {
        try {
            // Update hidden inputs
            const idInput = window.opener.document.getElementById(prefix + 'plot_id');
            const titleInput = window.opener.document.getElementById(prefix + 'plot_title');
            const displayDiv = window.opener.document.getElementById(prefix + 'plot_title_display');

            if (idInput) idInput.value = plotId;
            if (titleInput) titleInput.value = plotTitle;
            if (displayDiv) displayDiv.textContent = plotTitle;

            // Close the window
            window.close();
        } catch (error) {
            console.error('Error updating plot selection:', error);
            alert('Error selecting plot. Please try again.');
        }
    } else {
        alert('Parent window not found. Please try again.');
    }
}