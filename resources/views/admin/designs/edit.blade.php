@extends('layouts.admin')

@section('content')
<h1>Edit Design</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.designs.update', $design->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Name:</label>
    <input type="text" name="name" value="{{ old('name', $design->name) }}" required><br><br>

    <label>Front Image:</label>
    <input type="file" name="front_image">
    @if($design->front_image)
        <img src="{{ asset('storage/'.$design->front_image) }}" width="50">
    @endif
    <br><br>

    <label>Back Image:</label>
    <input type="file" name="back_image">
    @if($design->back_image)
        <img src="{{ asset('storage/'.$design->back_image) }}" width="50">
    @endif
    <br><br>

    <label>Left Image:</label>
    <input type="file" name="left_image">
    @if($design->left_image)
        <img src="{{ asset('storage/'.$design->left_image) }}" width="50">
    @endif
    <br><br>

    <label>Right Image:</label>
    <input type="file" name="right_image">
    @if($design->right_image)
        <img src="{{ asset('storage/'.$design->right_image) }}" width="50">
    @endif
    <br><br>

    <!-- Preview Image Field -->
    <label>Preview Image:</label>
    <input type="file" name="preview_img" accept="image/*" onchange="previewImage(event)">
    @if($design->preview_img)
        <img id="currentPreview" src="{{ asset('storage/'.$design->preview_img) }}" width="50" style="margin-bottom:10px;">
    @endif
    <img id="preview" src="#" alt="Preview Image" style="display:none; max-width:200px; margin-bottom:10px;"><br><br>

    <label>Attach Products:</label><br>
    @foreach($products as $product)
        <input type="checkbox" name="products[]" value="{{ $product->id }}" 
        {{ $design->products->contains($product->id) ? 'checked' : '' }}>
        {{ $product->name }} <br>
    @endforeach
    <br>

    <label>Active:</label>
    <input type="checkbox" name="is_active" value="1" {{ $design->is_active ? 'checked' : '' }}><br><br>

    <!-- Unique Field -->
    <label>Unique:</label>
    <input type="checkbox" name="is_unique" value="1" {{ $design->is_unique ? 'checked' : '' }}><br><br>

    <button type="submit">Update</button>
</form>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const current = document.getElementById('currentPreview');
    const file = event.target.files[0];

    if(file){
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        if(current) current.style.display = 'none'; // hide old preview
    }
}
</script>
@endsection
