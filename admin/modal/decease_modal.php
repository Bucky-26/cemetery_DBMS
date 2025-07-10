<style>
    
    .modal-content {
    border-radius: 15px;
}

.modal-header, .modal-footer {
    border: none;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
}

.btn-primary {
    background-color: #1a73e8;
    border: none;
    border-radius: 8px;
}

.btn-danger {
    border-radius: 0;
}

.btn-close {
    outline: none;
}

</style>
<div class="modal fade" id="addDeceaseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0">
                <h5 class="modal-title ms-2">Add New Burial Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addDeceasedForm" action="process/add_deceased.php" method="POST">
                <div class="modal-body px-4">
                    <!-- View Cemetery Map Button -->
                    <button type="button" class="btn btn-primary w-100 mb-4" 
                            style="background-color: #1a73e8; border: none; border-radius: 8px; padding: 10px;">
                        View Cemetery Map
                    </button>

                    <!-- Form Fields -->
                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">Full Name</label>
                        <input type="text" name="deceased_name" class="form-control" 
                               style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">Burial Date</label>
                        <input type="date" name="burial_date" class="form-control" 
                               placeholder="mm/dd/yyyy"
                               style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">Plot</label>
                        <div style="display: flex; background: #f0f0f0; height: 40px; border-radius: 10px; overflow: hidden;">
                            <div style="flex-grow: 1; padding: 8px 12px;" id="plot_title_display">
                                Select a plot...
                            </div>
                            <button type="button" onclick="openPlotSelector('add')" 
                                    class="btn btn-danger h-100"
                                    style="border-radius: 0;">
                                Select Plot
                            </button>
                        </div>
                        <div id="plot-selection-status" class="small text-muted mt-1"></div>
                        <input type="hidden" id="plot_id" name="plot_id" required>
                        <input type="hidden" id="plot_title" name="plot_title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="4" 
                                  style="border: 1px solid #ddd; border-radius: 8px; padding: 10px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn" 
                            style="background-color: #757575; color: white; border-radius: 8px;"
                            data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" 
                            style="background-color: #f44336; color: white; border-radius: 8px;">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0">
                <h5 class="modal-title ms-2">Edit Burial Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDeceasedForm" action="process/edit_deceased.php" method="POST">
                <input type="hidden" name="burial_id" id="burial_id">
                <input type="hidden" id="currentMode" value="edit">
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">Full Name</label>
                        <input type="text" id="edit_deceased_name" name="deceased_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">Burial Date</label>
                        <input type="date" id="edit_burial_date" name="burial_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">Plot</label>
                        <div style="display: flex; background: #f0f0f0; height: 40px; border-radius: 10px; overflow: hidden;">
                            <div style="flex-grow: 1; padding: 8px 12px;" id="edit_plot_title_display">
                                <!-- Plot title will show here -->
                            </div>
                            <button type="button" 
                                    onclick="openPlotSelectoredit()" 
                                    class="btn btn-danger h-100"
                                    style="border-radius: 0;">
                                Select Plot
                            </button>
                        </div>
                        <input type="hidden" id="edit_plot_id" name="plot_id" required>
                        <input type="hidden" id="edit_plot_title" name="plot_title" required>
                        <input type="hidden" id="old_plot_id" name="old_plot_id" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted mb-1">Remarks</label>
                        <textarea id="edit_remarks" name="remarks" class="form-control" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn" 
                            style="background-color: #757575; color: white; border-radius: 8px;"
                            data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" 
                            style="background-color: #f44336; color: white; border-radius: 8px;">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add this new modal for plot selection -->
<div class="modal fade" id="plotSelectorModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Plot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="currentMode" value="add">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="searchPlot" placeholder="Search plot...">
                </div>
                <div id="map" style="height: 500px;"></div>
            </div>
        </div>
    </div>
</div>