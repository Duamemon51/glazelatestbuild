@extends('layouts.admin')

@section('title','Subcategories Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Subcategories Management</h1>
            <p class="text-gray-600">Manage subcategory classifications</p>
        </div>
        <button onclick="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Subcategory
        </button>
    </div>

    <!-- Subcategories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subcategories as $subcategory)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Subcategory Icon -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="aspect-square bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                    <div class="text-center text-gray-400">
                        <i class="fas fa-layer-group text-3xl mb-2"></i>
                        <p class="text-sm">Subcategory</p>
                    </div>
                </div>
            </div>

            <!-- Subcategory Info -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $subcategory->name }}</h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                </div>

                <!-- Subcategory Details -->
                <div class="space-y-1 mb-3">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-sitemap mr-1"></i>
                        {{ $subcategory->parentCategory->name ?? 'No parent category' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-tag mr-1"></i>
                        {{ $subcategory->productTypes->count() }} product types
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar mr-1"></i>
                        Created {{ $subcategory->created_at->diffForHumans() }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <button onclick="openViewModal({{ $subcategory->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        View
                    </button>
                    <button onclick="openEditModal({{ $subcategory->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </button>
                    <button onclick="confirmDelete('{{ $subcategory->name }}', '{{ route('admin.subcategories.destroy', $subcategory) }}', 'subcategory')"
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
                <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No subcategories found</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first subcategory.</p>
                <button onclick="openCreateModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Subcategory
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($subcategories->hasPages())
    <div class="flex justify-center">
        {{ $subcategories->links() }}
    </div>
    @endif
</div>

<!-- Create Subcategory Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Create New Subcategory</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="createForm" method="POST" action="{{ route('admin.subcategories.store') }}" class="p-6 space-y-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Subcategory Name</label>
                        <input type="text" id="create_name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="create_parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
                        <select id="create_parent_category_id" name="parent_category_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Parent Category</option>
                            @foreach(\App\Models\ParentCategory::all() as $parentCategory)
                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Subcategory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Subcategory Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Subcategory Details</h3>
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

<!-- Edit Subcategory Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Edit Subcategory</h3>
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
    fetch(`/admin/subcategories/${id}`, {
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
    fetch(`/admin/subcategories/${id}/edit`, {
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
