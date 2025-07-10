<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <i class="fas fa-exclamation-circle fa-3x text-warning"></i>
                    <h4 class="text-gradient text-danger mt-4">Are you sure?</h4>
                    <p>Do you really want to delete this employee? This process cannot be undone.</p>
                </div>
                <input type="hidden" id="deleteEmployeeId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn bg-gradient-danger" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>
