<div class="offcanvas offcanvas-end" tabindex="-1" id="settingsOffcanvas" aria-labelledby="settingsOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title" id="settingsOffcanvasLabel">Plot Details</h5>
            <p class="mb-0 text-muted" id="offcanvasSubtitle">View plot information</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Plot Details Contents -->
        <div id="plotDetails">
            <!-- Plot Status -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Status</h6>
                <div id="plotStatus"></div>
            </div>

            <!-- Plot Description -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Description</h6>
                <p class="text-muted" id="plotDescription"></p>
            </div>

            <!-- Burial Information -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Burial Information</h6>
                <div class="card border">
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> <span id="burialName"></span></p>
                        <p class="mb-1"><strong>Date:</strong> <span id="burialDate"></span></p>
                        <p class="mb-0"><strong>Remarks:</strong> <span id="burialRemarks"></span></p>
                    </div>
                </div>
            </div>

            <!-- Owner Information -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Owner Information</h6>
                <div class="card border">
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> <span id="ownerName"></span></p>
                        <p class="mb-1"><strong>Contact:</strong> <span id="ownerContact"></span></p>
                        <p class="mb-0"><strong>Address:</strong> <span id="ownerAddress"></span></p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Actions</h6>
                <div class="d-flex gap-2" id="plotActions">
                    <!-- Actions will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.offcanvas {
    max-width: 330px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease-in-out;
    animation: slideIn 0.3s ease-in-out;
}

.offcanvas-header {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
}

.offcanvas-body {
    padding: 1.5rem;
    animation: fadeIn 0.4s ease-in-out;
}

#plotDetails .badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    text-transform: capitalize;
}

#plotDetails .card {
    background: #f8f9fa;
    border-color: #dee2e6;
}

#plotDetails .card-body {
    padding: 1rem;
}

#plotActions .btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

#plotActions .btn i {
    width: 16px;
}

/* Animation for the offcanvas */
.offcanvas {
    transition: transform 0.3s ease-in-out;
    animation: slideIn 0.3s ease-in-out;
}

/* Animation for the content */
.offcanvas-body {
    padding: 1.5rem;
    animation: fadeIn 0.4s ease-in-out;
}

/* Keyframes for slide-in animation */
@keyframes slideIn {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

/* Keyframes for fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>