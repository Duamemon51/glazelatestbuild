<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-user me-2"></i>User Details
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                <i class="fas fa-user fa-2x"></i>
            </div>
            <h4>{{ $user->name }}</h4>
            <p class="text-muted">{{ $user->email }}</p>
        </div>

        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-id-card me-1"></i>User ID
                        </h6>
                        <p class="mb-0 fw-bold">#{{ $user->id }}</p>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-envelope me-1"></i>Email
                        </h6>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-calendar-plus me-1"></i>Joined Date
                        </h6>
                        <p class="mb-0">{{ $user->created_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-clock me-1"></i>Last Updated
                        </h6>
                        <p class="mb-0">{{ $user->updated_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-info-circle me-1"></i>Account Status
                        </h6>
                        <span class="badge bg-success fs-6">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times me-1"></i>Close
    </button>
    <button type="button" class="btn btn-primary" onclick="editUserFromShow({{ $user->id }})">
        <i class="fas fa-edit me-1"></i>Edit User
    </button>
</div>

<script>
// Edit user from show modal
function editUserFromShow(userId) {
    // Close current modal
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
    if (currentModal) {
        currentModal.hide();
    }

    // Open edit modal
    setTimeout(() => {
        fetch(`{{ url('admin/users') }}/${userId}/edit`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('userModalContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading edit form:', error);
            alert('Error loading edit form. Please try again.');
        });
    }, 300);
}
</script>