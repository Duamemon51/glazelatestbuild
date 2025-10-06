@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>{{ isset($product) ? 'Edit Product' : 'Add Product' }}</h1>

    <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        {{-- Name --}}
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $product->name ?? '' }}" class="form-control" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Enter product description">{{ $product->description ?? '' }}</textarea>
        </div>
{{-- Details --}}
<div class="mb-3">
    <label>Details</label>
    <textarea name="details" class="form-control" rows="4" placeholder="Enter product details">{{ $product->details ?? '' }}</textarea>
</div>

        {{-- Product Type --}}
        <div class="mb-3">
            <label>Product Type</label>
           <select name="product_type_id" class="form-control" required>
    <option value="">-- Select Product Type --</option>
    @foreach($productTypes as $type)
        <option value="{{ $type->id }}" 
            {{ isset($product) && $product->product_type_id == $type->id ? 'selected' : '' }}>
            {{ $type->name }}
            @if($type->parentCategory)
                (Parent: {{ $type->parentCategory->name }})
            @endif
        </option>
    @endforeach
</select>

        </div>

        {{-- Gender --}}
        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="">-- Select Gender --</option>
                @foreach(['Men','Women','Kids'] as $gender)
                    <option value="{{ $gender }}" 
                        {{ isset($product) && $product->gender == $gender ? 'selected' : '' }}>
                        {{ $gender }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Base Price --}}
        <div class="mb-3">
            <label>Base Price</label>
            <input type="number" name="price" value="{{ $product->price ?? '' }}" class="form-control" required>
        </div>

        {{-- Stock Quantity --}}
        <div class="mb-3">
            <label>Stock Quantity</label>
            <input type="number" name="quantity" value="{{ $product->quantity ?? 0 }}" class="form-control" min="0">
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label>Status</label>
            <select name="is_active" class="form-control" required>
                <option value="1" {{ isset($product) && $product->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ isset($product) && !$product->is_active ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        {{-- Quantity-wise Prices --}}
        <div class="mb-3">
            <label>Quantity-wise Prices</label>
            <div id="prices-container">
                @php
                    $rawPrices = isset($product) ? ($product->prices ?? []) : [];
                    if (is_string($rawPrices)) {
                        $rawPrices = json_decode($rawPrices, true) ?: [];
                    }
                    $prices = collect($rawPrices)->map(function ($tier) {
                        return [
                            'min_quantity' => $tier['min_quantity'] ?? ($tier['quantity'] ?? ''),
                            'price' => $tier['price'] ?? '',
                        ];
                    })->filter(function ($tier) {
                        return $tier['min_quantity'] !== '' || $tier['price'] !== '';
                    })->values()->toArray();
                    if (empty($prices)) {
                        $prices = [['min_quantity' => '', 'price' => '']];
                    }
                @endphp
                @foreach($prices as $i => $p)
                <div class="row mb-2 price-row">
                    <div class="col">
                        <input type="number" name="prices[{{ $i }}][min_quantity]" value="{{ $p['min_quantity'] }}" class="form-control" placeholder="Min Quantity" min="1" required>
                    </div>
                    <div class="col">
                        <input type="number" name="prices[{{ $i }}][price]" value="{{ $p['price'] }}" class="form-control" placeholder="Price" step="0.01" min="0" required>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-danger remove-price">Remove</button>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-price" class="btn btn-secondary mt-2">Add Quantity Price</button>
        </div>

        {{-- Image --}}
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if(isset($product) && $product->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->image) }}" width="120" class="img-thumbnail">
                </div>
            @endif
        </div>

        {{-- 3D Model --}}
        @php
            $modelExtensions = config('uploads.product_model_extensions', []);
            $modelAccept = collect($modelExtensions)->map(fn($ext) => '.' . $ext)->implode(',');
            $previewableExtensions = config('uploads.previewable_model_extensions', []);
            $currentModelExt = isset($product) && $product->model_3d ? strtolower(pathinfo($product->model_3d, PATHINFO_EXTENSION)) : null;
        @endphp
        <div class="mb-3">
            <label>3D Model Asset</label>
            <input type="file" name="model_3d" class="form-control" accept="{{ $modelAccept }}">
            <small class="text-muted">Supported formats: {{ implode(', ', $modelExtensions) }} (max 50&nbsp;MB). Zip archives allowed for multi-file packages.</small>
            @if(isset($product) && $product->model_3d)
                <div class="mt-2">
                    <a href="{{ route('admin.products.show3D', $product->id) }}" target="_blank">View Current Model</a>
                    @php
                        $downloadUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($product->model_3d);
                    @endphp
                    <span class="d-block"><a href="{{ $downloadUrl }}" target="_blank">Download Asset</a></span>
                    <span class="text-muted">Preview availability: {{ $currentModelExt && in_array($currentModelExt, $previewableExtensions ?? []) ? 'Inline preview supported' : 'Download to view locally' }}</span>
                </div>
            @endif
        </div>

        <button class="btn btn-{{ isset($product) ? 'primary' : 'success' }}">
            {{ isset($product) ? 'Update' : 'Save' }}
        </button>
    </form>
</div>

{{-- JS for dynamic quantity-price rows --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let pricesContainer = document.getElementById('prices-container');
    let addBtn = document.getElementById('add-price');

    addBtn.addEventListener('click', function () {
        let index = pricesContainer.querySelectorAll('.price-row').length;
        let row = document.createElement('div');
        row.classList.add('row','mb-2','price-row');
        row.innerHTML = `
            <div class="col">
                <input type="number" name="prices[${index}][min_quantity]" class="form-control" placeholder="Min Quantity" min="1" required>
            </div>
            <div class="col">
                <input type="number" name="prices[${index}][price]" class="form-control" placeholder="Price" step="0.01" min="0" required>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger remove-price">Remove</button>
            </div>
        `;
        pricesContainer.appendChild(row);
    });

    pricesContainer.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-price')){
            e.target.closest('.price-row').remove();
        }
    });
});
</script>
@endsection
