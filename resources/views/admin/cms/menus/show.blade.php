@extends('layouts.admin')

@section('title', 'View Menu - ' . $menu->name)

@section('content')
<!-- Include SortableJS Library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $menu->name }}</h1>
            <p class="text-gray-600 mt-1">Menu Details and Items</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.cms.menus.edit', $menu) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Menu
            </a>
            <a href="{{ route('admin.cms.menus.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Menus
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Menu Information -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Menu Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <p class="text-gray-900">{{ $menu->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <p class="text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ $menu->slug }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($menu->location == 'header') bg-blue-100 text-blue-800
                        @elseif($menu->location == 'footer') bg-gray-100 text-gray-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ ucfirst($menu->location) }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    @if($menu->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <p class="text-gray-900">{{ $menu->sort_order }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Items</label>
                    <p class="text-gray-900">{{ $menu->menuItems->count() }}</p>
                </div>
            </div>
            @if($menu->description)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <p class="text-gray-900">{{ $menu->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Menu Items -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Menu Items</h3>
                <button onclick="openAddItemModal()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm flex items-center">
                    <i class="fas fa-plus mr-1"></i>
                    Add Item
                </button>
            </div>
        </div>
        <div class="p-6">
            @if($menu->menuItems->count() > 0)
                <div id="sortable-menu-items" class="space-y-4" data-menu-id="{{ $menu->id }}">
                    @foreach($menu->rootMenuItems()->active()->orderBy('sort_order')->get() as $menuItem)
                        <div class="menu-item border border-gray-200 rounded-lg p-4" data-id="{{ $menuItem->id }}" data-sort-order="{{ $menuItem->sort_order }}">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center space-x-3">
                                    <!-- Drag Handle -->
                                    <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 p-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $menuItem->title }}</h4>
                                            @if($menuItem->icon_class)
                                                <i class="{{ $menuItem->icon_class }} text-gray-400"></i>
                                            @endif
                                            @if($menuItem->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </div>
                                        @if($menuItem->description)
                                            <p class="text-gray-600 mt-1">{{ $menuItem->description }}</p>
                                        @endif
                                        <div class="mt-2 text-sm text-gray-500">
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-link mr-1"></i>
                                                URL: {{ $menuItem->full_url }}
                                            </span>
                                            @if($menuItem->target)
                                                <span class="ml-4 inline-flex items-center">
                                                    <i class="fas fa-external-link-alt mr-1"></i>
                                                    Target: {{ $menuItem->target }}
                                                </span>
                                            @endif
                                            <span class="ml-4 inline-flex items-center">
                                                <i class="fas fa-sort mr-1"></i>
                                                Order: <span class="sort-order-display">{{ $menuItem->sort_order }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <button onclick="openEditItemModal({{ $menuItem->id }})" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.cms.items.destroy', $menuItem) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this menu item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Sub-items -->
                            @if($menuItem->activeChildren()->count() > 0)
                                <div class="mt-4 pl-6 border-l-2 border-gray-200">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Sub-items:</h5>
                                    <div class="sortable-sub-items space-y-2" data-parent-id="{{ $menuItem->id }}">
                                        @foreach($menuItem->activeChildren()->orderBy('sort_order')->get() as $childItem)
                                            <div class="menu-item flex justify-between items-center p-2 bg-gray-50 rounded" data-id="{{ $childItem->id }}" data-sort-order="{{ $childItem->sort_order }}" data-parent-id="{{ $menuItem->id }}">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Drag Handle for sub-items -->
                                                    <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <span class="text-sm font-medium text-gray-900">{{ $childItem->title }}</span>
                                                        @if($childItem->description)
                                                            <span class="text-xs text-gray-500 ml-2">{{ Str::limit($childItem->description, 50) }}</span>
                                                        @endif
                                                        <span class="text-xs text-gray-400 ml-2">Order: <span class="sort-order-display">{{ $childItem->sort_order }}</span></span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button onclick="openEditItemModal({{ $childItem->id }})" class="text-blue-600 hover:text-blue-900 text-xs" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('admin.cms.items.destroy', $childItem) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this menu item?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                                    <div class="text-center py-12">
                        <i class="fas fa-list fa-3x text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No menu items found</h3>
                        <p class="text-gray-500 mb-6">Add your first menu item to get started.</p>
                        <button onclick="openAddItemModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add First Menu Item
                        </button>
                    </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Menu Item Modal -->
<div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Add Menu Item</h3>
                <button onclick="closeAddItemModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addItemForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                
                <!-- Menu Item Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Item Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="item_type" value="main" class="mr-2" checked onchange="toggleParentSelection()">
                            <span>Main Menu Item</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="item_type" value="sub" class="mr-2" onchange="toggleParentSelection()">
                            <span>Sub Menu Item</span>
                        </label>
                    </div>
                </div>

                <!-- Parent Menu Item (for sub items) -->
                <div id="parentSelection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent Menu Item</label>
                    <select name="parent_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Parent Item</option>
                        @foreach($menu->rootMenuItems()->active()->orderBy('sort_order')->get() as $menuItem)
                            <option value="{{ $menuItem->id }}">{{ $menuItem->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Menu Item Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter menu item title">
                </div>

                <!-- URL Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Link Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="url_type" value="custom" class="mr-2" checked onchange="toggleUrlType()">
                            <span>Custom URL</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="url_type" value="page" class="mr-2" onchange="toggleUrlType()">
                            <span>Page Link</span>
                        </label>
                    </div>
                </div>

                <!-- Custom URL -->
                <div id="customUrlField">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL *</label>
                    <input type="text" name="url" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., /about, https://example.com">
                </div>

                <!-- Page Selection -->
                <div id="pageField" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Page</label>
                    <select name="page_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a page</option>
                        @if(class_exists('App\Models\Page'))
                            @foreach(\App\Models\Page::published()->select('id', 'title', 'slug')->get() as $page)
                                <option value="{{ $page->id }}">{{ $page->title }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Optional description"></textarea>
                </div>

                <!-- Icon Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class</label>
                    <input type="text" name="icon_class" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., fas fa-home">
                    <p class="text-xs text-gray-500 mt-1">Font Awesome icon class (optional)</p>
                </div>

                <!-- Target -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                    <select name="target" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Same window</option>
                        <option value="_blank">New window</option>
                    </select>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddItemModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Add Menu Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Menu Item Modal -->
<div id="editItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Edit Menu Item</h3>
                <button onclick="closeEditItemModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editItemForm" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_id" id="edit_item_id">
                
                <!-- Menu Item Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Item Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="edit_item_type" value="main" class="mr-2" onchange="toggleEditParentSelection()">
                            <span>Main Menu Item</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="edit_item_type" value="sub" class="mr-2" onchange="toggleEditParentSelection()">
                            <span>Sub Menu Item</span>
                        </label>
                    </div>
                </div>

                <!-- Parent Menu Item (for sub items) -->
                <div id="editParentSelection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent Menu Item</label>
                    <select name="edit_parent_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Parent Item</option>
                        @foreach($menu->rootMenuItems()->active()->orderBy('sort_order')->get() as $menuItem)
                            <option value="{{ $menuItem->id }}">{{ $menuItem->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Menu Item Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="edit_title" id="edit_title" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter menu item title">
                </div>

                <!-- URL Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Link Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="edit_url_type" value="custom" class="mr-2" onchange="toggleEditUrlType()">
                            <span>Custom URL</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="edit_url_type" value="page" class="mr-2" onchange="toggleEditUrlType()">
                            <span>Page Link</span>
                        </label>
                    </div>
                </div>

                <!-- Custom URL -->
                <div id="editCustomUrlField">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL *</label>
                    <input type="text" name="edit_url" id="edit_url" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., /about, https://example.com">
                </div>

                <!-- Page Selection -->
                <div id="editPageField" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Page</label>
                    <select name="edit_page_id" id="edit_page_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a page</option>
                        @if(class_exists('App\Models\Page'))
                            @foreach(\App\Models\Page::published()->select('id', 'title', 'slug')->get() as $page)
                                <option value="{{ $page->id }}">{{ $page->title }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="edit_description" id="edit_description" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Optional description"></textarea>
                </div>

                <!-- Icon Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class</label>
                    <input type="text" name="edit_icon_class" id="edit_icon_class" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., fas fa-home">
                    <p class="text-xs text-gray-500 mt-1">Font Awesome icon class (optional)</p>
                </div>

                <!-- Target -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                    <select name="edit_target" id="edit_target" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Same window</option>
                        <option value="_blank">New window</option>
                    </select>
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="edit_is_active" id="edit_is_active" class="mr-2">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditItemModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Menu Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Drag and Drop Styles */
.sortable-ghost {
    opacity: 0.5;
    background: #f3f4f6;
}

.sortable-chosen {
    background: #dbeafe;
    border-color: #3b82f6;
}

.sortable-drag {
    background: white;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transform: rotate(5deg);
}

.drag-handle:hover {
    color: #4b5563;
}

.menu-item.dragging {
    opacity: 0.8;
}

.drop-zone {
    border: 2px dashed #d1d5db;
    background: #f9fafb;
    min-height: 40px;
    border-radius: 6px;
    margin: 8px 0;
}

.drop-zone.active {
    border-color: #3b82f6;
    background: #eff6ff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Initialize sortable for main menu items
    const mainSortable = document.getElementById('sortable-menu-items');
    if (mainSortable) {
        new Sortable(mainSortable, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onStart: function(evt) {
                evt.item.classList.add('dragging');
            },
            onEnd: function(evt) {
                evt.item.classList.remove('dragging');
                updateMenuItemOrder();
            }
        });
    }

    // Initialize sortable for sub-items
    document.querySelectorAll('.sortable-sub-items').forEach(function(subContainer) {
        new Sortable(subContainer, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onStart: function(evt) {
                evt.item.classList.add('dragging');
            },
            onEnd: function(evt) {
                evt.item.classList.remove('dragging');
                updateMenuItemOrder();
            }
        });
    });

    // Handle add item form submission
    document.getElementById('addItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        // Validate required fields
        if (!data.title.trim()) {
            showNotification('Title is required', 'error');
            return;
        }
        
        if (data.url_type === 'custom' && !data.url.trim()) {
            showNotification('URL is required', 'error');
            return;
        }
        
        if (data.url_type === 'page' && !data.page_id) {
            showNotification('Please select a page', 'error');
            return;
        }
        
        if (data.item_type === 'sub' && !data.parent_id) {
            showNotification('Please select a parent menu item', 'error');
            return;
        }

        // Send AJAX request to create menu item
        fetch('/admin/cms/menus/{{ $menu->id }}/items', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Menu item created successfully!', 'success');
                closeAddItemModal();
                // Reload the page to show the new item
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error creating menu item', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error creating menu item', 'error');
        });
    });

    // Handle edit item form submission
    document.getElementById('editItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        const itemId = data.item_id;
        
        // Validate required fields
        if (!data.edit_title.trim()) {
            showNotification('Title is required', 'error');
            return;
        }
        
        if (data.edit_url_type === 'custom' && !data.edit_url.trim()) {
            showNotification('URL is required', 'error');
            return;
        }
        
        if (data.edit_url_type === 'page' && !data.edit_page_id) {
            showNotification('Please select a page', 'error');
            return;
        }
        
        if (data.edit_item_type === 'sub' && !data.edit_parent_id) {
            showNotification('Please select a parent menu item', 'error');
            return;
        }

        // Prepare data for submission
        const submitData = {
            title: data.edit_title,
            description: data.edit_description || '',
            icon_class: data.edit_icon_class || '',
            target: data.edit_target || '_self',
            is_active: data.edit_is_active ? 1 : 0,
            url_type: data.edit_url_type,
            item_type: data.edit_item_type,
            url: data.edit_url_type === 'custom' ? (data.edit_url || '') : null,
            page_id: data.edit_url_type === 'page' ? (data.edit_page_id || null) : null,
            parent_id: data.edit_item_type === 'sub' ? (data.edit_parent_id || null) : null
        };

        // Send AJAX request to update menu item
        fetch(`/admin/cms/items/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(submitData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            
            // Get the response text first to see what we're actually getting
            return response.text().then(text => {
                console.log('Raw response:', text);
                
                try {
                    const data = JSON.parse(text);
                    if (!response.ok) {
                        throw new Error(data.message || `HTTP error! status: ${response.status}`);
                    }
                    return data;
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    throw new Error(`Invalid JSON response: ${text}`);
                }
            });
        })
        .then(data => {
            console.log('Parsed response data:', data);
            if (data.success) {
                showNotification('Menu item updated successfully!', 'success');
                closeEditItemModal();
                // Reload the page to show the updated item
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                console.error('Server returned success: false', data);
                showNotification(data.message || 'Error updating menu item', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            console.error('Submit data:', submitData);
            showNotification('Error updating menu item: ' + error.message, 'error');
        });
    });

    function updateMenuItemOrder() {
        const items = [];
        
        // Process main menu items
        document.querySelectorAll('#sortable-menu-items > .menu-item').forEach(function(item, index) {
            const itemId = item.getAttribute('data-id');
            items.push({
                id: parseInt(itemId),
                sort_order: index,
                parent_id: null
            });
            
            // Process sub-items
            item.querySelectorAll('.sortable-sub-items .menu-item').forEach(function(subItem, subIndex) {
                const subItemId = subItem.getAttribute('data-id');
                const parentId = subItem.getAttribute('data-parent-id');
                items.push({
                    id: parseInt(subItemId),
                    sort_order: subIndex,
                    parent_id: parseInt(parentId)
                });
            });
        });

        // Send AJAX request to update order
        fetch('/admin/cms/menu-items/reorder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                items: items
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the displayed sort orders
                updateDisplayedSortOrders();
                showNotification('Menu items reordered successfully!', 'success');
            } else {
                showNotification('Error reordering menu items', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error reordering menu items', 'error');
        });
    }

    function updateDisplayedSortOrders() {
        // Update main items
        document.querySelectorAll('#sortable-menu-items > .menu-item').forEach(function(item, index) {
            const sortOrderDisplay = item.querySelector('.sort-order-display');
            if (sortOrderDisplay) {
                sortOrderDisplay.textContent = index;
            }
        });

        // Update sub-items
        document.querySelectorAll('.sortable-sub-items').forEach(function(container) {
            container.querySelectorAll('.menu-item').forEach(function(subItem, subIndex) {
                const sortOrderDisplay = subItem.querySelector('.sort-order-display');
                if (sortOrderDisplay) {
                    sortOrderDisplay.textContent = subIndex;
                }
            });
        });
    }

    function showNotification(message, type = 'success') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});

// Modal functions
function openAddItemModal() {
    document.getElementById('addItemModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddItemModal() {
    document.getElementById('addItemModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('addItemForm').reset();
    toggleParentSelection();
    toggleUrlType();
}

function openEditItemModal(itemId) {
    // Fetch menu item data
    fetch(`/admin/cms/items/${itemId}/edit`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON');
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        if (data.success) {
            populateEditForm(data.menuItem);
            document.getElementById('editItemModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            showNotification('Error loading menu item data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error loading menu item data: ' + error.message, 'error');
    });
}

function closeEditItemModal() {
    document.getElementById('editItemModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('editItemForm').reset();
    toggleEditParentSelection();
    toggleEditUrlType();
}

function populateEditForm(menuItem) {
    document.getElementById('edit_item_id').value = menuItem.id;
    document.getElementById('edit_title').value = menuItem.title || '';
    document.getElementById('edit_description').value = menuItem.description || '';
    document.getElementById('edit_icon_class').value = menuItem.icon_class || '';
    document.getElementById('edit_target').value = menuItem.target || '';
    document.getElementById('edit_is_active').checked = menuItem.is_active;
    
    // Set item type
    const itemType = menuItem.parent_id ? 'sub' : 'main';
    document.querySelector(`input[name="edit_item_type"][value="${itemType}"]`).checked = true;
    
    // Set parent if sub item
    if (menuItem.parent_id) {
        document.querySelector('select[name="edit_parent_id"]').value = menuItem.parent_id;
    }
    
    // Set URL type and values
    if (menuItem.page_id) {
        document.querySelector('input[name="edit_url_type"][value="page"]').checked = true;
        document.getElementById('edit_page_id').value = menuItem.page_id;
    } else {
        document.querySelector('input[name="edit_url_type"][value="custom"]').checked = true;
        document.getElementById('edit_url').value = menuItem.url || '';
    }
    
    // Trigger field visibility updates
    toggleEditParentSelection();
    toggleEditUrlType();
}

function toggleParentSelection() {
    const itemType = document.querySelector('input[name="item_type"]:checked').value;
    const parentSelection = document.getElementById('parentSelection');
    
    if (itemType === 'sub') {
        parentSelection.classList.remove('hidden');
        parentSelection.querySelector('select').required = true;
    } else {
        parentSelection.classList.add('hidden');
        parentSelection.querySelector('select').required = false;
    }
}

function toggleEditParentSelection() {
    const checkedRadio = document.querySelector('input[name="edit_item_type"]:checked');
    if (!checkedRadio) return; // Exit if no radio button is checked
    
    const itemType = checkedRadio.value;
    const parentSelection = document.getElementById('editParentSelection');
    
    if (itemType === 'sub') {
        parentSelection.classList.remove('hidden');
        parentSelection.querySelector('select').required = true;
    } else {
        parentSelection.classList.add('hidden');
        parentSelection.querySelector('select').required = false;
    }
}

function toggleUrlType() {
    const urlType = document.querySelector('input[name="url_type"]:checked').value;
    const customUrlField = document.getElementById('customUrlField');
    const pageField = document.getElementById('pageField');
    
    if (urlType === 'custom') {
        customUrlField.classList.remove('hidden');
        pageField.classList.add('hidden');
        customUrlField.querySelector('input').required = true;
        pageField.querySelector('select').required = false;
    } else {
        customUrlField.classList.add('hidden');
        pageField.classList.remove('hidden');
        customUrlField.querySelector('input').required = false;
        pageField.querySelector('select').required = true;
    }
}

function toggleEditUrlType() {
    const checkedRadio = document.querySelector('input[name="edit_url_type"]:checked');
    if (!checkedRadio) return; // Exit if no radio button is checked
    
    const urlType = checkedRadio.value;
    const customUrlField = document.getElementById('editCustomUrlField');
    const pageField = document.getElementById('editPageField');
    
    if (urlType === 'custom') {
        customUrlField.classList.remove('hidden');
        pageField.classList.add('hidden');
        customUrlField.querySelector('input').required = true;
        pageField.querySelector('select').required = false;
    } else {
        customUrlField.classList.add('hidden');
        pageField.classList.remove('hidden');
        customUrlField.querySelector('input').required = false;
        pageField.querySelector('select').required = true;
    }
}

// Close modals when clicking outside
document.getElementById('addItemModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddItemModal();
    }
});

document.getElementById('editItemModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditItemModal();
    }
});
</script>
@endsection