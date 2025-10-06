@extends('layouts.admin')

@section('title','Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome back, {{ auth()->user()->name ?? 'Admin' }}!</h1>
        <p class="text-gray-600">Here's what's happening with your store today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Product::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-sitemap text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Categories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\ParentCategory::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Designs -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-palette text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Designs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Design::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Patterns -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-shapes text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Patterns</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Pattern::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row of Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Color Categories -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <i class="fas fa-folder text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Color Categories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\ColorCategory::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Colors -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-pink-100 rounded-lg">
                    <i class="fas fa-paint-brush text-pink-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Colors</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\AdminColor::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Users -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-shopping-cart text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Orders</p>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Products -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Products</h3>
            <div class="space-y-3">
                @foreach(\App\Models\Product::latest()->take(5)->get() as $product)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-box text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $product->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View all products →
                </a>
            </div>
        </div>

        <!-- Recent Designs -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Designs</h3>
            <div class="space-y-3">
                @foreach(\App\Models\Design::latest()->take(5)->get() as $design)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-palette text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $design->name }}</p>
                            <p class="text-sm text-gray-500">{{ $design->is_unique ? 'Unique' : 'Standard' }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $design->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.designs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View all designs →
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <button onclick="openCreateModal('products')"
                    class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-plus-circle text-blue-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-blue-600">Add Product</span>
            </button>
            <button onclick="openCreateModal('designs')"
                    class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-palette text-purple-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-purple-600">Add Design</span>
            </button>
            <button onclick="openCreateModal('patterns')"
                    class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <i class="fas fa-shapes text-orange-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-orange-600">Add Pattern</span>
            </button>
            <button onclick="openCreateModal('parents')"
                    class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-sitemap text-green-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-green-600">Add Category</span>
            </button>
            <button onclick="openCreateModal('subcategories')"
                    class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                <i class="fas fa-list text-indigo-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-indigo-600">Add Sub Category</span>
            </button>
            <button onclick="openCreateModal('product-types')"
                    class="flex flex-col items-center p-4 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                <i class="fas fa-tags text-teal-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-teal-600">Add Product Type</span>
            </button>
            <button onclick="openCreateModal('color-categories')"
                    class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                <i class="fas fa-folder text-indigo-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-indigo-600">Add Color Category</span>
            </button>
            <button onclick="openCreateModal('colors')"
                    class="flex flex-col items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors">
                <i class="fas fa-paint-brush text-pink-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-pink-600">Add Color</span>
            </button>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Create New Item</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Modal functions
function openCreateModal(type) {
    let title = '';
    let url = '';

    switch(type) {
        case 'products':
            title = 'Create New Product';
            url = '{{ route("admin.products.create") }}';
            break;
        case 'designs':
            title = 'Create New Design';
            url = '{{ route("admin.designs.create") }}';
            break;
        case 'patterns':
            title = 'Create New Pattern';
            url = '{{ route("admin.patterns.create") }}';
            break;
        case 'parents':
            title = 'Create New Category';
            url = '{{ route("admin.parents.create") }}';
            break;
        case 'subcategories':
            title = 'Create New Sub Category';
            url = '{{ route("admin.subcategories.create") }}';
            break;
        case 'product-types':
            title = 'Create New Product Type';
            url = '{{ route("admin.product-types.create") }}';
            break;
        case 'color-categories':
            title = 'Create New Color Category';
            url = '{{ route("admin.color-categories.create") }}';
            break;
        case 'colors':
            title = 'Create New Color';
            url = '{{ route("admin.colors.create") }}';
            break;
    }

    document.getElementById('modalTitle').textContent = title;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('createModal').classList.remove('hidden');
        document.getElementById('modalContent').innerHTML = html;

        // Execute scripts manually since innerHTML doesn't execute them
        const scripts = document.getElementById('modalContent').querySelectorAll('script');
        scripts.forEach(script => {
            if (script.src) {
                const newScript = document.createElement('script');
                newScript.src = script.src;
                document.head.appendChild(newScript);
            } else {
                try {
                    // Create a new script element to execute in proper context
                    const newScript = document.createElement('script');
                    newScript.textContent = script.textContent;
                    document.body.appendChild(newScript);
                    // Remove the script after execution
                    setTimeout(() => document.body.removeChild(newScript), 100);
                } catch (error) {
                    console.warn('Error executing modal script:', error);
                }
            }
        });

        // Reset form and initialize cascading selects
        const form = document.getElementById('createModalForm');
        if (form) {
            form.reset();
        }
        const pricingContainer = document.getElementById('createModal-pricing-tiers');
        if (pricingContainer) {
            pricingContainer.innerHTML = '';
            if (typeof addCreateModalPricingTier === 'function') {
                addCreateModalPricingTier();
            }
        }
        if (window.productHierarchyState && window.productHierarchyState.createModal && typeof window.productHierarchyState.createModal.populate === 'function') {
            window.productHierarchyState.createModal.populate(true);
        }
    })
    .catch(error => {
        console.error('Error loading modal content:', error);
        alert('Error loading form. Please try again.');
    });
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('modalContent').innerHTML = '';
}

// Close modal when clicking outside
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});
</script>
@endsection
