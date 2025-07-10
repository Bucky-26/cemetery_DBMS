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

    const mode = document.getElementById('selectorMode').value;

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
                        // Only show available plots
                        if (plot.status !== 'available') return;

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

                            const popupContent = `
                                <div class="plot-popup">
                                    <h6>${plot.title}</h6>
                                    <p>${plot.description || ''}</p>
                                    <button class="btn btn-primary btn-sm" onclick="selectPlot('${plot.id}', '${plot.title}')">
                                        Select This Plot
                                    </button>
                                </div>
                            `;

                            plotLayer.bindPopup(popupContent);
                            plotLayer.plotId = plot.id;
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

// Function to handle plot selection
function selectPlot(plotId, plotTitle) {
    if (window.opener && !window.opener.closed) {
        // Get the elements from the parent window
        const displayElement = window.opener.document.getElementById('edit_plot_title_display');
        const idInput = window.opener.document.getElementById('edit_plot_id');
        const titleInput = window.opener.document.getElementById('edit_plot_title');

        // Update the display text with proper styling
        if (displayElement) {
            displayElement.textContent = plotTitle;
            displayElement.style.color = '#333'; // Make text visible
            displayElement.style.lineHeight = '24px'; // Center text vertically
        }

        // Update the hidden inputs
        if (idInput) idInput.value = plotId;
        if (titleInput) titleInput.value = plotTitle;

        window.close();
    }
}