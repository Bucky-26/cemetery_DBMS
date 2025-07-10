<!-- Plot Modal -->
<div class="modal fade" id="plotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-map-marker-alt me-2"></i>Plot Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="plotForm" class="needs-validation" novalidate>
                    <input type="hidden" id="plotId">
                    <div class="mb-3">
                        <label for="title">Title</label>
                        <input type="text" class="form-control border p-2" id="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea class="form-control border p-2" id="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Owner</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="owner_name" 
                                   name="owner_name"
                                   placeholder="Select an Owner..." 
                                   style="flex: 1; background-color: #fff;" 
                                   readonly>
                            <input type="hidden" 
                                   id="owner_id" 
                                   name="owner_id"
                                   value="">
                            <button type="button" 
                                    class="btn btn-dark" 
                                    onclick="openOwnerSelection()" 
                                    style="width: 120px;">
                                Select Owner
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-control border p-2" id="status">
                            <option value="available" class="text-success">🟢 Available</option>
                            <option value="reserved" class="text-warning">🟡 Reserved</option>
                            <option value="occupied" class="text-primary">🔵 Occupied</option>
                            <option value="sold" class="text-info">💠 Sold</option>
                            <option value="hold" style="color: #74b9ff;">🔵 Hold</option>
                            <option value="obstructed" class="text-danger">🔴 Obstructed</option>
                            <option value="maintenance" class="text-warning">🟡 Maintenanc</option>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="background-color: #6c757d;" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background-color: #f44336; color: white;" id="savePlot">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Plot Info Modal -->
<div class="modal fade" id="plotInfoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="plotInfoTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="plotInfoBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary shadow-sm editPlot">Edit</button>
                <button type="button" class="btn btn-danger shadow-sm deletePlot">Delete</button>
            </div>
        </div>
    </div>
</div>
    
<script>
function openOwnerSelection() {
    // Define handleOwnerSelection before opening the window
    window.handleOwnerSelection = function(id, fullname) {
        try {
            // Get the input elements using exact IDs
            const ownerIdInput = document.getElementById('owner_id');
            const ownerNameInput = document.getElementById('owner_name');
            
            if (!ownerIdInput || !ownerNameInput) {
                console.error('Owner input elements not found');
                return;
            }
            
            // Set the values
            ownerIdInput.value = id;
            ownerNameInput.value = fullname;
            
            // Trigger change event for any listeners
            $(ownerNameInput).trigger('change');
            
            // Close the selection window
            if (window.ownerSelectionWindow && !window.ownerSelectionWindow.closed) {
                window.ownerSelectionWindow.close();
            }
        } catch (error) {
            console.error('Error handling owner selection:', error);
            alert('Error setting owner details: ' + error.message);
        }
    };
    
    // Open the window with the correct relative path
    window.ownerSelectionWindow = window.open('/admin/select_owner.php', 'OwnerSelection', 
        'width=800,height=600,resizable=yes,scrollbars=yes');
}

// Add this function to verify the values are set correctly when the modal opens
document.addEventListener('DOMContentLoaded', function() {
    const plotModal = document.getElementById('plotModal');
    if (plotModal) {
        plotModal.addEventListener('shown.bs.modal', function() {
            const ownerNameInput = document.getElementById('ownerName');
            const ownerIdInput = document.getElementById('owner_id');
            console.log('Modal opened - Owner values:', {
                name: ownerNameInput.value,
                id: ownerIdInput.value
            });
        });
    }
});

// Add this function to handle saving the plot

</script>
