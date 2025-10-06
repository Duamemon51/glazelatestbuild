@extends('layouts.admin')

@section('title', 'Edit Color Category')
@section('page-title', 'Edit Color Category')

@section('page-actions')
    <a href="{{ route('admin.color-categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Categories
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Category Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.color-categories.update', $colorCategory) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $colorCategory->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Optional description for this color category...">{{ old('description', $colorCategory->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.color-categories.index') }}" class="btn btn-secondary me-md-2">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Category Stats</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $colorCategory->colors->count() }}</h4>
                            <small class="text-muted">Colors</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-1">{{ $colorCategory->created_at->format('M Y') }}</h4>
                        <small class="text-muted">Created</small>
                    </div>
                </div>
                
                @if($colorCategory->colors->count() > 0)
                <hr>
                <div class="d-flex">
                    <i class="fas fa-exclamation-triangle text-warning me-2 mt-1"></i>
                    <small class="text-muted">
                        This category contains {{ $colorCategory->colors->count() }} color(s). 
                        Deleting this category will also delete all its colors.
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection