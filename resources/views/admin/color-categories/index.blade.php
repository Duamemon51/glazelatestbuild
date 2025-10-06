@extends('layouts.admin')

@section('title', 'Color Categories')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Color Categories</h1>
            <p class="text-gray-600">Manage your color category system</p>
        </div>
        <button onclick="openCreateModal('color-categories')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add Category</span>
        </button>
    </div>

    @if($categories->count() > 0)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Colors</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $category->description ? Str::limit($category->description, 60) : 'No description' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $category->colors_count }} colors
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.color-categories.show', $category) }}"
                                       class="text-blue-600 hover:text-blue-900 p-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.color-categories.edit', $category) }}"
                                       class="text-gray-600 hover:text-gray-900 p-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('{{ $category->name }}', '{{ route('admin.color-categories.destroy', $category) }}', 'category')"
                                            class="text-red-600 hover:text-red-900 p-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
            <div class="bg-white px-6 py-3 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow-sm">
            <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Color Categories Yet</h3>
            <p class="text-gray-500 mb-4">Create your first color category to get started.</p>
            <button onclick="openCreateModal('color-categories')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Add First Category
            </button>
        </div>
    @endif
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
        case 'colors':
            title = 'Create New Color';
            url = '{{ route("admin.colors.create") }}';
            break;
        case 'color-categories':
            title = 'Create New Color Category';
            url = '{{ route("admin.color-categories.create") }}';
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