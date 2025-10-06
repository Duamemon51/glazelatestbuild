<form method="POST" action="{{ route('admin.orders.update', $order) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title">
            <i class="fas fa-edit me-2"></i>Edit Order
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12">
                <div class="bg-light rounded p-3 mb-3">
                    <h6 class="mb-2">
                        <i class="fas fa-info-circle me-1"></i>Order Information
                    </h6>
                    <div class="row">
                        <div class="col-sm-6">
                            <small class="text-muted">Order Number:</small>
                            <div class="fw-bold">{{ $order->order_number }}</div>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted">Customer:</small>
                            <div class="fw-bold">{{ $order->user->name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label">Order Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="payment_status" class="form-label">Payment Status</label>
                <select id="payment_status" name="payment_status" class="form-select" required>
                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>

            <div class="col-12">
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" name="notes" class="form-control" rows="3"
                          placeholder="Add any notes about this order...">{{ old('notes', $order->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-3 p-3 bg-light rounded">
            <h6 class="mb-2">
                <i class="fas fa-dollar-sign me-1"></i>Order Summary
            </h6>
            <div class="row">
                <div class="col-sm-6">
                    <small class="text-muted">Total Amount:</small>
                    <div class="fw-bold fs-5">${{ number_format($order->total_amount, 2) }}</div>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted">Order Date:</small>
                    <div class="fw-bold">{{ $order->created_at->format('M j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Update Order
        </button>
    </div>
</form>