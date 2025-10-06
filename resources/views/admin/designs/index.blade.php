@extends('layouts.admin')

@section('title','Designs Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Designs Management</h1>
            <p class="text-gray-600">Manage your design templates</p>
        </div>
        <button onclick="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add New Design
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex">
            <i class="fas fa-check-circle text-green-400"></i>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Designs Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($designs as $design)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Design Preview -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="aspect-square bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                    @if($design->preview_img)
                        <img src="{{ asset('storage/' . $design->preview_img) }}" alt="{{ $design->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="text-center text-gray-400">
                            <i class="fas fa-palette text-3xl mb-2"></i>
                            <p class="text-sm">No preview</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Design Info -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $design->name }}</h3>
                    @if($design->is_unique)
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                        Unique
                    </span>
                    @else
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                        Standard
                    </span>
                    @endif
                </div>

                <!-- Design Details -->
                <div class="space-y-1 mb-3">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-images mr-1"></i>
                        Images: {{ collect([$design->front_image, $design->back_image, $design->left_image, $design->right_image])->filter()->count() }}/4
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-box mr-1"></i>
                        Products: {{ $design->products->count() }}
                    </p>
                    @if($design->products->count() > 0)
                    <div class="text-xs text-gray-500 mt-1">
                        @foreach($design->products->take(2) as $product)
                        <span class="inline-block bg-gray-100 rounded px-2 py-1 mr-1 mb-1">{{ $product->name }}</span>
                        @endforeach
                        @if($design->products->count() > 2)
                        <span class="text-gray-400">+{{ $design->products->count() - 2 }} more</span>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <button onclick="openEditModal({{ $design->id }})"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-purple-100 text-purple-700 text-sm font-medium rounded-lg hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </button>
                    <form action="{{ route('admin.designs.destroy', $design->id) }}" method="POST" class="flex-1"
                          onsubmit="return confirm('Are you sure you want to delete this design?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-trash mr-1"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-palette text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No designs found</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first design template.</p>
                <button onclick="openCreateModal()"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Design
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($designs->hasPages())
    <div class="flex justify-center">
        {{ $designs->links() }}
    </div>
    @endif
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Create New Design</h3>
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

<!-- Edit Design Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Design</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="editModalContent">
                <!-- Edit form will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Modal functions
function openCreateModal() {
    fetch('{{ route("admin.designs.create") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('modalContent').innerHTML = html;
        document.getElementById('createModal').classList.remove('hidden');
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

function openEditModal(designId) {
    fetch(`{{ url('admin/designs') }}/${designId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('editModalContent').innerHTML = html;
        document.getElementById('editModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error loading edit modal content:', error);
        alert('Error loading edit form. Please try again.');
    });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModalContent').innerHTML = '';
}

// Close modal when clicking outside
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endsection
