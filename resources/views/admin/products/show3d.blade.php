{{-- resources/views/admin/products/show3d.blade.php --}}

@extends('layouts.admin')

@section('content')
<div class="container text-center py-5">
    <h2>3D Model View of {{ $product->name }}</h2>

    @if ($product->model_3d)
        @php
            $storagePath = asset('storage/' . $product->model_3d);
            $isPreviewable = in_array($modelExtension, $previewableExtensions ?? []);
        @endphp

        @if($isPreviewable)
            <div class="model-viewer-container centered-model-container">
                <model-viewer 
                    src="{{ $storagePath }}" 
                    ar 
                    ar-modes="webxr scene-viewer quick-look" 
                    camera-controls 
                    touch-action="pan-y" 
                    alt="A 3D model of {{ $product->name }}">
                </model-viewer>
            </div>
        @else
            <div class="alert alert-info" role="alert">
                <strong>Preview unavailable.</strong> The uploaded file format (<code>{{ strtoupper($modelExtension) }}</code>) can’t be rendered in the browser. Download the asset to inspect it locally.
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ $storagePath }}" class="btn btn-primary" download>
                <i class="fas fa-download me-2"></i>Download 3D Asset
            </a>
        </div>
    @else
        <p>No 3D model available for this product.</p>
    @endif

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-4">Back to Products</a>
</div>

{{-- Updated CSS to increase the size of the model viewer --}}
<style>
    .centered-model-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        /* Adjusted the height to a percentage of the viewport height */
        height: 80vh; 
        margin: auto;
    }

    model-viewer {
        /* Make the model viewer element fill its container */
        width: 100%;
        height: 100%;
    }
</style>

@if(in_array($modelExtension, $previewableExtensions ?? []))
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
@endif
@endsection