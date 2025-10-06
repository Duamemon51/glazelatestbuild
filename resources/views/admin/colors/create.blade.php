@if(request()->ajax())
    <form id="createModalForm" action="{{ route('admin.colors.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-4">
                <div>
                    <label for="color_category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select id="color_category_id" 
                            name="color_category_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Color Name *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="e.g., Ocean Blue"
                           required>
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Color Code *</label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="e.g., PANTONE 286 C"
                           required>
                </div>

                <div>
                    <label for="coloring_system" class="block text-sm font-medium text-gray-700 mb-1">Coloring System *</label>
                    <select id="coloring_system" 
                            name="coloring_system" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Select System</option>
                        @foreach($coloringSystems as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">RGB Values *</label>
                    <div class="grid grid-cols-3 gap-2">
                        <input type="number" 
                               id="rgb_r" 
                               name="rgb_r" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="R (0-255)"
                               min="0" max="255"
                               onchange="updateColorPreview()"
                               required>
                        <input type="number" 
                               id="rgb_g" 
                               name="rgb_g" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="G (0-255)"
                               min="0" max="255"
                               onchange="updateColorPreview()"
                               required>
                        <input type="number" 
                               id="rgb_b" 
                               name="rgb_b" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="B (0-255)"
                               min="0" max="255"
                               onchange="updateColorPreview()"
                               required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color Preview</label>
                    <div id="colorPreview" class="w-full h-16 border border-gray-300 rounded-lg bg-gray-100"></div>
                </div>

                <div>
                    <label for="closest_association" class="block text-sm font-medium text-gray-700 mb-1">Closest Association</label>
                    <input type="text" 
                           id="closest_association" 
                           name="closest_association" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="e.g., Sky, Ocean, Navy">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Optional description"></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
            <button type="button" onclick="closeCreateModal()" 
                    class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                Cancel
            </button>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Create Color
            </button>
        </div>
    </form>

    <script>
    function updateColorPreview() {
        const r = document.getElementById('rgb_r')?.value || 0;
        const g = document.getElementById('rgb_g')?.value || 0;
        const b = document.getElementById('rgb_b')?.value || 0;
        const preview = document.getElementById('colorPreview');
        if (preview) {
            preview.style.backgroundColor = `rgb(${r}, ${g}, ${b})`;
        }
    }

    // Wait for DOM elements to be available
    setTimeout(function() {
        const form = document.getElementById('createModalForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
                submitBtn.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeCreateModal();
                // Show success notification
                showNotification('✅ ' + data.message, 'success');
                // Refresh page after short delay to show server-side success message
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('❌ ' + (data.message || 'An error occurred'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ An error occurred while creating the color', 'error');
        })
        .finally(() => {
            // Restore button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
            });
        }
    }, 100); // Wait 100ms for DOM elements to be available
    </script>
@else
    @extends('layouts.admin')

    @section('title', 'Create Color')

    @section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Create Color</h1>
                <p class="text-gray-600">Add a new color to your library</p>
            </div>

            <form action="{{ route('admin.colors.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label for="color_category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select id="color_category_id" 
                                    name="color_category_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color_category_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('color_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('color_category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Color Name *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Ocean Blue"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Color Code *</label>
                            <input type="text" 
                                   id="code" 
                                   name="code" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror" 
                                   value="{{ old('code') }}"
                                   placeholder="e.g., PANTONE 286 C, PMS 186 C, RAL 3020"
                                   required>
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="coloring_system" class="block text-sm font-medium text-gray-700 mb-2">Coloring System *</label>
                            <select id="coloring_system" 
                                    name="coloring_system" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('coloring_system') border-red-500 @enderror"
                                    required>
                                <option value="">Select System</option>
                                @foreach($coloringSystems as $key => $label)
                                    <option value="{{ $key }}" {{ old('coloring_system') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('coloring_system')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">RGB Values *</label>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label for="rgb_r" class="block text-xs text-gray-500 mb-1">Red (0-255)</label>
                                    <input type="number" 
                                           id="rgb_r" 
                                           name="rgb_r" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rgb_r') border-red-500 @enderror" 
                                           value="{{ old('rgb_r', 0) }}"
                                           min="0" max="255"
                                           onchange="updateColorPreview()"
                                           required>
                                    @error('rgb_r')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="rgb_g" class="block text-xs text-gray-500 mb-1">Green (0-255)</label>
                                    <input type="number" 
                                           id="rgb_g" 
                                           name="rgb_g" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rgb_g') border-red-500 @enderror" 
                                           value="{{ old('rgb_g', 0) }}"
                                           min="0" max="255"
                                           onchange="updateColorPreview()"
                                           required>
                                    @error('rgb_g')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="rgb_b" class="block text-xs text-gray-500 mb-1">Blue (0-255)</label>
                                    <input type="number" 
                                           id="rgb_b" 
                                           name="rgb_b" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rgb_b') border-red-500 @enderror" 
                                           value="{{ old('rgb_b', 0) }}"
                                           min="0" max="255"
                                           onchange="updateColorPreview()"
                                           required>
                                    @error('rgb_b')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color Preview</label>
                            <div id="colorPreview" 
                                 class="w-full h-24 border border-gray-300 rounded-lg bg-gray-100 flex items-center justify-center">
                                <span class="text-gray-500">Enter RGB values</span>
                            </div>
                        </div>

                        <div>
                            <label for="closest_association" class="block text-sm font-medium text-gray-700 mb-2">Closest Association</label>
                            <input type="text" 
                                   id="closest_association" 
                                   name="closest_association" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('closest_association') border-red-500 @enderror" 
                                   value="{{ old('closest_association') }}"
                                   placeholder="e.g., Sky, Ocean, Navy">
                            @error('closest_association')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Optional description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.colors.index') }}" 
                       class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Colors
                    </a>
                    <div class="space-x-3">
                        <a href="{{ route('admin.colors.index') }}" 
                           class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Create Color
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    function updateColorPreview() {
        const r = document.getElementById('rgb_r').value || 0;
        const g = document.getElementById('rgb_g').value || 0;
        const b = document.getElementById('rgb_b').value || 0;
        const preview = document.getElementById('colorPreview');
        
        if (r > 0 || g > 0 || b > 0) {
            preview.style.backgroundColor = `rgb(${r}, ${g}, ${b})`;
            preview.innerHTML = '';
        } else {
            preview.style.backgroundColor = '';
            preview.innerHTML = '<span class="text-gray-500">Enter RGB values</span>';
        }
    }

    // Initialize color preview on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateColorPreview();
    });
    </script>
    @endsection
@endif