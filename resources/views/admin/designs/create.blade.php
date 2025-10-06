@extends('layouts.admin')

@section('content')
<h1>Create Design</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.designs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Name:</label>
    <input type="text" name="name" value="{{ old('name') }}" required><br><br>

    <label>Front Image:</label>
    <input type="file" name="front_image" accept="image/*"><br><br>

    <label>Back Image:</label>
    <input type="file" name="back_image" accept="image/*"><br><br>

    <label>Left Image:</label>
    <input type="file" name="left_image" accept="image/*"><br><br>

    <label>Right Image:</label>
    <input type="file" name="right_image" accept="image/*"><br><br>

    <!-- Preview Image Field -->
    <label>Preview Image:</label>
    <input type="file" name="preview_img" accept="image/*" onchange="previewImage(event)"><br><br>
    <img id="preview" src="#" alt="Preview Image" style="display:none; max-width:200px; margin-bottom:10px;"><br>

    <label>Attach Products:</label><br>
    @foreach($products as $product)
        <input type="checkbox" name="products[]" value="{{ $product->id }}"> {{ $product->name }} <br>
    @endforeach
    <br>

    <label>Active:</label>
    <input type="checkbox" name="is_active" value="1" checked><br><br>

    <!-- Unique Field -->
    <label>Unique:</label>
    <input type="checkbox" name="is_unique" value="1"><br><br>

    <button type="submit">Create</button>
</form>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if(file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}
</script>
@endsection
