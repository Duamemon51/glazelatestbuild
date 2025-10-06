<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-shopping-cart me-2"></i>Order Details
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                <i class="fas fa-shopping-cart fa-2x"></i>
            </div>
            <h4>{{ $order->order_number }}</h4>
            <p class="text-muted">{{ $order->user->name }}</p>
        </div>

        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-hashtag me-1"></i>Order ID
                        </h6>
                        <p class="mb-0 fw-bold">#{{ $order->id }}</p>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-dollar-sign me-1"></i>Total Amount
                        </h6>
                        <p class="mb-0 fw-bold">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-calendar-plus me-1"></i>Order Date
                        </h6>
                        <p class="mb-0">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-clock me-1"></i>Last Updated
                        </h6>
                        <p class="mb-0">{{ $order->updated_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-truck me-1"></i>Order Status
                        </h6>
                        <span class="badge fs-6
                            @if($order->status === 'pending') bg-warning
                            @elseif($order->status === 'processing') bg-info
                            @elseif($order->status === 'shipped') bg-primary
                            @elseif($order->status === 'delivered') bg-success
                            @else bg-danger
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-credit-card me-1"></i>Payment Status
                        </h6>
                        <span class="badge fs-6
                            @if($order->payment_status === 'paid') bg-success
                            @elseif($order->payment_status === 'pending') bg-warning
                            @elseif($order->payment_status === 'failed') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                @if($order->notes)
                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-sticky-note me-1"></i>Notes
                        </h6>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times me-1"></i>Close
    </button>
    <button type="button" class="btn btn-primary" onclick="editOrderFromShow({{ $order->id }})">
        <i class="fas fa-edit me-1"></i>Edit Order
    </button>
</div>

<script>
// Edit order from show modal
function editOrderFromShow(orderId) {
    // Close current modal
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('orderModal'));
    if (currentModal) {
        currentModal.hide();
    }

    // Open edit modal
    setTimeout(() => {
        fetch(`{{ url('admin/orders') }}/${orderId}/edit`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('orderModalContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('orderModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading edit form:', error);
            alert('Error loading edit form. Please try again.');
        });
    }, 300);
}
</script>