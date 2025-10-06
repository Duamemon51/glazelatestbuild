@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Product Type</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.product-types.update', $productType->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Parent Category</label>
            <select id="parent_category" name="parent_category_id" class="form-control" required>
                <option value="">-- Select Parent Category --</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ $productType->subcategory->parent_category_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Subcategory</label>
            <select id="subcategory" name="subcategory_id" class="form-control" required>
                <option value="{{ $productType->subcategory->id }}">{{ $productType->subcategory->name }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Product Type Name</label>
            <input type="text" name="name" value="{{ $productType->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        @if($productType->image)
            <div class="mb-3">
                <img src="{{ asset('storage/'.$productType->image) }}" width="120" class="img-thumbnail">
            </div>
        @endif

        <button class="btn btn-primary">Update</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#parent_category').change(function(){
    var parentId = $(this).val();
    if(parentId){
        $.get('/admin/subcategories/by-parent/'+parentId, function(data){
            $('#subcategory').empty().append('<option value="">-- Select Subcategory --</option>');
            data.forEach(function(sub){
                $('#subcategory').append('<option value="'+sub.id+'">'+sub.name+'</option>');
            });
        });
    } else {
        $('#subcategory').empty().append('<option value="">-- Select Subcategory --</option>');
    }
});
</script>
@endsection
