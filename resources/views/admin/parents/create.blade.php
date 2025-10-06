@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Add Parent Category</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.parents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Default Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Fallback image used when no specific asset is provided.</small>
        </div>

        <div class="mb-3">
            <label>Homepage Image</label>
            <input type="file" name="home_image" class="form-control" accept="image/*">
            <small class="text-muted">Displayed on the homepage category carousel.</small>
        </div>

        <div class="mb-3">
            <label>Category Page Hero Image</label>
            <input type="file" name="category_image" class="form-control" accept="image/*">
            <small class="text-muted">Shown as the hero image on the category detail page.</small>
        </div>

        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
