@extends('layouts.admin')

@section('title','Product Types Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Product Types Management</h1>
            <p class="text-gray-600">Manage product type classifications</p>
        </div>
        <button onclick="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Product Type
        </button>
    </div>

    <!-- Product Types Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($types as $type)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Product Type Image -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="aspect-square bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                    @if($type->image)
                        <img src="{{ asset('storage/' . $type->image) }}" alt="{{ $type->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="text-center text-gray-400">
                            <i class="fas fa-tag text-3xl mb-2"></i>
                            <p class="text-sm">No image</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Type Info -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $type->name }}</h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                </div>

                <!-- Product Type Details -->
                <div class="space-y-1 mb-3">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-layer-group mr-1"></i>
                        {{ $type->subcategory->name ?? 'No subcategory' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-sitemap mr-1"></i>
                        {{ $type->subcategory->parentCategory->name ?? 'No parent category' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar mr-1"></i>
                        Created {{ $type->created_at->diffForHumans() }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <button onclick="openViewModal({{ $type->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        View
                    </button>
                    <button onclick="openEditModal({{ $type->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </button>
                    <button onclick="confirmDelete('{{ $type->name }}', '{{ route('admin.product-types.destroy', $type) }}', 'product type')"
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
                <i class="fas fa-tag text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No product types found</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first product type.</p>
                <button onclick="openCreateModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Product Type
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($types->hasPages())
    <div class="flex justify-center">
        {{ $types->links() }}
    </div>
    @endif
</div>

<!-- Create Product Type Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Create New Product Type</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="createForm" method="POST" action="{{ route('admin.product-types.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Product Type Name</label>
                        <input type="text" id="create_name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="create_subcategory_id" class="block text-sm font-medium text-gray-700 mb-2">Subcategory</label>
                        <select id="create_subcategory_id" name="subcategory_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Subcategory</option>
                            @foreach(\App\Models\Subcategory::with('parentCategory')->get() as $subcategory)
                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }} ({{ $subcategory->parentCategory->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="create_image" class="block text-sm font-medium text-gray-700 mb-2">Product Type Image</label>
                        <input type="file" id="create_image" name="image" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Product Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Product Type Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Product Type Details</h3>
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

<!-- Edit Product Type Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Edit Product Type</h3>
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
// Modal functions
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openViewModal(id) {
    fetch(`/admin/product-types/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('viewContent').innerHTML = html;
            document.getElementById('viewModal').classList.remove('hidden');
        });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function openEditModal(id) {
    fetch(`/admin/product-types/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('editContent').innerHTML = html;
            document.getElementById('editModal').classList.remove('hidden');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
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
