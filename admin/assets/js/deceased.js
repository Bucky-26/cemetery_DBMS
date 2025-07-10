$(document).ready(function() {
    $('#addDeceasedForm').on('submit', function(e) {
        e.preventDefault();

        // Verify plot_id is present
        if (!$('#plot_id').val()) {
            alert('Please select a plot first');
            return false;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Close modal
                    $('#addDeceaseModal').modal('hide');

                    // Reset form
                    $('#addDeceasedForm')[0].reset();
                    $('#plot_title_display').text('');
                    $('#plot_id').val('');
                    $('#plot_title').val('');

                    // Refresh the table or show success message
                    alert('Record added successfully');
                    location.reload(); // Or update your table using AJAX
                } else {
                    alert(response.message || 'Error adding record');
                }
            },
            error: function() {
                alert('Error occurred while processing request');
            }
        });
    });
});

function openPlotSelector(mode = 'add') {
    // Store the current mode (add or edit) in localStorage
    localStorage.setItem('plotSelectorMode', mode);

    // Open the plot selector in a new window with specific dimensions
    const plotWindow = window.open(
        'plot-selector.php',
        'Plot Selector',
        'width=800,height=600,resizable=yes,scrollbars=yes'
    );

    // Focus the new window
    if (plotWindow) {
        plotWindow.focus();
    }
}
document.getElementById('searchInput').addEventListener('keyup', function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll('#deceaseTable tbody tr');

    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
});
document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript loaded');
});

function editsDecease(burialId) {
    // Create FormData object
    const formData = new FormData();
    formData.append('burial_id', burialId);

    // Fetch burial record data
    fetch('model/get_single_deceased.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data); // Debug log

            // Set the burial_id in the hidden input
            document.getElementById('burial_id').value = burialId;

            // Populate the edit modal with the data
            document.getElementById('edit_deceased_name').value = data.burial_name;
            document.getElementById('edit_burial_date').value = data.burial_date;
            document.getElementById('edit_plot_id').value = data.plot_id;
            document.getElementById('edit_plot_title').value = data.plot_title;
            document.getElementById('old_plot_id').value = data.plot_id;
            document.getElementById('edit_plot_title_display').textContent = data.plot_title;
            document.getElementById('edit_remarks').value = data.remarks || '';

            // Show the modal
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function viewDecease(burialId) {
    console.log('View button clicked for burial ID:', burialId);

    fetch('model/get_single_deceased.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'burial_id=' + burialId
        })
        .then(response => response.json())
        .then(data => {
            // Redirect to mapping.php with the plot_id parameter
            window.location.href = 'mapping.php?plot_id=' + data.plot_id;
        })
        .catch(error => console.error('Error:', error));
}

function openPlotSelector(mode = 'add') {
    console.log('Opening plot selector:', mode);

    // Open in center of screen
    const width = 800;
    const height = 600;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;

    const plotWindow = window.open(
        `plot-selector.php?mode=${mode}`,
        'plotSelector',
        `width=${width},height=${height},left=${left},top=${top},scrollbars=yes`
    );

    if (!plotWindow) {
        alert('Please enable popups for this site');
        return;
    }

    // Ensure the window is focused
    plotWindow.focus();
}

function openPlotSelectoredit(mode = 'edit') {
    console.log('Opening plot selector:', mode);

    // Open in center of screen
    const width = 800;
    const height = 600;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;

    const plotWindow = window.open(
        `plot-selector_edit.php`,
        'plotSelector',
        `width=${width},height=${height},left=${left},top=${top},scrollbars=yes`
    );

    if (!plotWindow) {
        alert('Please enable popups for this site');
        return;
    }

    // Ensure the window is focused
    plotWindow.focus();
}

function deleteRecord(burialId) {
    if (confirm('Are you sure you want to delete this burial record?')) {
        fetch('process/delete_deceased.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'burial_id=' + burialId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the page or remove the row from the table
                    location.reload();
                } else {
                    alert('Error deleting record: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting record');
            });
    }
}