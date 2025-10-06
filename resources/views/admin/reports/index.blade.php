@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
        <div class="flex items-center space-x-2">
            <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" onclick="refreshPage()">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" onclick="generateReport()">
                <i class="fas fa-chart-line mr-2"></i> Generate Report
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">{{ number_format($totalUsers) }}</h3>
                    <p class="text-blue-100">Total Users</p>
                </div>
                <i class="fas fa-users text-3xl opacity-75"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">{{ number_format($totalOrders) }}</h3>
                    <p class="text-green-100">Total Orders</p>
                </div>
                <i class="fas fa-shopping-cart text-3xl opacity-75"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">${{ number_format($totalRevenue, 2) }}</h3>
                    <p class="text-cyan-100">Total Revenue</p>
                </div>
                <i class="fas fa-dollar-sign text-3xl opacity-75"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">{{ number_format($totalProducts) }}</h3>
                    <p class="text-yellow-100">Total Products</p>
                </div>
                <i class="fas fa-box text-3xl opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Order Status Distribution -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-chart-pie mr-2 text-blue-500"></i>Order Status Distribution
                </h3>
            </div>
            <div class="p-6">
                @if($orderStatusStats->isNotEmpty())
                    <canvas id="orderStatusChart" width="400" height="200"></canvas>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-6xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">No order data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-clock mr-2 text-green-500"></i>Recent Orders (Last 30 Days)
                </h3>
            </div>
            <div class="p-6">
                @if($recentOrders->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($recentOrders->take(5) as $order)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $order->order_number }}</h4>
                                <p class="text-sm text-gray-600">{{ $order->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</div>
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
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">No recent orders</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-chart-line mr-2 text-purple-500"></i>Monthly Revenue (Last 12 Months)
            </h3>
        </div>
        <div class="p-6">
            @if($monthlyRevenue->isNotEmpty())
                <canvas id="revenueChart" width="400" height="200"></canvas>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-chart-line text-6xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">No revenue data available</p>
                </div>
            @endif
        </div>
    </div>

    </div>
</div>

<!-- Report Generation Modal -->
<div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <form action="{{ route('admin.reports.generate') }}" method="GET">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Generate Detailed Report</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select name="type" id="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="overview">Overview Report</option>
                        <option value="sales">Sales Report</option>
                        <option value="orders">Orders Report</option>
                        <option value="users">Users Report</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ now()->subDays(30)->format('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Generate Report</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Order Status Chart
    @if($orderStatusStats->isNotEmpty())
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {{ Js::from(array_keys($orderStatusStats->toArray())) }},
            datasets: [{
                data: {{ Js::from(array_values($orderStatusStats->toArray())) }},
                backgroundColor: [
                    '#ffc107', // pending
                    '#0dcaf0', // processing
                    '#0d6efd', // shipped
                    '#198754', // delivered
                    '#dc3545'  // cancelled
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif

    // Revenue Chart
    @if($monthlyRevenue->isNotEmpty())
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($monthlyRevenue->reverse());

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => `${item.month}/${item.year}`),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(item => item.revenue),
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toFixed(2);
                        }
                    }
                }
            }
        }
    });
    @endif
});

// Generate report modal
function generateReport() {
    document.getElementById('reportModal').classList.remove('hidden');
}

// Close modal
function closeModal() {
    document.getElementById('reportModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('reportModal').addEventListener('click', function(e) {
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