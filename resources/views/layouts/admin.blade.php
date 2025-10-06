<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: margin-left 0.3s ease-in-out; }
        .main-content-transition { transition: margin-left 0.3s ease-in-out; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
    <!-- Mobile menu overlay -->
    <div x-show="mobileMenuOpen" x-cloak
         class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
         @click="mobileMenuOpen = false"></div>

    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('admin-components.sidebar-modern')

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navigation -->
            @include('admin-components.navbar-modern')

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modal container for dynamic modals -->
    <div id="modal-container"></div>

    <!-- Notification container for success/error messages -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        Confirm Deletion
                    </h3>
                    <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <p id="deleteMessage" class="text-gray-600 mb-6">
                        Are you sure you want to delete this item? This action cannot be undone.
                    </p>
                    
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeDeleteModal()" 
                                class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button id="confirmDeleteBtn" onclick="performDelete()" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                            <i class="fas fa-trash mr-2"></i>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Global modal functions
        window.showModal = function(content) {
            const modalContainer = document.getElementById('modal-container');
            modalContainer.innerHTML = content;
        };

        window.hideModal = function() {
            const modalContainer = document.getElementById('modal-container');
            modalContainer.innerHTML = '';
        };

        // Global notification function
        window.showNotification = function(message, type = 'info', duration = 4000) {
            const container = document.getElementById('notification-container');
            if (!container) return;
            
            const notification = document.createElement('div');
            const baseClasses = 'px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out mb-3 max-w-md';
            
            let typeClasses = '';
            switch(type) {
                case 'success':
                    typeClasses = 'bg-green-500 text-white border-l-4 border-green-700';
                    break;
                case 'error':
                    typeClasses = 'bg-red-500 text-white border-l-4 border-red-700';
                    break;
                case 'warning':
                    typeClasses = 'bg-yellow-500 text-white border-l-4 border-yellow-700';
                    break;
                default:
                    typeClasses = 'bg-blue-500 text-white border-l-4 border-blue-700';
            }
            
            notification.className = baseClasses + ' ' + typeClasses + ' translate-x-full opacity-0';
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
                notification.classList.add('translate-x-0', 'opacity-100');
            }, 100);
            
            // Auto remove after duration
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, duration);
        };

        // Global delete confirmation functions
        window.deleteItemData = null; // Store delete data temporarily
        
        window.confirmDelete = function(itemName, deleteUrl, itemType = 'item') {
            window.deleteItemData = { url: deleteUrl, type: itemType };
            document.getElementById('deleteMessage').textContent = 
                `Are you sure you want to delete "${itemName}"? This ${itemType} and all related data will be permanently removed.`;
            document.getElementById('deleteModal').classList.remove('hidden');
        };
        
        window.closeDeleteModal = function() {
            document.getElementById('deleteModal').classList.add('hidden');
            window.deleteItemData = null;
        };
        
        window.performDelete = function() {
            if (!window.deleteItemData) return;
            
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const originalHtml = confirmBtn.innerHTML;
            
            // Show loading state
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
            confirmBtn.disabled = true;
            
            fetch(window.deleteItemData.url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDeleteModal();
                    showNotification('✅ ' + data.message, 'success');
                    // Refresh page to update the list
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showNotification('❌ ' + (data.message || 'Failed to delete item'), 'error');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                showNotification('❌ An error occurred while deleting', 'error');
            })
            .finally(() => {
                // Restore button state
                confirmBtn.innerHTML = originalHtml;
                confirmBtn.disabled = false;
            });
        };
        
        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
