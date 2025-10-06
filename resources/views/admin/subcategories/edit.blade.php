@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Subcategory</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $subcategory->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Parent Category</label>
            <select name="parent_category_id" class="form-control" required>
                <option value="">-- Select Parent Category --</option>
                @foreach($parentCategories as $parent) {{-- sahi variable --}}
                    <option value="{{ $parent->id }}" {{ $subcategory->parent_category_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
