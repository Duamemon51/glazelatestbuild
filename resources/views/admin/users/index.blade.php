@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
            <p class="text-gray-600">Manage system users</p>
        </div>
        <div class="flex gap-2">
            <button onclick="refreshPage()"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($users as $user)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-lg"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-500 text-sm">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Joined {{ $user->created_at->format('M j, Y') }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            onclick="showUser({{ $user->id }})">
                        <i class="fas fa-eye mr-1"></i> View
                    </button>
                    <button class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                            onclick="editUser({{ $user->id }})">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Users Found</h3>
                <p class="text-gray-500">There are no users in the system yet.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $users->links() }}
    </div>
    @endif
        </main>
    </div>
</div>

<!-- Modals -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3" id="userModalContent">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>

<script>
// Show user details modal
function showUser(userId) {
    fetch(`{{ url('admin/users') }}/${userId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('userModalContent').innerHTML = html;
        document.getElementById('userModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error loading user details:', error);
        alert('Error loading user details. Please try again.');
    });
}

// Edit user modal
function editUser(userId) {
    fetch(`{{ url('admin/users') }}/${userId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('userModalContent').innerHTML = html;
        document.getElementById('userModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error loading edit form:', error);
        alert('Error loading edit form. Please try again.');
    });
}

// Close modal
function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('userModal').addEventListener('click', function(e) {
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