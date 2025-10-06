@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>{{ $example->name }} - 3D Model</h3>

    @if($example->model_3d)
        <model-viewer src="{{ asset('storage/' . $example->model_3d) }}" 
                      alt="{{ $example->name }}" 
                      auto-rotate camera-controls 
                      style="width: 100%; height: 500px;">
        </model-viewer>
    @else
        <p>No 3D model available.</p>
    @endif

    <a href="{{ route('admin.examples.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>

<!-- Include model-viewer -->
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
@endsection
