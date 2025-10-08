@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Edit Page</h1>
        <div class="flex space-x-3">
            <a href="{{ route('page.show', $page->slug) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center" target="_blank">
                <i class="fas fa-eye mr-2"></i>
                View Page
            </a>
            <a href="{{ route('admin.cms.pages.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Pages
            </a>
        </div>
    </div>

    <form action="{{ route('admin.cms.pages.update', $page) }}" method="POST" id="pageForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Page Content Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Page Content</h2>

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Page Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('title') ? 'border-red-500' : '' }}" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-6">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Page Slug <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('slug') ? 'border-red-500' : '' }}" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-6">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('excerpt') ? 'border-red-500' : '' }}" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $page->excerpt) }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Brief description of the page content (optional).</p>
                    </div>

                    <!-- Content Sections -->
                    <div id="contentSections">
                        @foreach($page->contentSections->sortBy('sort_order') as $index => $section)
                        <div class="content-section border border-gray-200 rounded-lg p-4 mb-4" id="section-{{ $index + 1 }}" data-section-id="{{ $index + 1 }}">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Content Section {{ $index + 1 }}</h3>
                                <button type="button" class="text-red-600 hover:text-red-800 remove-section {{ $page->contentSections->count() > 1 ? '' : 'hidden' }}">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Section Type</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 section-type" name="content_sections[{{ $index }}][section_type]" required>
                                    <option value="html" {{ $section->section_type == 'html' ? 'selected' : '' }}>Rich Text (HTML)</option>
                                    <option value="text" {{ $section->section_type == 'text' ? 'selected' : '' }}>Plain Text</option>
                                    <option value="image" {{ $section->section_type == 'image' ? 'selected' : '' }}>Single Image</option>
                                    <option value="gallery" {{ $section->section_type == 'gallery' ? 'selected' : '' }}>Image Gallery</option>
                                    <option value="video" {{ $section->section_type == 'video' ? 'selected' : '' }}>Video Embed</option>
                                    <option value="embed" {{ $section->section_type == 'embed' ? 'selected' : '' }}>Custom Embed</option>
                                </select>
                            </div>

                            <!-- Section Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Section Title (Optional)</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][section_name]" value="{{ old('content_sections.' . $index . '.section_name', $section->section_name) }}">
                            </div>

                            <!-- HTML/Rich Text Section Type -->
                            <div class="section-type-content section-type-html" style="{{ $section->section_type == 'html' ? 'display: block;' : 'display: none;' }}">
                                <!-- Layout Type -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Layout Type</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 layout-type" name="content_sections[{{ $index }}][layout_type]">
                                        <option value="single" {{ old('content_sections.' . $index . '.layout_type', $section->layout_type ?? 'single') == 'single' ? 'selected' : '' }}>Single Column</option>
                                        <option value="two_column" {{ old('content_sections.' . $index . '.layout_type', $section->layout_type) == 'two_column' ? 'selected' : '' }}>Two Columns</option>
                                        <option value="three_column" {{ old('content_sections.' . $index . '.layout_type', $section->layout_type) == 'three_column' ? 'selected' : '' }}>Three Columns</option>
                                    </select>
                                </div>

                            <!-- Single Column Content -->
                            <div class="content-editor-container single-column-content" style="{{ old('content_sections.' . $index . '.layout_type', $section->layout_type ?? 'single') == 'single' ? 'display: block;' : 'display: none;' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 content-editor" name="content_sections[{{ $index }}][single_content]" rows="8">{{ old('content_sections.' . $index . '.single_content', $section->content) }}</textarea>
                            </div>

                            <!-- Multi-Column Content -->
                            <div class="multi-column-content" style="{{ old('content_sections.' . $index . '.layout_type', $section->layout_type ?? 'single') != 'single' ? 'display: block;' : 'display: none;' }}">
                                <!-- Column 1 -->
                                <div class="mb-6 border border-gray-200 rounded-lg p-4">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">Column 1</h4>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[{{ $index }}][column_1_type]">
                                            <option value="content" {{ old('content_sections.' . $index . '.column_1_type', $section->column_1_type ?? 'content') == 'content' ? 'selected' : '' }}>Rich Text Content</option>
                                            <option value="image" {{ old('content_sections.' . $index . '.column_1_type', $section->column_1_type) == 'image' ? 'selected' : '' }}>Single Image</option>
                                            <option value="gallery" {{ old('content_sections.' . $index . '.column_1_type', $section->column_1_type) == 'gallery' ? 'selected' : '' }}>Image Gallery (Slider)</option>
                                            <option value="video" {{ old('content_sections.' . $index . '.column_1_type', $section->column_1_type) == 'video' ? 'selected' : '' }}>YouTube Video</option>
                                        </select>
                                    </div>
                                    <div class="column-content-container">
                                        <div class="content-type-content" style="{{ old('content_sections.' . $index . '.column_1_type', $section->column_1_type ?? 'content') == 'content' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[{{ $index }}][column_1_content]" rows="6">{{ old('content_sections.' . $index . '.column_1_content', $section->column_1_content) }}</textarea>
                                        </div>
                                        <div class="content-type-image" style="{{ old('content_sections.' . $index . '.column_1_type', $section->column_1_type ?? 'content') == 'image' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_1_image]" accept="image/*">
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[{{ $index }}][column_1_image_url]" value="{{ old('content_sections.' . $index . '.column_1_image_url', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_1_image_url'] ?? '') }}" placeholder="https://example.com/image.jpg">
                                            @php
                                                $sectionData = is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true);
                                                $currentCol1ImageUrl = $sectionData['column_1_image_url'] ?? null;
                                                $currentCol1ImagePath = $sectionData['column_1_image_path'] ?? null;
                                            @endphp
                                            @if($currentCol1ImageUrl || $currentCol1ImagePath)
                                            <div class="mt-2">
                                                <img src="{{ $currentCol1ImageUrl ?: asset('storage/' . $currentCol1ImagePath) }}" alt="Current image" class="max-w-full h-auto max-h-24 rounded border">
                                            </div>
                                            @endif
                                        </div>
                                        <div class="content-type-gallery" style="{{ old('content_sections.' . $index . '.column_1_type', $section->column_1_type ?? 'content') == 'gallery' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_1_gallery][]" accept="image/*" multiple>
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[{{ $index }}][column_1_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg">{{ old('content_sections.' . $index . '.column_1_gallery_urls', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_1_gallery_urls'] ?? '') }}</textarea>
                                            @php
                                                $sectionData = is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true);
                                                $currentCol1GalleryUrls = $sectionData['column_1_gallery_urls'] ?? null;
                                                $currentCol1GalleryPaths = $sectionData['column_1_gallery_paths'] ?? null;
                                            @endphp
                                            @if($currentCol1GalleryUrls || $currentCol1GalleryPaths)
                                            <div class="mt-2">
                                                <div class="grid grid-cols-3 gap-1">
                                                    @if($currentCol1GalleryUrls)
                                                        @foreach(explode("\n", trim($currentCol1GalleryUrls)) as $url)
                                                            @if(trim($url))
                                                            <img src="{{ trim($url) }}" alt="Gallery image" class="w-full h-16 object-cover rounded border">
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if($currentCol1GalleryPaths && is_array($currentCol1GalleryPaths))
                                                        @foreach($currentCol1GalleryPaths as $path)
                                                            <img src="{{ asset('storage/' . $path) }}" alt="Gallery image" class="w-full h-16 object-cover rounded border">
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="content-type-video" style="{{ old('content_sections.' . $index . '.column_1_type', $section->column_1_type ?? 'content') == 'video' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_1_video]" value="{{ old('content_sections.' . $index . '.column_1_video', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_1_video'] ?? '') }}" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-6 border border-gray-200 rounded-lg p-4 column-2-field">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">Column 2</h4>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[{{ $index }}][column_2_type]">
                                            <option value="content" {{ old('content_sections.' . $index . '.column_2_type', $section->column_2_type ?? 'content') == 'content' ? 'selected' : '' }}>Rich Text Content</option>
                                            <option value="image" {{ old('content_sections.' . $index . '.column_2_type', $section->column_2_type) == 'image' ? 'selected' : '' }}>Single Image</option>
                                            <option value="gallery" {{ old('content_sections.' . $index . '.column_2_type', $section->column_2_type) == 'gallery' ? 'selected' : '' }}>Image Gallery (Slider)</option>
                                            <option value="video" {{ old('content_sections.' . $index . '.column_2_type', $section->column_2_type) == 'video' ? 'selected' : '' }}>YouTube Video</option>
                                        </select>
                                    </div>
                                    <div class="column-content-container">
                                        <div class="content-type-content" style="{{ old('content_sections.' . $index . '.column_2_type', $section->column_2_type ?? 'content') == 'content' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[{{ $index }}][column_2_content]" rows="6">{{ old('content_sections.' . $index . '.column_2_content', $section->column_2_content) }}</textarea>
                                        </div>
                                        <div class="content-type-image" style="{{ old('content_sections.' . $index . '.column_2_type', $section->column_2_type ?? 'content') == 'image' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_2_image]" accept="image/*">
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[{{ $index }}][column_2_image_url]" value="{{ old('content_sections.' . $index . '.column_2_image_url', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_2_image_url'] ?? '') }}" placeholder="https://example.com/image.jpg">
                                            @php
                                                $sectionData = is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true);
                                                $currentCol2ImageUrl = $sectionData['column_2_image_url'] ?? null;
                                                $currentCol2ImagePath = $sectionData['column_2_image_path'] ?? null;
                                            @endphp
                                            @if($currentCol2ImageUrl || $currentCol2ImagePath)
                                            <div class="mt-2">
                                                <img src="{{ $currentCol2ImageUrl ?: asset('storage/' . $currentCol2ImagePath) }}" alt="Current image" class="max-w-full h-auto max-h-24 rounded border">
                                            </div>
                                            @endif
                                        </div>
                                        <div class="content-type-gallery" style="{{ old('content_sections.' . $index . '.column_2_type', $section->column_2_type ?? 'content') == 'gallery' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_2_gallery][]" accept="image/*" multiple>
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[{{ $index }}][column_2_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg">{{ old('content_sections.' . $index . '.column_2_gallery_urls', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_2_gallery_urls'] ?? '') }}</textarea>
                                            @php
                                                $sectionData = is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true);
                                                $currentCol2GalleryUrls = $sectionData['column_2_gallery_urls'] ?? null;
                                                $currentCol2GalleryPaths = $sectionData['column_2_gallery_paths'] ?? null;
                                            @endphp
                                            @if($currentCol2GalleryUrls || $currentCol2GalleryPaths)
                                            <div class="mt-2">
                                                <div class="grid grid-cols-3 gap-1">
                                                    @if($currentCol2GalleryUrls)
                                                        @foreach(explode("\n", trim($currentCol2GalleryUrls)) as $url)
                                                            @if(trim($url))
                                                            <img src="{{ trim($url) }}" alt="Gallery image" class="w-full h-16 object-cover rounded border">
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if($currentCol2GalleryPaths && is_array($currentCol2GalleryPaths))
                                                        @foreach($currentCol2GalleryPaths as $path)
                                                            <img src="{{ asset('storage/' . $path) }}" alt="Gallery image" class="w-full h-16 object-cover rounded border">
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="content-type-video" style="{{ old('content_sections.' . $index . '.column_2_type', $section->column_2_type ?? 'content') == 'video' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_2_video]" value="{{ old('content_sections.' . $index . '.column_2_video', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_2_video'] ?? '') }}" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 3 -->
                                <div class="mb-6 border border-gray-200 rounded-lg p-4 column-3-field" style="{{ old('content_sections.' . $index . '.layout_type', $section->layout_type) == 'three_column' ? 'display: block;' : 'display: none;' }}">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">Column 3</h4>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[{{ $index }}][column_3_type]">
                                            <option value="content" {{ old('content_sections.' . $index . '.column_3_type', $section->column_3_type ?? 'content') == 'content' ? 'selected' : '' }}>Rich Text Content</option>
                                            <option value="image" {{ old('content_sections.' . $index . '.column_3_type', $section->column_3_type) == 'image' ? 'selected' : '' }}>Single Image</option>
                                            <option value="gallery" {{ old('content_sections.' . $index . '.column_3_type', $section->column_3_type) == 'gallery' ? 'selected' : '' }}>Image Gallery (Slider)</option>
                                            <option value="video" {{ old('content_sections.' . $index . '.column_3_type', $section->column_3_type) == 'video' ? 'selected' : '' }}>YouTube Video</option>
                                        </select>
                                    </div>
                                    <div class="column-content-container">
                                        <div class="content-type-content" style="{{ old('content_sections.' . $index . '.column_3_type', $section->column_3_type ?? 'content') == 'content' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[{{ $index }}][column_3_content]" rows="6">{{ old('content_sections.' . $index . '.column_3_content', $section->column_3_content) }}</textarea>
                                        </div>
                                        <div class="content-type-image" style="{{ old('content_sections.' . $index . '.column_3_type', $section->column_3_type ?? 'content') == 'image' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_3_image]" accept="image/*">
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[{{ $index }}][column_3_image_url]" value="{{ old('content_sections.' . $index . '.column_3_image_url', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_3_image_url'] ?? '') }}" placeholder="https://example.com/image.jpg">
                                            @php
                                                $sectionData = is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true);
                                                $currentCol3ImageUrl = $sectionData['column_3_image_url'] ?? null;
                                                $currentCol3ImagePath = $sectionData['column_3_image_path'] ?? null;
                                            @endphp
                                            @if($currentCol3ImageUrl || $currentCol3ImagePath)
                                            <div class="mt-2">
                                                <img src="{{ $currentCol3ImageUrl ?: asset('storage/' . $currentCol3ImagePath) }}" alt="Current image" class="max-w-full h-auto max-h-24 rounded border">
                                            </div>
                                            @endif
                                        </div>
                                        <div class="content-type-gallery" style="{{ old('content_sections.' . $index . '.column_3_type', $section->column_3_type ?? 'content') == 'gallery' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_3_gallery][]" accept="image/*" multiple>
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[{{ $index }}][column_3_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg">{{ old('content_sections.' . $index . '.column_3_gallery_urls', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_3_gallery_urls'] ?? '') }}</textarea>
                                            @php
                                                $sectionData = is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true);
                                                $currentCol3GalleryUrls = $sectionData['column_3_gallery_urls'] ?? null;
                                                $currentCol3GalleryPaths = $sectionData['column_3_gallery_paths'] ?? null;
                                            @endphp
                                            @if($currentCol3GalleryUrls || $currentCol3GalleryPaths)
                                            <div class="mt-2">
                                                <div class="grid grid-cols-3 gap-1">
                                                    @if($currentCol3GalleryUrls)
                                                        @foreach(explode("\n", trim($currentCol3GalleryUrls)) as $url)
                                                            @if(trim($url))
                                                            <img src="{{ trim($url) }}" alt="Gallery image" class="w-full h-16 object-cover rounded border">
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if($currentCol3GalleryPaths && is_array($currentCol3GalleryPaths))
                                                        @foreach($currentCol3GalleryPaths as $path)
                                                            <img src="{{ asset('storage/' . $path) }}" alt="Gallery image" class="w-full h-16 object-cover rounded border">
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="content-type-video" style="{{ old('content_sections.' . $index . '.column_3_type', $section->column_3_type ?? 'content') == 'video' ? 'display: block;' : 'display: none;' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_3_video]" value="{{ old('content_sections.' . $index . '.column_3_video', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['column_3_video'] ?? '') }}" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!-- Plain Text Section Type -->
                            <div class="section-type-content section-type-text" style="{{ $section->section_type == 'text' ? 'display: block;' : 'display: none;' }}">
                                <!-- Layout Type -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Layout Type</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 layout-type" name="content_sections[{{ $index }}][layout_type]">
                                        <option value="single" {{ old('content_sections.' . $index . '.layout_type', $section->layout_type ?? 'single') == 'single' ? 'selected' : '' }}>Single Column</option>
                                        <option value="two_column" {{ old('content_sections.' . $index . '.layout_type', $section->layout_type) == 'two_column' ? 'selected' : '' }}>Two Columns</option>
                                        <option value="three_column" {{ old('content_sections.' . $index . '.layout_type', $section->layout_type) == 'three_column' ? 'selected' : '' }}>Three Columns</option>
                                    </select>
                                </div>

                                <!-- Single Column Content -->
                                <div class="content-editor-container single-column-content" style="{{ old('content_sections.' . $index . '.layout_type', $section->layout_type ?? 'single') == 'single' ? 'display: block;' : 'display: none;' }}">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][content]" rows="8" placeholder="Enter plain text content here...">{{ old('content_sections.' . $index . '.content', $section->data['content'] ?? '') }}</textarea>
                                </div>

                                <!-- Multi-Column Content -->
                                <div class="multi-column-content" style="{{ old('content_sections.' . $index . '.layout_type', $section->layout_type ?? 'single') != 'single' ? 'display: block;' : 'display: none;' }}">
                                    <!-- Column 1 -->
                                    <div class="mb-6 border border-gray-200 rounded-lg p-4">
                                        <h4 class="text-md font-medium text-gray-800 mb-3">Column 1</h4>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_1_content]" rows="6" placeholder="Enter plain text content for column 1...">{{ old('content_sections.' . $index . '.column_1_content', $section->column_1_content) }}</textarea>
                                    </div>
                                    
                                    <!-- Column 2 -->
                                    <div class="mb-6 border border-gray-200 rounded-lg p-4 column-2-field">
                                        <h4 class="text-md font-medium text-gray-800 mb-3">Column 2</h4>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_2_content]" rows="6" placeholder="Enter plain text content for column 2...">{{ old('content_sections.' . $index . '.column_2_content', $section->column_2_content) }}</textarea>
                                    </div>
                                    
                                    <!-- Column 3 -->
                                    <div class="mb-6 border border-gray-200 rounded-lg p-4 column-3-field" style="{{ old('content_sections.' . $index . '.layout_type', $section->layout_type) == 'three_column' ? 'display: block;' : 'display: none;' }}">
                                        <h4 class="text-md font-medium text-gray-800 mb-3">Column 3</h4>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][column_3_content]" rows="6" placeholder="Enter plain text content for column 3...">{{ old('content_sections.' . $index . '.column_3_content', $section->column_3_content) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Image Section Type -->
                            <div class="section-type-content section-type-image" style="{{ $section->section_type == 'image' ? 'display: block;' : 'display: none;' }}">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][image_file]" accept="image/*">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Or Image URL</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][image_url]" value="{{ old('content_sections.' . $index . '.image_url', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['image_url'] ?? '') }}" placeholder="https://example.com/image.jpg">
                                </div>
                                @php
                                    $sectionData = is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true);
                                    $currentImageUrl = $sectionData['image_url'] ?? null;
                                    $currentImagePath = $sectionData['single_image_path'] ?? $sectionData['image_path'] ?? null;
                                @endphp
                                @if($currentImageUrl || $currentImagePath)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                    <div class="border border-gray-300 rounded-md p-2">
                                        <img src="{{ $currentImageUrl ?: asset('storage/' . $currentImagePath) }}" alt="{{ $sectionData['image_alt'] ?? 'Current image' }}" class="max-w-full h-auto max-h-48 rounded">
                                    </div>
                                </div>
                                @endif
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Image Caption (Optional)</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][image_caption]" value="{{ old('content_sections.' . $index . '.image_caption', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['image_caption'] ?? '') }}" placeholder="Enter image caption...">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][image_alt]" value="{{ old('content_sections.' . $index . '.image_alt', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['image_alt'] ?? '') }}" placeholder="Enter alt text for accessibility...">
                                </div>
                            </div>

                            <!-- Image Gallery Section Type -->
                            <div class="section-type-content section-type-gallery" style="{{ $section->section_type == 'gallery' ? 'display: block;' : 'display: none;' }}">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][gallery_files][]" accept="image/*" multiple>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Or Gallery Image URLs (one per line)</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][gallery_urls]" rows="6" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg">{{ old('content_sections.' . $index . '.gallery_urls', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['gallery_urls'] ?? '') }}</textarea>
                                </div>
                                @php
                                    $sectionData = is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true);
                                    $currentGalleryUrls = $sectionData['gallery_urls'] ?? null;
                                    $currentGalleryPaths = $sectionData['gallery_paths'] ?? null;
                                @endphp
                                @if($currentGalleryUrls || $currentGalleryPaths)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Gallery Images</label>
                                    <div class="border border-gray-300 rounded-md p-2">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                            @if($currentGalleryUrls)
                                                @foreach(explode("\n", trim($currentGalleryUrls)) as $url)
                                                    @if(trim($url))
                                                    <div class="relative">
                                                        <img src="{{ trim($url) }}" alt="Gallery image" class="w-full h-20 object-cover rounded">
                                                    </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if($currentGalleryPaths && is_array($currentGalleryPaths))
                                                @foreach($currentGalleryPaths as $path)
                                                    <div class="relative">
                                                        <img src="{{ asset('storage/' . $path) }}" alt="Gallery image" class="w-full h-20 object-cover rounded">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Caption (Optional)</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][gallery_caption]" value="{{ old('content_sections.' . $index . '.gallery_caption', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['gallery_caption'] ?? '') }}" placeholder="Enter gallery caption...">
                                </div>
                            </div>

                            <!-- Video Embed Section Type -->
                            <div class="section-type-content section-type-video" style="{{ $section->section_type == 'video' ? 'display: block;' : 'display: none;' }}">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][video_url]" value="{{ old('content_sections.' . $index . '.video_url', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['video_url'] ?? '') }}" placeholder="https://www.youtube.com/watch?v=VIDEO_ID or https://vimeo.com/VIDEO_ID">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Video Caption (Optional)</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][video_caption]" value="{{ old('content_sections.' . $index . '.video_caption', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['video_caption'] ?? '') }}" placeholder="Enter video caption...">
                                </div>
                            </div>

                            <!-- Custom Embed Section Type -->
                            <div class="section-type-content section-type-embed" style="{{ $section->section_type == 'embed' ? 'display: block;' : 'display: none;' }}">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Embed Code</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][embed_code]" rows="6" placeholder="Paste your embed code here (iframe, script tags, etc.)...">{{ old('content_sections.' . $index . '.embed_code', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['embed_code'] ?? '') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Embed Caption (Optional)</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[{{ $index }}][embed_caption]" value="{{ old('content_sections.' . $index . '.embed_caption', (is_array($section->data) ? $section->data : json_decode($section->data ?? '{}', true))['embed_caption'] ?? '') }}" placeholder="Enter embed caption...">
                                </div>
                            </div>

                            <input type="hidden" name="content_sections[{{ $index }}][sort_order]" value="{{ $section->sort_order }}">
                            <input type="hidden" name="content_sections[{{ $index }}][id]" value="{{ $section->id }}">
                        </div>
                        @endforeach
                    </div>

                    <!-- Add Section Button -->
                    <button type="button" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex items-center" id="addSection">
                        <i class="fas fa-plus mr-2"></i> Add Content Section
                    </button>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="space-y-6">
                <!-- Publishing Options -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing</h3>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('status') ? 'border-red-500' : '' }}" id="status" name="status" required>
                            <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="scheduled" {{ old('status', $page->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publish Date -->
                    <div class="mb-4" id="publishedAtGroup" style="{{ old('status', $page->status) == 'scheduled' ? 'display: block;' : 'display: none;' }}">
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                        <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('published_at') ? 'border-red-500' : '' }}" id="published_at" name="published_at" value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}">
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visibility -->
                    <div class="mb-4">
                        <label for="visibility" class="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('visibility') ? 'border-red-500' : '' }}" id="visibility" name="visibility" required>
                            <option value="public" {{ old('visibility', $page->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ old('visibility', $page->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                            <option value="password" {{ old('visibility', $page->visibility) == 'password' ? 'selected' : '' }}>Password Protected</option>
                        </select>
                        @error('visibility')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Page Settings -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Page Settings</h3>

                    <!-- Template -->
                    <div class="mb-4">
                        <label for="template" class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('template') ? 'border-red-500' : '' }}" id="template" name="template">
                            <option value="default" {{ old('template', $page->template) == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="full-width" {{ old('template', $page->template) == 'full-width' ? 'selected' : '' }}>Full Width</option>
                            <option value="landing" {{ old('template', $page->template) == 'landing' ? 'selected' : '' }}>Landing Page</option>
                        </select>
                        @error('template')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-4">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                        @if($page->featured_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $page->featured_image) }}" alt="Current featured image" class="w-32 h-32 object-cover rounded-lg border">
                                <p class="text-sm text-gray-500 mt-1">Current image</p>
                            </div>
                        @endif
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('featured_image') ? 'border-red-500' : '' }}" id="featured_image" name="featured_image" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Upload a new image to replace the current one (JPG, PNG, GIF, WebP). Max 2MB. Leave empty to keep current image.</p>
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SEO Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Information</h3>

                    <!-- SEO Title -->
                    <div class="mb-4">
                        <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-2">SEO Title</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="seo_title" name="seo_title" value="{{ old('seo_title', $page->seoData ? $page->seoData->title : '') }}" maxlength="60">
                        <p class="mt-1 text-sm text-gray-500">Leave blank to use page title. Max 60 characters.</p>
                    </div>

                    <!-- SEO Description -->
                    <div class="mb-4">
                        <label for="seo_description" class="block text-sm font-medium text-gray-700 mb-2">SEO Description</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="seo_description" name="seo_description" rows="3" maxlength="160">{{ old('seo_description', $page->seoData ? $page->seoData->description : '') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Leave blank to use excerpt. Max 160 characters.</p>
                    </div>

                    <!-- SEO Keywords -->
                    <div class="mb-4">
                        <label for="seo_keywords" class="block text-sm font-medium text-gray-700 mb-2">SEO Keywords</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $page->seoData ? $page->seoData->keywords : '') }}">
                        <p class="mt-1 text-sm text-gray-500">Comma-separated keywords.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.cms.pages.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Page
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .content-section {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
// Global variables
let sectionCount = {{ $page->contentSections->count() }};

// Column layout toggle function - made global for inline event handlers
function toggleColumnFields(selectElement) {
    const section = selectElement.closest('.content-section');
    
    // Find the current active section type container
    const activeSectionType = section.querySelector('.section-type-content[style*="display: block"], .section-type-content[style="display: block;"]');
    if (!activeSectionType) {
        console.error('Could not find active section type container');
        return;
    }
    
    const singleColumnContent = activeSectionType.querySelector('.single-column-content');
    const multiColumnContent = activeSectionType.querySelector('.multi-column-content');
    const column3Field = activeSectionType.querySelector('.column-3-field');
    
    const layoutType = selectElement.value;
    
    console.log('toggleColumnFields called:', layoutType, 'Column 3 field found:', !!column3Field);
    
    if (layoutType === 'single') {
        if (singleColumnContent) singleColumnContent.style.display = 'block';
        if (multiColumnContent) multiColumnContent.style.display = 'none';
    } else {
        if (singleColumnContent) singleColumnContent.style.display = 'none';
        if (multiColumnContent) multiColumnContent.style.display = 'block';
        
        // Show/hide column 3 based on layout type
        if (column3Field) {
            if (layoutType === 'three_column') {
                column3Field.style.display = 'block';
                console.log('Showing column 3 field');
            } else {
                column3Field.style.display = 'none';
                console.log('Hiding column 3 field');
            }
        } else {
            console.error('Column 3 field not found in active section type');
        }
    }
}

// Clean up existing CKEditor instances
function cleanupCKEditorInstances() {
    document.querySelectorAll('.content-editor, .column-editor').forEach(textarea => {
        if (textarea.ckEditorInstance) {
            try {
                textarea.ckEditorInstance.destroy();
            } catch (error) {
                console.log('Error destroying CKEditor instance:', error);
            }
            textarea.ckEditorInstance = null;
            textarea.dataset.ckEditorInit = '';
        }
    });
}

// Toggle column content type fields
function toggleColumnContentType(selectElement, columnNumber) {
    console.log('toggleColumnContentType called:', selectElement.value, 'column:', columnNumber);
    
    const section = selectElement.closest('.content-section');
    const columnContainer = selectElement.closest('.column-content-container');
    
    if (!columnContainer) {
        console.error('Could not find column-content-container');
        return;
    }
    
    const contentTypeFields = columnContainer.querySelectorAll('[class^="content-type-"]');
    console.log('Found content type sections:', contentTypeFields.length);
    
    // Hide all content type fields
    contentTypeFields.forEach(field => {
        field.style.display = 'none';
        console.log('Hiding section:', field.className);
    });
    
    // Show the selected content type field
    const selectedType = selectElement.value;
    const targetField = columnContainer.querySelector(`.content-type-${selectedType}`);
    if (targetField) {
        targetField.style.display = 'block';
        console.log('Showing section:', targetField.className);
    } else {
        console.error('Could not find target section:', `.content-type-${selectedType}`);
    }
        
        // Initialize CKEditor for content type if it's a textarea
        if (selectedType === 'content') {
            const textarea = targetField.querySelector('textarea');
            if (textarea && !textarea.dataset.ckEditorInit && !textarea.ckEditorInstance) {
                ClassicEditor.create(textarea, {
                    height: '200px',
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'link', 'blockQuote', '|',
                        'undo', 'redo'
                    ]
                }).then(editor => {
                    textarea.dataset.ckEditorInit = 'true';
                    textarea.ckEditorInstance = editor;
                }).catch(error => {
                    console.error('CKEditor error:', error);
                });
            }
        }
}

// Section type toggle function - controls which section interface is shown
function toggleSectionType(selectElement) {
    console.log('toggleSectionType called:', selectElement.value);
    
    const section = selectElement.closest('.content-section');
    const allSectionTypes = section.querySelectorAll('.section-type-content');
    const selectedType = selectElement.value;
    
    console.log('Found section type containers:', allSectionTypes.length);
    
    // Hide all section type containers
    allSectionTypes.forEach(container => {
        container.style.display = 'none';
        console.log('Hiding section type:', container.className);
    });
    
    // Show the selected section type container
    const targetContainer = section.querySelector(`.section-type-${selectedType}`);
    if (targetContainer) {
        targetContainer.style.display = 'block';
        console.log('Showing section type:', targetContainer.className);
        
        // Initialize CKEditor for any content editors in the shown section
        if (selectedType === 'html') {
            const textareas = targetContainer.querySelectorAll('.content-editor, .column-editor');
            textareas.forEach(textarea => {
                if (!textarea.dataset.ckEditorInit && !textarea.ckEditorInstance) {
                    ClassicEditor.create(textarea, {
                        height: '300px',
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'underline', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'link', 'blockQuote', '|',
                            'undo', 'redo'
                        ]
                    }).then(editor => {
                        textarea.dataset.ckEditorInit = 'true';
                        textarea.ckEditorInstance = editor;
                    }).catch(error => {
                        console.error('CKEditor error:', error);
                    });
                }
            });
        }
    } else {
        console.error('Could not find section type container:', `.section-type-${selectedType}`);
    }
}

document.addEventListener('DOMContentLoaded', function() {

    // Consolidated form submission logic
    const pageForm = document.getElementById('pageForm');
    if (pageForm) {
        pageForm.addEventListener('submit', function(e) {
            console.log('Form submission started');

            // CRITICAL: Update all CKEditor instances before form submission
            const ckEditors = document.querySelectorAll('.content-editor, .column-editor');
            console.log('Found CKEditor textareas:', ckEditors.length);

            ckEditors.forEach((textarea, index) => {
                try {
                    if (textarea && textarea.ckEditorInstance) {
                        console.log(`Updating CKEditor ${index + 1} for:`, textarea.name);
                        const editorData = textarea.ckEditorInstance.getData();
                        textarea.value = editorData;
                        console.log(`Updated textarea ${textarea.name} with ${editorData.length} characters`);
                    } else {
                        console.warn(`No CKEditor instance found for textarea ${index + 1}:`, textarea ? textarea.name : 'unknown');
                    }
                } catch (error) {
                    console.error(`Error updating CKEditor ${index + 1}:`, error);
                }
            });

            // Additional fallback: Try to find any CKEditor instances that might not be stored
            try {
                document.querySelectorAll('.ck-editor').forEach((editorWrapper, index) => {
                    try {
                        const textarea = editorWrapper.previousElementSibling;
                        if (textarea && (textarea.classList.contains('content-editor') || textarea.classList.contains('column-editor'))) {
                            if (textarea.ckEditorInstance) {
                                const data = textarea.ckEditorInstance.getData();
                                textarea.value = data;
                                console.log(`Fallback updated textarea ${textarea.name} with ${data.length} characters`);
                            }
                        }
                    } catch (err) {
                        console.error(`Fallback error for editor ${index + 1}:`, err);
                    }
                });
            } catch (error) {
                console.error('Error in fallback CKEditor update:', error);
            }

            // Log form data for debugging
            try {
                const formData = new FormData(this);
                console.log('Final form data check:');
                let ckEditorCount = 0;
                for (let [key, value] of formData.entries()) {
                    if (key.includes('content') && typeof value === 'string' && value.length > 100) {
                        ckEditorCount++;
                        console.log(`${key}: ${value.substring(0, 100)}... (${value.length} chars)`);
                    }
                }
                console.log(`Total CKEditor fields with content: ${ckEditorCount}`);
            } catch (error) {
                console.error('Error logging form data:', error);
            }

            // Don't prevent default - let the form submit normally
        });
    }

    // Initialize section type visibility - show correct section type based on saved values
    document.querySelectorAll('.section-type').forEach(select => {
        // Manually trigger the toggleSectionType function for initialization
        toggleSectionType(select);
    });

    // Initialize layout type visibility - show correct layout based on saved values
    document.querySelectorAll('.layout-type').forEach(select => {
        // Manually trigger the toggleColumnFields function for initialization
        toggleColumnFields(select);
    });

    // Initialize content type visibility - show correct content type sections based on saved values
    document.querySelectorAll('.column-type').forEach(select => {
        // Trigger the change event to show the correct content type
        const event = new Event('change');
        select.dispatchEvent(event);
    });

    // Add event listeners for dynamic functionality
    document.querySelectorAll('.section-type').forEach(select => {
        select.addEventListener('change', function() {
            toggleSectionType(this);
        });
    });

    document.querySelectorAll('.layout-type').forEach(select => {
        select.addEventListener('change', function() {
            toggleColumnFields(this);
        });
    });

    document.querySelectorAll('.column-type').forEach(select => {
        select.addEventListener('change', function() {
            const columnNumber = this.closest('.column-field').classList.contains('column-2-field') ? 2 : 
                                this.closest('.column-field').classList.contains('column-3-field') ? 3 : 1;
            toggleColumnContentType(this, columnNumber);
        });
    });

    // Initialize CKEditor for existing editors AFTER all sections are properly shown
    setTimeout(() => {
        initializeCKEditor();
        // Also try again after a longer delay in case some elements become visible later
        setTimeout(() => {
            initializeCKEditor();
        }, 500);
    }, 100);

    // Status change handler
    document.getElementById('status').addEventListener('change', function() {
        const publishedAtGroup = document.getElementById('publishedAtGroup');
        if (this.value === 'scheduled') {
            publishedAtGroup.style.display = 'block';
        } else {
            publishedAtGroup.style.display = 'none';
        }
    });

    // Add section button
    document.getElementById('addSection').addEventListener('click', function() {
        sectionCount++;
        const sectionsContainer = document.getElementById('contentSections');
        const sectionHtml = createSectionHtml(sectionCount);
        sectionsContainer.insertAdjacentHTML('beforeend', sectionHtml);

        // Add event listeners for the new section
        const newSection = sectionsContainer.lastElementChild;
        newSection.querySelectorAll('.section-type').forEach(select => {
            select.addEventListener('change', function() {
                toggleSectionType(this);
            });
        });
        newSection.querySelectorAll('.layout-type').forEach(select => {
            select.addEventListener('change', function() {
                toggleColumnFields(this);
            });
        });
        newSection.querySelectorAll('.column-type').forEach(select => {
            select.addEventListener('change', function() {
                const columnNumber = this.closest('.column-field').classList.contains('column-2-field') ? 2 : 
                                    this.closest('.column-field').classList.contains('column-3-field') ? 3 : 1;
                toggleColumnContentType(this, columnNumber);
            });
        });

        // Initialize CKEditor for the new section
        initializeCKEditorForSection(sectionCount);

        // Show remove buttons if more than one section
        updateRemoveButtons();
    });

    // Initialize CKEditor
    function initializeCKEditor() {
        document.querySelectorAll('.content-editor, .column-editor').forEach(textarea => {
            // Only initialize if textarea is visible and not already initialized
            const isVisible = textarea.offsetParent !== null;
            if (isVisible && !textarea.dataset.ckEditorInit && !textarea.ckEditorInstance) {
                ClassicEditor.create(textarea, {
                    height: '300px',
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'link', 'blockQuote', '|',
                        'undo', 'redo'
                    ]
                }).then(editor => {
                    textarea.dataset.ckEditorInit = 'true';
                    textarea.ckEditorInstance = editor;
                }).catch(error => {
                    console.error('CKEditor error:', error);
                });
            }
        });
    }

    function initializeCKEditorForSection(sectionId) {
        const textareas = document.querySelectorAll(`#section-${sectionId} .content-editor, #section-${sectionId} .column-editor`);
        textareas.forEach(textarea => {
            if (textarea && !textarea.dataset.ckEditorInit && !textarea.ckEditorInstance) {
                ClassicEditor.create(textarea, {
                    height: '300px',
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'link', 'blockQuote', '|',
                        'undo', 'redo'
                    ]
                }).then(editor => {
                    textarea.dataset.ckEditorInit = 'true';
                    textarea.ckEditorInstance = editor;
                }).catch(error => {
                    console.error('CKEditor error:', error);
                });
            }
        });
        
        // Initialize content type dropdowns for new section
        const section = document.querySelector(`#section-${sectionId}`);
        if (section) {
            section.querySelectorAll('.column-type').forEach(select => {
                const event = new Event('change');
                select.dispatchEvent(event);
            });
        }
    }

    function createSectionHtml(sectionId) {
        return `
            <div class="content-section border border-gray-200 rounded-lg p-4 mb-4" id="section-${sectionId}" data-section-id="${sectionId}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Content Section ${sectionId}</h3>
                    <button type="button" class="text-red-600 hover:text-red-800 remove-section">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section Type</label>
                    <select class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 section-type" name="content_sections[${sectionId - 1}][section_type]" required>
                        <option value="html" selected>Rich Text (HTML)</option>
                        <option value="text">Plain Text</option>
                        <option value="image">Single Image</option>
                        <option value="gallery">Image Gallery</option>
                        <option value="video">Video Embed</option>
                        <option value="embed">Custom Embed</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section Title (Optional)</label>
                    <input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][section_name]" value="">
                </div>

                <!-- Rich Text (HTML) Section Type -->
                <div class="section-type-content section-type-html" style="display: block;">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Layout Type</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 layout-type" name="content_sections[${sectionId - 1}][layout_type]">
                            <option value="single" selected>Single Column</option>
                            <option value="two_column">Two Columns</option>
                            <option value="three_column">Three Columns</option>
                        </select>
                    </div>

                    <div class="content-editor-container single-column-content">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                        <textarea class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 content-editor" name="content_sections[${sectionId - 1}][content]" rows="8"></textarea>
                    </div>

                    <div class="multi-column-content" style="display: none;">
                        <!-- Column 1 -->
                        <div class="mb-6 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Column 1</h4>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[${sectionId - 1}][column_1_type]">
                                    <option value="content">Rich Text Content</option>
                                    <option value="image">Single Image</option>
                                    <option value="gallery">Image Gallery (Slider)</option>
                                    <option value="video">YouTube Video</option>
                                </select>
                            </div>
                            <div class="column-content-container">
                                <div class="content-type-content">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[${sectionId - 1}][column_1_content]" rows="6"></textarea>
                                </div>
                                <div class="content-type-image" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_1_image]" accept="image/*">
                                    <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_1_image_url]" placeholder="https://example.com/image.jpg">
                                </div>
                                <div class="content-type-gallery" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_1_gallery][]" accept="image/*" multiple>
                                    <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_1_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg"></textarea>
                                </div>
                                <div class="content-type-video" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_1_video]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6 border border-gray-200 rounded-lg p-4 column-2-field">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Column 2</h4>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[${sectionId - 1}][column_2_type]">
                                    <option value="content">Rich Text Content</option>
                                    <option value="image">Single Image</option>
                                    <option value="gallery">Image Gallery (Slider)</option>
                                    <option value="video">YouTube Video</option>
                                </select>
                            </div>
                            <div class="column-content-container">
                                <div class="content-type-content">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[${sectionId - 1}][column_2_content]" rows="6"></textarea>
                                </div>
                                <div class="content-type-image" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_2_image]" accept="image/*">
                                    <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_2_image_url]" placeholder="https://example.com/image.jpg">
                                </div>
                                <div class="content-type-gallery" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_2_gallery][]" accept="image/*" multiple>
                                    <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_2_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg"></textarea>
                                </div>
                                <div class="content-type-video" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_2_video]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Column 3 -->
                        <div class="mb-6 border border-gray-200 rounded-lg p-4 column-3-field" style="display: none;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Column 3</h4>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[${sectionId - 1}][column_3_type]">
                                    <option value="content">Rich Text Content</option>
                                    <option value="image">Single Image</option>
                                    <option value="gallery">Image Gallery (Slider)</option>
                                    <option value="video">YouTube Video</option>
                                </select>
                            </div>
                            <div class="column-content-container">
                                <div class="content-type-content">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[${sectionId - 1}][column_3_content]" rows="6"></textarea>
                                </div>
                                <div class="content-type-image" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_3_image]" accept="image/*">
                                    <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_3_image_url]" placeholder="https://example.com/image.jpg">
                                </div>
                                <div class="content-type-gallery" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_3_gallery][]" accept="image/*" multiple>
                                    <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_3_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg"></textarea>
                                </div>
                                <div class="content-type-video" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_3_video]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plain Text Section Type -->
                <div class="section-type-content section-type-text" style="display: none;">
                    <!-- Layout Type -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Layout Type</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 layout-type" name="content_sections[${sectionId - 1}][layout_type]">
                            <option value="single" selected>Single Column</option>
                            <option value="two_column">Two Columns</option>
                            <option value="three_column">Three Columns</option>
                        </select>
                    </div>

                    <!-- Single Column Content -->
                    <div class="content-editor-container single-column-content">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][content]" rows="8" placeholder="Enter plain text content here..."></textarea>
                    </div>

                    <!-- Multi-Column Content -->
                    <div class="multi-column-content" style="display: none;">
                        <!-- Column 1 -->
                        <div class="mb-6 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Column 1</h4>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_1_content]" rows="6" placeholder="Enter plain text content for column 1..."></textarea>
                        </div>
                        
                        <!-- Column 2 -->
                        <div class="mb-6 border border-gray-200 rounded-lg p-4 column-2-field">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Column 2</h4>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_2_content]" rows="6" placeholder="Enter plain text content for column 2..."></textarea>
                        </div>
                        
                        <!-- Column 3 -->
                        <div class="mb-6 border border-gray-200 rounded-lg p-4 column-3-field" style="display: none;">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Column 3</h4>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_3_content]" rows="6" placeholder="Enter plain text content for column 3..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Single Image Section Type -->
                <div class="section-type-content section-type-image" style="display: none;">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][image_file]" accept="image/*">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Or Image URL</label>
                        <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][image_url]" placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image Caption (Optional)</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][image_caption]" placeholder="Enter image caption...">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][image_alt]" placeholder="Enter alt text for accessibility...">
                    </div>
                </div>

                <!-- Image Gallery Section Type -->
                <div class="section-type-content section-type-gallery" style="display: none;">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][gallery_files][]" accept="image/*" multiple>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Or Gallery Image URLs (one per line)</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][gallery_urls]" rows="6" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Caption (Optional)</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][gallery_caption]" placeholder="Enter gallery caption...">
                    </div>
                </div>

                <!-- Video Embed Section Type -->
                <div class="section-type-content section-type-video" style="display: none;">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                        <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][video_url]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID or https://vimeo.com/VIDEO_ID">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Video Caption (Optional)</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][video_caption]" placeholder="Enter video caption...">
                    </div>
                </div>

                <!-- Custom Embed Section Type -->
                <div class="section-type-content section-type-embed" style="display: none;">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Embed Code</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][embed_code]" rows="6" placeholder="Paste your embed code here (iframe, script tags, etc.)..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Embed Caption (Optional)</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][embed_caption]" placeholder="Enter embed caption...">
                    </div>
                </div>

                <input type="hidden" name="content_sections[${sectionId - 1}][sort_order]" value="${sectionId - 1}">
            </div>
        `;
    }

    function updateRemoveButtons() {
        const sections = document.querySelectorAll('.content-section');
        const removeButtons = document.querySelectorAll('.remove-section');

        if (sections.length > 1) {
            removeButtons.forEach(button => button.classList.remove('hidden'));
        } else {
            removeButtons.forEach(button => button.classList.add('hidden'));
        }
    }

    // Event delegation for remove buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-section') || e.target.closest('.remove-section')) {
            const section = e.target.closest('.content-section');
            if (section) {
                // Destroy CKEditor instance
                const editor = section.querySelector('.content-editor');
                if (editor && editor.ckEditorInstance) {
                    editor.ckEditorInstance.destroy();
                }

                section.remove();
                updateRemoveButtons();
                reorderSections();
            }
        }
    });

    function reorderSections() {
        const sections = document.querySelectorAll('.content-section');
        sections.forEach((section, index) => {
            const sectionId = index + 1;
            section.id = `section-${sectionId}`;
            section.setAttribute('data-section-id', sectionId);

            // Update form field names
            const inputs = section.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                }
            });

            // Update section title
            const title = section.querySelector('h3');
            if (title) {
                title.textContent = `Content Section ${sectionId}`;
            }
        });
    }
});
</script>
@endpush