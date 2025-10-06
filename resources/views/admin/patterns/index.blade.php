@extends('layouts.admin')

@section('title','Patterns Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Patterns Management</h1>
            <p class="text-gray-600">Manage SVG patterns for your products</p>
        </div>
        <button onclick="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add New Pattern
        </button>
    </div>

    <!-- Patterns Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($patterns as $pattern)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Pattern Preview -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="aspect-square bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                    @if($pattern->svg_content)
                        <div class="w-full h-full flex items-center justify-center">
                            {!! $pattern->svg_content !!}
                        </div>
                    @else
                        <div class="text-center text-gray-400">
                            <i class="fas fa-shapes text-3xl mb-2"></i>
                            <p class="text-sm">No preview</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pattern Info -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $pattern->name }}</h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $pattern->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $pattern->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <!-- Products Count -->
                <div class="mb-3">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-box mr-1"></i>
                        {{ $pattern->products->count() }} product{{ $pattern->products->count() !== 1 ? 's' : '' }} assigned
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <button onclick="openViewModal({{ $pattern->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        View
                    </button>
                    <button onclick="openEditModal({{ $pattern->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-shapes text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No patterns found</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first pattern.</p>
                <button onclick="openCreateModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Pattern
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($patterns->hasPages())
    <div class="flex justify-center">
        {{ $patterns->links() }}
    </div>
    @endif
</div>

<!-- Create Pattern Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Create New Pattern</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="createForm" method="POST" action="{{ route('admin.patterns.store') }}" class="p-6 space-y-6">
                @csrf
                <div>
                    <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Pattern Name</label>
                    <input type="text" id="create_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="create_svg_content" class="block text-sm font-medium text-gray-700 mb-2">SVG Content</label>
                    <textarea id="create_svg_content" name="svg_content" rows="8" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"
                              placeholder="<svg>...</svg>"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign to Products</label>
                    <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-3">
                        @foreach(\App\Models\Product::all() as $product)
                        <label class="flex items-center space-x-2 py-1">
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">{{ $product->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="create_is_active" name="is_active" value="1" checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="create_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Pattern
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Pattern Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Pattern Details</h3>
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

<!-- Edit Pattern Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Edit Pattern</h3>
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
    fetch(`/admin/patterns/${id}`, {
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
    fetch(`/admin/patterns/${id}/edit`, {
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