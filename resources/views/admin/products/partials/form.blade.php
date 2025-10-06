@php
    $product = $product ?? null;
    $productTypes = $productTypes ?? collect();
    $patterns = $patterns ?? collect();
    $parentCategories = $parentCategories ?? collect();
    $subcategories = $subcategories ?? collect();
    $formId = $formId ?? null;
    $formClass = $formClass ?? 'space-y-6';
    $method = strtoupper($method ?? 'POST');
    $action = $action ?? '#';
    $fieldPrefix = $fieldPrefix ?? 'product';
    $showCancel = $showCancel ?? false;
    $cancelLabel = $cancelLabel ?? 'Cancel';
    $cancelHandler = $cancelHandler ?? null;
    $submitLabel = $submitLabel ?? ($method === 'POST' ? 'Create Product' : 'Save Product');
    $submitButtonClasses = $submitButtonClasses ?? 'px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2';
    $cancelButtonClasses = $cancelButtonClasses ?? 'px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2';
    $footerClasses = $footerClasses ?? 'flex justify-end space-x-3 pt-4 border-t border-gray-200';
    $useOldInput = $useOldInput ?? true;
    $nameValue = $useOldInput ? old('name', $product->name ?? '') : ($product->name ?? '');
    $priceValue = $useOldInput ? old('price', $product->price ?? '') : ($product->price ?? '');
    $quantityValue = $useOldInput ? old('quantity', $product->quantity ?? 0) : ($product->quantity ?? 0);
    $detailsValue = $useOldInput ? old('details', $product->details ?? '') : ($product->details ?? '');
    $selectedProductType = $useOldInput ? old('product_type_id', $product->product_type_id ?? '') : ($product->product_type_id ?? '');
    $selectedSubcategory = $useOldInput ? old('subcategory_id', optional(optional($product)->productType)->subcategory_id ?? '') : (optional(optional($product)->productType)->subcategory_id ?? '');
    $selectedParentCategory = $useOldInput ? old('parent_category_id', optional(optional(optional($product)->productType)->subcategory)->parent_category_id ?? '') : (optional(optional(optional($product)->productType)->subcategory)->parent_category_id ?? '');
    
    // Handle pattern selection
    if ($useOldInput) {
        $selectedPatterns = collect(old('patterns', $selectedPatternIds ?? ($product ? $product->patterns->pluck('id')->all() : [])))->map(fn ($id) => (int) $id)->all();
    } else {
        $selectedPatterns = collect($selectedPatternIds ?? ($product ? $product->patterns->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
    }
    
    // Handle design selection
    if ($useOldInput) {
        $selectedDesigns = collect(old('designs', $selectedDesignIds ?? ($product ? $product->designs->pluck('id')->all() : [])))->map(fn ($id) => (int) $id)->all();
    } else {
        $selectedDesigns = collect($selectedDesignIds ?? ($product ? $product->designs->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
    }
    
    $modelExtensions = config('uploads.product_model_extensions', []);
    $modelAccept = collect($modelExtensions)->map(fn ($ext) => '.' . $ext)->implode(',');
    $previewableExtensions = config('uploads.previewable_model_extensions', []);

    $pricingInput = $useOldInput ? old('prices') : null;
    if (is_null($pricingInput)) {
        if ($product && $product->prices && is_array($product->prices)) {
            $pricingInput = $product->prices;
        } else {
            $pricingInput = [];
        }
    }
    if (is_string($pricingInput)) {
        $decoded = json_decode($pricingInput, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $pricingInput = $decoded;
        }
    }
    $pricingRows = collect($pricingInput)->map(function ($tier) {
        return [
            'min_quantity' => $tier['min_quantity'] ?? ($tier['quantity'] ?? ''),
            'price' => $tier['price'] ?? '',
        ];
    })->filter(function ($tier) {
        return $tier['min_quantity'] !== '' || $tier['price'] !== '';
    })->values()->all();
    if (empty($pricingRows)) {
        $pricingRows[] = ['min_quantity' => '', 'price' => ''];
    }

    $pricingContainerId = $pricingContainerId ?? ($fieldPrefix . '-pricing-tiers');
    $filteredSubcategories = $selectedParentCategory ? $subcategories->where('parent_category_id', (int) $selectedParentCategory) : collect();
    $filteredProductTypes = $selectedSubcategory ? $productTypes->where('subcategory_id', (int) $selectedSubcategory) : collect();
@endphp

    <form id="{{ $formId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data" autocomplete="off" class="{{ $formClass }}">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label for="{{ $fieldPrefix }}_name" class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                <input type="text" id="{{ $fieldPrefix }}_name" name="name" value="{{ $nameValue }}" autocomplete="off" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="{{ $fieldPrefix }}_parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">Main Category</label>
        <select id="{{ $fieldPrefix }}_parent_category_id" name="parent_category_id" autocomplete="off" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            data-field-prefix="{{ $fieldPrefix }}" data-initial="{{ $selectedParentCategory }}">
                    <option value="">Select Main Category</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ (string) $selectedParentCategory === (string) $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="{{ $fieldPrefix }}_subcategory_id" class="block text-sm font-medium text-gray-700 mb-2">Subcategory</label>
        <select id="{{ $fieldPrefix }}_subcategory_id" name="subcategory_id" autocomplete="off" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            data-field-prefix="{{ $fieldPrefix }}" data-initial="{{ $selectedSubcategory }}">
                    <option value="">Select Subcategory</option>
                    @foreach($filteredSubcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ (string) $selectedSubcategory === (string) $subcategory->id ? 'selected' : '' }}>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
                @error('subcategory_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="{{ $fieldPrefix }}_product_type_id" class="block text-sm font-medium text-gray-700 mb-2">Product Type</label>
        <select id="{{ $fieldPrefix }}_product_type_id" name="product_type_id" autocomplete="off" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            data-field-prefix="{{ $fieldPrefix }}" data-initial="{{ $selectedProductType }}">
                    <option value="">Select Product Type</option>
                    @foreach($filteredProductTypes as $type)
                        <option value="{{ $type->id }}" {{ (string) $selectedProductType === (string) $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_type_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="{{ $fieldPrefix }}_price" class="block text-sm font-medium text-gray-700 mb-2">Base Price ($)</label>
                <input type="number" id="{{ $fieldPrefix }}_price" name="price" value="{{ $priceValue }}" step="0.01" min="0" autocomplete="off" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tiered Pricing (Optional)</label>
                <div id="{{ $pricingContainerId }}" class="space-y-2">
                    @foreach($pricingRows as $index => $tier)
                        @php $rowIndex = $index + 1; @endphp
                        <div class="flex items-center space-x-2" id="{{ $fieldPrefix }}-tier-{{ $rowIndex }}" data-pricing-row="true">
                            <input type="number" name="prices[{{ $rowIndex }}][min_quantity]" value="{{ $tier['min_quantity'] }}" placeholder="Min Qty" min="1"
                                   class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                            <input type="number" name="prices[{{ $rowIndex }}][price]" value="{{ $tier['price'] }}" placeholder="Price" step="0.01" min="0"
                                   class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                            <button type="button" class="text-red-500 hover:text-red-700" onclick="remove{{ ucfirst($fieldPrefix) }}PricingTier({{ $rowIndex }})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="add{{ ucfirst($fieldPrefix) }}PricingTier()" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-plus mr-1"></i>Add Pricing Tier
                </button>
            </div>

            <div>
                <label for="{{ $fieldPrefix }}_quantity" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                <input type="number" id="{{ $fieldPrefix }}_quantity" name="quantity" value="{{ $quantityValue }}" min="0" autocomplete="off" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('quantity')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label for="{{ $fieldPrefix }}_image" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                <input type="file" id="{{ $fieldPrefix }}_image" name="image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @if($product && $product->image)
                    <div class="flex items-center space-x-2 text-xs text-gray-600 mt-2">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Current image" class="w-16 h-16 object-cover rounded">
                        <span>{{ $product->image }}</span>
                    </div>
                @endif
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="{{ $fieldPrefix }}_model_3d" class="block text-sm font-medium text-gray-700 mb-2">3D Model Asset</label>
                <input type="file" id="{{ $fieldPrefix }}_model_3d" name="model_3d" accept="{{ $modelAccept }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Supported formats: {{ implode(', ', $modelExtensions) }} (max 50&nbsp;MB). Zip archives allowed for multi-file packages.</p>
                @if($product && $product->model_3d)
                    @php
                        $modelUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($product->model_3d);
                        $modelExtension = strtolower(pathinfo($product->model_3d, PATHINFO_EXTENSION));
                    @endphp
                    <div class="mt-2 text-sm text-gray-700 space-y-1">
                        <a href="{{ route('admin.products.show3D', $product) }}" target="_blank" class="text-blue-600 hover:text-blue-800">View current 3D asset</a>
                        <a href="{{ $modelUrl }}" target="_blank" class="text-blue-600 hover:text-blue-800">Download current file</a>
                        <span class="block text-xs text-gray-500">Preview availability: {{ in_array($modelExtension, $previewableExtensions ?? []) ? 'Inline preview supported' : 'Download to view locally' }}</span>
                    </div>
                @endif
                @error('model_3d')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="{{ $fieldPrefix }}_details" class="block text-sm font-medium text-gray-700 mb-2">Details</label>
                <textarea id="{{ $fieldPrefix }}_details" name="details" autocomplete="off" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $detailsValue }}</textarea>
                @error('details')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Assign Patterns</label>
                    <button type="button" onclick="toggleAllPatterns('{{ $fieldPrefix }}')" 
                            class="text-xs text-blue-600 hover:text-blue-800 focus:outline-none">
                        Select All / None
                    </button>
                </div>
                <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    @forelse($patterns as $pattern)
                        <label class="flex items-center space-x-2 py-1">
                            <input type="checkbox" name="patterns[]" value="{{ $pattern->id }}"
                                   {{ in_array($pattern->id, $selectedPatterns, true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 pattern-checkbox">
                            <span class="text-sm text-gray-700">{{ $pattern->name }}</span>
                        </label>
                    @empty
                        <p class="text-xs text-gray-500">No patterns available.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Assign Designs</label>
                    <button type="button" onclick="toggleAllDesigns('{{ $fieldPrefix }}')" 
                            class="text-xs text-purple-600 hover:text-purple-800 focus:outline-none">
                        Select All / None
                    </button>
                </div>
                <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    @forelse($designs as $design)
                        <label class="flex items-center space-x-2 py-1">
                            <input type="checkbox" name="designs[]" value="{{ $design->id }}"
                                   {{ in_array($design->id, $selectedDesigns, true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 design-checkbox">
                            <span class="text-sm text-gray-700">{{ $design->name }}</span>
                            @if($design->is_unique)
                                <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded">Unique</span>
                            @endif
                        </label>
                    @empty
                        <p class="text-xs text-gray-500">No designs available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="{{ $footerClasses }}">
        @if($showCancel && $cancelHandler)
            <button type="button" onclick="{{ $cancelHandler }}" class="{{ $cancelButtonClasses }}">
                {{ $cancelLabel }}
            </button>
        @endif
        <button type="submit" class="{{ $submitButtonClasses }}">
            {{ $submitLabel }}
        </button>
    </div>
</form>
