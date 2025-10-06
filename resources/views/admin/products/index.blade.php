@extends('layouts.admin')

@section('title','Products Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Products Management</h1>
            <p class="text-gray-600">Manage your product catalog</p>
        </div>
        <button onclick="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add New Product
        </button>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Product Image -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="aspect-square bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="text-center text-gray-400">
                            <i class="fas fa-image text-3xl mb-2"></i>
                            <p class="text-sm">No image</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                </div>

                <!-- Product Details -->
                <div class="space-y-1 mb-3">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-tag mr-1"></i>
                        @if($product->productType)
                            {{ $product->productType->name }}
                            @if($product->productType->parentCategory)
                                ({{ $product->productType->parentCategory->name }})
                            @endif
                        @else
                            No type assigned
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-dollar-sign mr-1"></i>
                        @if($product->prices && is_array($product->prices) && count($product->prices) > 0)
                            From ${{ number_format($product->getPriceForQuantity(1), 2) }}
                        @else
                            Base: ${{ number_format($product->price, 2) }}
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-cubes mr-1"></i>
                        Quantity: {{ $product->quantity }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <button onclick="openViewModal({{ $product->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        View
                    </button>
                    <button onclick="openEditModal({{ $product->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </button>
                    <button onclick="confirmDelete('{{ $product->name }}', '{{ route('admin.products.destroy', $product) }}', 'product')"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-box text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first product.</p>
                <button onclick="openCreateModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Product
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="flex justify-center">
        {{ $products->links() }}
    </div>
    @endif
</div>

<!-- Create Product Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Create New Product</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <!-- Content will be loaded dynamically -->
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-2 text-gray-600">Loading form...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Product Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Product Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="viewContent" class="p-6">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Edit Product</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="editContent" class="p-6">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
function executeScripts(container) {
    if (!container) {
        return;
    }
    const scripts = container.querySelectorAll('script');
    scripts.forEach(original => {
        const script = document.createElement('script');
        if (original.src) {
            script.src = original.src;
        } else {
            script.textContent = original.textContent;
        }
        document.body.appendChild(script);
        document.body.removeChild(script);
    });
}

// Modal functions
function openCreateModal() {
    // Show the modal first with loading state
    document.getElementById('createModal').classList.remove('hidden');
    
    // Fetch fresh create form content via AJAX to ensure fresh CSRF token
    fetch('{{ route("admin.products.create") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Update modal content with fresh form
        const modalContent = document.querySelector('#createModal .p-6');
        if (modalContent) {
            modalContent.innerHTML = html;
            
            // Execute any scripts that were loaded with the content
            const scripts = modalContent.querySelectorAll('script');
            scripts.forEach(script => {
                if (script.textContent) {
                    try {
                        eval(script.textContent);
                    } catch (error) {
                        console.error('Error executing script:', error);
                    }
                }
            });
        }
    })
    .catch(error => {
        console.error('Error loading create form:', error);
        
        // On error, show error message
        const modalContent = document.querySelector('#createModal .p-6');
        if (modalContent) {
            modalContent.innerHTML = `
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Failed to Load Form</h3>
                    <p class="text-gray-600 mb-4">There was an error loading the product form. Please try again.</p>
                    <button onclick="closeCreateModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Close
                    </button>
                </div>
            `;
        }
    });
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    
    // Clear the modal content
    const modalContent = document.querySelector('#createModal .p-6');
    if (modalContent) {
        modalContent.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Loading form...</span>
            </div>
        `;
    }
}

function openViewModal(id) {
    fetch(`/admin/products/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('viewContent').innerHTML = html;
            executeScripts(document.getElementById('viewContent'));
            document.getElementById('viewModal').classList.remove('hidden');
            executeScripts(container);
        });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function openEditModal(id) {
    fetch(`/admin/products/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('editContent').innerHTML = html;
            
            // Execute any scripts that were loaded with the content
            const scripts = document.getElementById('editContent').querySelectorAll('script');
            scripts.forEach(script => {
                if (script.textContent) {
                    try {
                        eval(script.textContent);
                    } catch (error) {
                        console.error('Error executing script in edit modal:', error);
                    }
                }
            });
            
            document.getElementById('editModal').classList.remove('hidden');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    const pricingContainer = document.getElementById('edit-pricing-tiers');
    if (pricingContainer) {
        pricingContainer.innerHTML = '';
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('bg-black')) {
        closeCreateModal();
        closeViewModal();
        closeEditModal();
    }
});
</script>
@endsection
