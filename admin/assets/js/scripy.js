var map = L.map('map', {
    crs: L.CRS.Simple,
    minZoom: -1
});

var bounds = [[0, 0], [1000, 1000]];
var image = L.imageOverlay('/admin/images/map1.png', bounds).addTo(map);
map.fitBounds(bounds);
map.setMaxBounds(bounds);

var drawingRect = false;
var rectangle = null;
var plots = [];
var plotLayers = [];
var isSaving = false;

// Status colors
const STATUS_COLORS = {
    available: '#28a745',
    occupied: '#dc3545',
    maintenance: '#ffc107'
};

// Search functionality
$('#searchPlot').on('input', function() {
    const searchTerm = $(this).val().toLowerCase();
    const results = plots.filter(plot => 
        plot.title.toLowerCase().includes(searchTerm) ||
        plot.description.toLowerCase().includes(searchTerm)
    );
    
    displaySearchResults(results);
});

function displaySearchResults(results) {
    const $results = $('#searchResults');
    $results.empty();
    
    if (results.length > 0) {
        results.forEach(plot => {
            const $item = $(`<a href="#" class="list-group-item">${plot.title}</a>`);
            $item.click(function(e) {
                e.preventDefault();
                zoomToPlot(plot);
                $results.hide();
            });
            $results.append($item);
        });
        $results.show();
    } else {
        $results.hide();
    }
}

function zoomToPlot(plot) {
    const coords = JSON.parse(plot.coordinates);
    const bounds = L.latLngBounds(
        [coords._southWest.lat, coords._southWest.lng],
        [coords._northEast.lat, coords._northEast.lng]
    );
    map.fitBounds(bounds, { padding: [50, 50] });
}

// Check for overlapping plots
function checkOverlap(newBounds) {
    return plotLayers.some(layer => {
        const layerBounds = layer.getBounds();
        return newBounds.overlaps(layerBounds);
    });
}

// Add plot button handler
$('#addPlot').click(function() {
    if (drawingRect) return;
    
    drawingRect = true;
    map.dragging.disable();
    
    let startPoint;
    
    map.once('mousedown', function(e) {
        startPoint = e.latlng;
        
        map.once('mouseup', function(e) {
            const endPoint = e.latlng;
            
            // Create rectangle
            if (rectangle) {
                map.removeLayer(rectangle);
            }
            
            rectangle = L.rectangle([startPoint, endPoint], {
                color: STATUS_COLORS.available,
                weight: 2,
                fillOpacity: 0.3
            }).addTo(map);
            
            drawingRect = false;
            map.dragging.enable();
            
            // Reset form and show modal
            $('#plotForm')[0].reset();
            $('#plotId').val('');
            $('#plotModal').modal('show');
        });
    });
});

// Plot details modal
function showPlotDetails(plot) {
    const html = `
        <div class="d-flex justify-content-between align-items-center">
            <h5>${plot.title}</h5>
            <div>
                <button class="btn btn-sm btn-primary edit-plot">Edit</button>
                <button class="btn btn-sm btn-danger delete-plot">Delete</button>
            </div>
        </div>
        <p>${plot.description}</p>
        <p><strong>Status:</strong> ${plot.status}</p>
    `;
    
    L.popup()
        .setLatLng(rectangle.getBounds().getCenter())
        .setContent(html)
        .openOn(map);
        
    $('.edit-plot').click(() => {
        $('#plotId').val(plot.id);
        $('#title').val(plot.title);
        $('#description').val(plot.description);
        $('#status').val(plot.status);
        $('#plotModal').modal('show');
    });
    
    $('.delete-plot').click(() => deletePlot(plot.id));
}

// Add plot to map
function addPlotToMap(plot) {
    const coords = JSON.parse(plot.coordinates);
    const bounds = [
        [coords._southWest.lat, coords._southWest.lng],
        [coords._northEast.lat, coords._northEast.lng]
    ];
    
    const rect = L.rectangle(bounds, {
        color: STATUS_COLORS[plot.status],
        weight: 2,
        fillOpacity: 0.3
    }).addTo(map);
    
    rect.bindTooltip(plot.title);
    rect.on('click', () => showPlotDetails(plot));
    
    plotLayers.push(rect);
}

// Save plot handler
$('#savePlot').click(function() {
    if (!rectangle) {
        alert('Please draw a plot first');
        return;
    }
    
    const bounds = rectangle.getBounds();
    const plotData = {
        id: $('#plotId').val() || null,
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
        return;
    }

    $.ajax({
        url: API_ENDPOINTS.SAVE_PLOT,
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
                loadPlots();
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

// Employee edit functionality
function editEmployee(employeeId) {
    $.ajax({
        url: 'model/get_employee.php',
        type: 'POST',
        data: { id: employeeId },
        success: function(response) {
            try {
                const employee = JSON.parse(response);
                // Populate the form fields
                $('#employee_id').val(employee.id);
                $('#firstname').val(employee.firstname);
                $('#lastname').val(employee.lastname);
                $('#username').val(employee.username);
                $('#email').val(employee.email);
                $('#job_title').val(employee.job_title);
                
                // Show the modal
                $('#editEmployeeModal').modal('show');
            } catch (e) {
                console.error('Error parsing employee data:', e);
                alert('Error loading employee data');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching employee:', error);
            alert('Error loading employee data');
        }
    });
}

// Handle employee edit form submission
$('#editEmployeeForm').on('submit', function(e) {
    e.preventDefault();
    
    // Log the data being sent
    console.log('Sending employee data:', $(this).serialize());
    
    $.ajax({
        url: 'model/update_employee.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.status === 'success') {
                $('#editEmployeeModal').modal('hide');
                // Show success message
                alert('Employee updated successfully');
                location.reload();
            } else {
                alert('Failed to update employee: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error details:', {
                status: status,
                error: error,
                response: xhr.responseText
            });
            alert('Error updating employee. Please try again.');
        }
    });
}); 