@extends('layouts.admin')

@section('title', 'Create Color')
@section('page-title', 'Create Color')

@section('page-actions')
    <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Colors
    </a>
@endsection

@push('styles')
<style>
    .color-preview-large {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        border: 2px solid #ddd;
        transition: all 0.3s ease;
    }
    
    .rgb-input {
        max-width: 80px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Color Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.colors.store') }}" method="POST" id="colorForm">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="color_category_id" class="form-label">Category *</label>
                            <select class="form-select @error('color_category_id') is-invalid @enderror" 
                                    id="color_category_id" 
                                    name="color_category_id" 
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('color_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('color_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="coloring_system" class="form-label">Coloring System *</label>
                            <select class="form-select @error('coloring_system') is-invalid @enderror" 
                                    id="coloring_system" 
                                    name="coloring_system" 
                                    required>
                                <option value="">Select System</option>
                                @foreach($coloringSystems as $key => $label)
                                    <option value="{{ $key }}" {{ old('coloring_system') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('coloring_system')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Color Name *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="code" class="form-label">Color Code *</label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code') }}" 
                                   placeholder="e.g., PMS 186 C, RAL 3020"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">RGB Values *</label>
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label for="rgb_r" class="form-label small">Red (0-255)</label>
                                <input type="number" 
                                       class="form-control rgb-input @error('rgb_r') is-invalid @enderror" 
                                       id="rgb_r" 
                                       name="rgb_r" 
                                       min="0" 
                                       max="255" 
                                       value="{{ old('rgb_r', 0) }}" 
                                       required>
                                @error('rgb_r')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="rgb_g" class="form-label small">Green (0-255)</label>
                                <input type="number" 
                                       class="form-control rgb-input @error('rgb_g') is-invalid @enderror" 
                                       id="rgb_g" 
                                       name="rgb_g" 
                                       min="0" 
                                       max="255" 
                                       value="{{ old('rgb_g', 0) }}" 
                                       required>
                                @error('rgb_g')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="rgb_b" class="form-label small">Blue (0-255)</label>
                                <input type="number" 
                                       class="form-control rgb-input @error('rgb_b') is-invalid @enderror" 
                                       id="rgb_b" 
                                       name="rgb_b" 
                                       min="0" 
                                       max="255" 
                                       value="{{ old('rgb_b', 0) }}" 
                                       required>
                                @error('rgb_b')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Preview</label>
                                <div class="color-preview-large" id="colorPreview"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="closest_association" class="form-label">Closest Association</label>
                        <input type="text" 
                               class="form-control @error('closest_association') is-invalid @enderror" 
                               id="closest_association" 
                               name="closest_association" 
                               value="{{ old('closest_association') }}"
                               placeholder="e.g., Ocean Blue, Forest Green">
                        @error('closest_association')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Optional description of this color...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary me-md-2">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Color
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Color Systems Guide</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Pantone Coated</strong>
                    <p class="small text-muted">For coated paper printing</p>
                </div>
                <div class="mb-3">
                    <strong>Pantone Uncoated</strong>
                    <p class="small text-muted">For uncoated paper printing</p>
                </div>
                <div class="mb-3">
                    <strong>HKS K</strong>
                    <p class="small text-muted">European color system for coated paper</p>
                </div>
                <div class="mb-3">
                    <strong>HKS N</strong>
                    <p class="small text-muted">European color system for uncoated paper</p>
                </div>
                <div class="mb-3">
                    <strong>RAL</strong>
                    <p class="small text-muted">European color matching system</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rgbInputs = ['rgb_r', 'rgb_g', 'rgb_b'];
    const colorPreview = document.getElementById('colorPreview');
    
    function updateColorPreview() {
        const r = document.getElementById('rgb_r').value || 0;
        const g = document.getElementById('rgb_g').value || 0;
        const b = document.getElementById('rgb_b').value || 0;
        
        const color = `rgb(${r}, ${g}, ${b})`;
        colorPreview.style.backgroundColor = color;
    }
    
    // Update preview on input change
    rgbInputs.forEach(function(inputId) {
        document.getElementById(inputId).addEventListener('input', updateColorPreview);
    });
    
    // Initial preview update
    updateColorPreview();
});
</script>
@endpush