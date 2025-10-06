@extends('layouts.admin')

@section('title', 'Colors')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Colors</h1>
            <p class="text-gray-600">Manage your color library</p>
        </div>
        <button onclick="openCreateModal('colors')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add Color</span>
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form method="GET" action="{{ route('admin.colors.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Search colors..." 
                       value="{{ request('search') }}">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('admin.colors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-times mr-1"></i>Clear
                </a>
            </div>
        </form>
    </div>

    @if($colors->count() > 0)
        <!-- Colors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($colors as $color)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg border border-gray-200 mr-3" 
                             style="background-color: {{ $color->hex_code }}"></div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $color->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $color->category->name }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div>
                            <span class="text-gray-500">Code:</span>
                            <code class="text-gray-900">{{ $color->code }}</code>
                        </div>
                        
                        <div>
                            <span class="text-gray-500">RGB:</span>
                            <span class="text-gray-900">{{ $color->rgb_r }}, {{ $color->rgb_g }}, {{ $color->rgb_b }}</span>
                        </div>
                        
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $color->coloring_system_name }}
                            </span>
                        </div>
                        
                        @if($color->closest_association)
                        <div>
                            <span class="text-gray-500">Association:</span>
                            <span class="text-gray-900">{{ $color->closest_association }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 rounded-b-lg">
                    <div class="flex justify-between">
                        <a href="{{ route('admin.colors.show', $color) }}" 
                           class="text-blue-600 hover:text-blue-800 p-2">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.colors.edit', $color) }}" 
                           class="text-gray-600 hover:text-gray-800 p-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete('{{ $color->name }}', '{{ route('admin.colors.destroy', $color) }}', 'color')" 
                                class="text-red-600 hover:text-red-800 p-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($colors->hasPages())
        <div class="flex justify-center">
            {{ $colors->appends(request()->query())->links() }}
        </div>
        @endif
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow-sm">
            <i class="fas fa-paint-brush text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Colors Found</h3>
            @if(request()->filled('search') || request()->filled('category_id'))
                <p class="text-gray-500 mb-4">Try adjusting your filters or search terms.</p>
                <div class="space-x-2">
                    <a href="{{ route('admin.colors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </a>
                    <button onclick="openCreateModal('colors')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Add Color
                    </button>
                </div>
            @else
                <p class="text-gray-500 mb-4">Create your first color to get started.</p>
                <button onclick="openCreateModal('colors')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Add First Color
                </button>
            @endif
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