@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Add Subcategory</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.subcategories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Parent Category</label>
            <select name="parent_category_id" class="form-control" required>
                <option value="">-- Select Parent Category --</option>
                @foreach($parentCategories as $parent) {{-- sahi variable naam --}}
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
