// Global variables
let plotMap = null;
let plotLayers = [];
let currentMode = 'add';



function initializePlotMap() {
    // Initialize the map
    plotMap = L.map('plotMap', {
        crs: L.CRS.Simple,
        minZoom: 1.0
    });

    const bounds = [
        [0, 0],
        [1000, 1000]
    ];
    L.imageOverlay('images/map1.png', bounds).addTo(plotMap);
    plotMap.fitBounds(bounds);

    // Load plots
    loadPlots();

    // Add search functionality
    $('#plotSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        plotLayers.forEach(layer => {
            const plotTitle = layer.options.title.toLowerCase();
            if (plotTitle.includes(searchTerm)) {
                layer.setStyle({ opacity: 1, fillOpacity: 0.3 });
            } else {
                layer.setStyle({ opacity: 0.2, fillOpacity: 0.1 });
            }
        });
    });
}

function loadPlots() {
    $.ajax({
        url: 'process/get_plots.php',
        success: function(response) {
            if (response.success) {
                // Clear existing layers
                plotLayers.forEach(layer => plotMap.removeLayer(layer));
                plotLayers = [];

                response.data.forEach(plot => {
                    try {
                        const coords = JSON.parse(plot.coordinates);
                        const bounds = [
                            [coords.southWest.lat, coords.southWest.lng],
                            [coords.northEast.lat, coords.northEast.lng]
                        ];

                        const layer = L.rectangle(bounds, {
                            color: getStatusColor(plot.status),
                            weight: 2,
                            fillOpacity: 0.3,
                            title: plot.title,
                            id: plot.id
                        }).addTo(plotMap);

                        layer.on('click', () => handlePlotSelection(plot.id, plot.title));
                        plotLayers.push(layer);
                    } catch (error) {
                        console.error('Error processing plot:', error);
                    }
                });
            }
        },
        error: function(error) {
            console.error('Error loading plots:', error);
        }
    });
}

function getStatusColor(status) {
    const colors = {
        available: '#28a745',
        occupied: '#dc3545',
        maintenance: '#ffc107',
        sold: '#1A73E8',
        hold: '#74b9ff',
        obstructed: '#dc3545'
    };
    return colors[status] || '#gray';
}

function handlePlotSelection(plotId, plotTitle) {
    const mode = currentMode;
    const prefix = mode === 'edit' ? 'edit_' : '';

    console.log('Selecting plot:', { mode, plotId, plotTitle });

    // Update the form fields
    $(`#${prefix}plot_id`).val(plotId);
    $(`#${prefix}plot_title`).val(plotTitle);
    $(`#${prefix}plot_title_display`).val(plotTitle);

    // Hide plot selector modal
    $('#plotSelectorModal').modal('hide');

    // Show the appropriate modal
    if (mode === 'edit') {
        $('#editModal').modal('show');
    }
}

function editDecease(burialId) {
    $.ajax({
        url: 'process/get_deceased.php',
        type: 'POST',
        data: { burial_id: burialId },
        dataType: 'json',
        success: function(response) {
            if (response) {
                console.log('Edit response:', response);

                // Update form fields
                $('#burial_id').val(response.burial_id);
                $('#edit_deceased_name').val(response.burial_name);
                $('#edit_burial_date').val(response.burial_date);
                $('#edit_plot_id').val(response.plot_id);
                $('#edit_plot_title').val(response.plot_title);
                $('#edit_plot_title_display').val(response.plot_title);
                $('#edit_remarks').val(response.remarks);

                // Show the modal
                $('#editModal').modal('show');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching burial details:', error);
            alert('Error loading burial details. Please try again.');
        }
    });
}

// Modal event handlers
$('#plotSelectorModal').on('shown.bs.modal', function() {
    if (plotMap) {
        plotMap.invalidateSize();
    }
});