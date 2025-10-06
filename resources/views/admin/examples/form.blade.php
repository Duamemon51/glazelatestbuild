@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ isset($example) ? 'Edit Example' : 'Add New Example' }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($example) ? route('admin.examples.update', $example->id) : route('admin.examples.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($example))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">Example Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $example->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="product_type_id" class="form-label">Product Type</label>
            <select name="product_type_id" id="product_type_id" class="form-select" required>
                <option value="">Select Product Type</option>
                @foreach($productTypes as $type)
                    <option value="{{ $type->id }}" {{ (old('product_type_id', $example->product_type_id ?? '') == $type->id) ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control">
            @if(isset($example) && $example->image)
                <img src="{{ asset('storage/' . $example->image) }}" alt="" width="100" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            <label for="model_3d" class="form-label">3D Model (.glb or .gltf)</label>
            <input type="file" name="model_3d" id="model_3d" class="form-control">
            @if(isset($example) && $example->model_3d)
                <a href="{{ asset('storage/' . $example->model_3d) }}" target="_blank" class="d-block mt-2">View Current 3D Model</a>
            @endif
        </div>

        <button type="submit" class="btn btn-success">{{ isset($example) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection
