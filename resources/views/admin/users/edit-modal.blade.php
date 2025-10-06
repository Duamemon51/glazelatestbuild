<form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title">
            <i class="fas fa-edit me-2"></i>Edit User
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12">
                <label for="edit_name" class="form-label">Full Name</label>
                <input type="text" id="edit_name" name="name" value="{{ old('name', $user->name) }}"
                       class="form-control" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="edit_email" class="form-label">Email Address</label>
                <input type="email" id="edit_email" name="email" value="{{ old('email', $user->email) }}"
                       class="form-control" required>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="edit_password" class="form-label">New Password (leave blank to keep current)</label>
                <input type="password" id="edit_password" name="password" class="form-control">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="edit_password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" id="edit_password_confirmation" name="password_confirmation" class="form-control">
            </div>
        </div>

        <div class="mt-3 p-3 bg-light rounded">
            <h6 class="mb-2">
                <i class="fas fa-info-circle me-1"></i>Account Information
            </h6>
            <div class="row">
                <div class="col-sm-6">
                    <small class="text-muted">User ID:</small>
                    <div class="fw-bold">#{{ $user->id }}</div>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted">Joined:</small>
                    <div class="fw-bold">{{ $user->created_at->format('M j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Update User
        </button>
    </div>
</form>