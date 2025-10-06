@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Orders Management</h1>
        <div class="flex items-center space-x-2">
            <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" onclick="refreshPage()">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($orders as $order)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-lg"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</h3>
                        <p class="text-gray-600 text-sm">{{ $order->user->name }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <span class="text-gray-500 text-sm">Total</span>
                            <p class="font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ $order->created_at->format('M j, Y') }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            onclick="showOrder({{ $order->id }})">
                        <i class="fas fa-eye mr-1"></i> View
                    </button>
                    <button class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            onclick="editOrder({{ $order->id }})">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Orders Found</h3>
                <p class="text-gray-500">There are no orders in the system yet.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<!-- Modals -->
<div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3" id="orderModalContent">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>

<script>
// Show order details modal
function showOrder(orderId) {
    fetch(`{{ url('admin/orders') }}/${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('orderModalContent').innerHTML = html;
        document.getElementById('orderModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error loading order details:', error);
        alert('Error loading order details. Please try again.');
    });
}

// Edit order modal
function editOrder(orderId) {
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
        document.getElementById('orderModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error loading edit form:', error);
        alert('Error loading edit form. Please try again.');
    });
}

// Close modal
function closeModal() {
    document.getElementById('orderModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Refresh page
function refreshPage() {
    window.location.reload();
}
</script>
@endsection