@extends('layouts.admin')

@section('title', 'Create Page')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justi                    <!-- Featured Image -->
                    <div class="mb-4">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('featured_image') ? 'border-red-500' : '' }}" id="featured_image" name="featured_image" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Upload an image file (JPG, PNG, GIF, WebP). Max 2MB.</p>
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>en items-center">
        <h1 class="text-3xl font-bold text-gray-900">Create Page</h1>
        <a href="{{ route('admin.cms.pages.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Pages
        </a>
    </div>

    <form action="{{ route('admin.cms.pages.store') }}" method="POST" id="pageForm" enctype="multipart/form-data">
        @csrf

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
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('title') ? 'border-red-500' : '' }}" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-6">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('excerpt') ? 'border-red-500' : '' }}" id="excerpt" name="excerpt" rows="3">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Brief description of the page content (optional).</p>
                    </div>

                    <!-- Content Sections -->
                    <div id="contentSections">
                        <div class="content-section border border-gray-200 rounded-lg p-4 mb-4" data-section-id="1">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Content Section 1</h3>
                                <button type="button" class="text-red-600 hover:text-red-800 hidden remove-section">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>

                            <!-- Section Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Section Type</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 section-type" name="content_sections[0][section_type]" onchange="toggleSectionType(this)" required>
                                    <option value="html">Rich Text (HTML)</option>
                                    <option value="text">Plain Text</option>
                                    <option value="image">Single Image</option>
                                    <option value="gallery">Image Gallery</option>
                                    <option value="video">Video Embed</option>
                                    <option value="embed">Custom Embed</option>
                                </select>
                            </div>

                            <!-- Section Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Section Title (Optional)</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][section_name]" value="">
                            </div>

                            <!-- HTML/Rich Text Section Type (Default) -->
                            <div class="section-type-content section-type-html">
                                <!-- Layout Type -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Layout Type</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 layout-type" name="content_sections[0][layout_type]" onchange="toggleColumnFields(this)">
                                        <option value="single">Single Column</option>
                                        <option value="two_column">Two Columns</option>
                                        <option value="three_column">Three Columns</option>
                                    </select>
                                </div>

                            <!-- Single Column Content -->
                            <div class="content-editor-container single-column-content">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 content-editor" name="content_sections[0][content]" rows="8"></textarea>
                            </div>

                            <!-- Multi-Column Content -->
                            <div class="multi-column-content" style="display: none;">
                                <!-- Column 1 -->
                                <div class="mb-6 border border-gray-200 rounded-lg p-4">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">Column 1</h4>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[0][column_1_type]" onchange="toggleColumnContentType(this, 1)">
                                            <option value="content">Rich Text Content</option>
                                            <option value="image">Single Image</option>
                                            <option value="gallery">Image Gallery (Slider)</option>
                                            <option value="video">YouTube Video</option>
                                        </select>
                                    </div>
                                    <div class="column-content-container">
                                        <div class="content-type-content">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[0][column_1_content]" rows="6"></textarea>
                                        </div>
                                        <div class="content-type-image" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_1_image]" accept="image/*">
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[0][column_1_image_url]" placeholder="https://example.com/image.jpg">
                                        </div>
                                        <div class="content-type-gallery" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_1_gallery][]" accept="image/*" multiple>
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[0][column_1_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg
https://example.com/image2.jpg
https://example.com/image3.jpg"></textarea>
                                        </div>
                                        <div class="content-type-video" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_1_video]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 2 -->
                                <div class="mb-6 border border-gray-200 rounded-lg p-4 column-2-field">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">Column 2</h4>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[0][column_2_type]" onchange="toggleColumnContentType(this, 2)">
                                            <option value="content">Rich Text Content</option>
                                            <option value="image">Single Image</option>
                                            <option value="gallery">Image Gallery (Slider)</option>
                                            <option value="video">YouTube Video</option>
                                        </select>
                                    </div>
                                    <div class="column-content-container">
                                        <div class="content-type-content">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[0][column_2_content]" rows="6"></textarea>
                                        </div>
                                        <div class="content-type-image" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_2_image]" accept="image/*">
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[0][column_2_image_url]" placeholder="https://example.com/image.jpg">
                                        </div>
                                        <div class="content-type-gallery" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_2_gallery][]" accept="image/*" multiple>
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[0][column_2_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg
https://example.com/image2.jpg
https://example.com/image3.jpg"></textarea>
                                        </div>
                                        <div class="content-type-video" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_2_video]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 3 -->
                                <div class="mb-6 border border-gray-200 rounded-lg p-4 column-3-field" style="display: none;">
                                    <h4 class="text-md font-medium text-gray-800 mb-3">Column 3</h4>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[0][column_3_type]" onchange="toggleColumnContentType(this, 3)">
                                            <option value="content">Rich Text Content</option>
                                            <option value="image">Single Image</option>
                                            <option value="gallery">Image Gallery (Slider)</option>
                                            <option value="video">YouTube Video</option>
                                        </select>
                                    </div>
                                    <div class="column-content-container">
                                        <div class="content-type-content">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-editor" name="content_sections[0][column_3_content]" rows="6"></textarea>
                                        </div>
                                        <div class="content-type-image" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_3_image]" accept="image/*">
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URL:</p>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[0][column_3_image_url]" placeholder="https://example.com/image.jpg">
                                        </div>
                                        <div class="content-type-gallery" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_3_gallery][]" accept="image/*" multiple>
                                            <p class="text-sm text-gray-500 mt-1">Or enter image URLs (one per line):</p>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[0][column_3_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg
https://example.com/image2.jpg
https://example.com/image3.jpg"></textarea>
                                        </div>
                                        <div class="content-type-video" style="display: none;">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][column_3_video]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!-- Plain Text Section Type -->
                            <div class="section-type-content section-type-text" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Plain Text Content</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][text_content]" rows="8" placeholder="Enter plain text content here..."></textarea>
                            </div>

                            <!-- Single Image Section Type -->
                            <div class="section-type-content section-type-image" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][image_file]" accept="image/*">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Or Image URL</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][image_url]" placeholder="https://example.com/image.jpg">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Image Caption (Optional)</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][image_caption]" placeholder="Enter image caption...">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][image_alt]" placeholder="Enter alt text for accessibility...">
                                </div>
                            </div>

                            <!-- Image Gallery Section Type -->
                            <div class="section-type-content section-type-gallery" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][gallery_files][]" accept="image/*" multiple>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Or Gallery Image URLs (one per line)</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][gallery_urls]" rows="6" placeholder="https://example.com/image1.jpg
https://example.com/image2.jpg
https://example.com/image3.jpg"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Caption (Optional)</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][gallery_caption]" placeholder="Enter gallery caption...">
                                </div>
                            </div>

                            <!-- Video Embed Section Type -->
                            <div class="section-type-content section-type-video" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][video_url]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID or https://vimeo.com/VIDEO_ID">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Video Caption (Optional)</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][video_caption]" placeholder="Enter video caption...">
                                </div>
                            </div>

                            <!-- Custom Embed Section Type -->
                            <div class="section-type-content section-type-embed" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Embed Code</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][embed_code]" rows="6" placeholder="Paste your embed code here (iframe, script tags, etc.)..."></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Embed Caption (Optional)</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[0][embed_caption]" placeholder="Enter embed caption...">
                                </div>
                            </div>

                            <input type="hidden" name="content_sections[0][sort_order]" value="0">
                        </div>
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
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publish Date -->
                    <div class="mb-4" id="publishedAtGroup" style="display: none;">
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                        <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('published_at') ? 'border-red-500' : '' }}" id="published_at" name="published_at" value="{{ old('published_at') }}">
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visibility -->
                    <div class="mb-4">
                        <label for="visibility" class="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('visibility') ? 'border-red-500' : '' }}" id="visibility" name="visibility" required>
                            <option value="public" {{ old('visibility', 'public') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                            <option value="password" {{ old('visibility') == 'password' ? 'selected' : '' }}>Password Protected</option>
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
                            <option value="default" {{ old('template', 'default') == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="full-width" {{ old('template') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                            <option value="landing" {{ old('template') == 'landing' ? 'selected' : '' }}>Landing Page</option>
                        </select>
                        @error('template')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-4">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Image URL</label>
                        <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('featured_image') ? 'border-red-500' : '' }}" id="featured_image" name="featured_image" value="{{ old('featured_image') }}">
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
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="seo_title" name="seo_title" value="{{ old('seo_title') }}" maxlength="60">
                        <p class="mt-1 text-sm text-gray-500">Leave blank to use page title. Max 60 characters.</p>
                    </div>

                    <!-- SEO Description -->
                    <div class="mb-4">
                        <label for="seo_description" class="block text-sm font-medium text-gray-700 mb-2">SEO Description</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="seo_description" name="seo_description" rows="3" maxlength="160">{{ old('seo_description') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Leave blank to use excerpt. Max 160 characters.</p>
                    </div>

                    <!-- SEO Keywords -->
                    <div class="mb-4">
                        <label for="seo_keywords" class="block text-sm font-medium text-gray-700 mb-2">SEO Keywords</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords') }}">
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
                    Create Page
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
let sectionCount = 1;

// Column layout toggle function - made global for inline event handlers
function toggleColumnFields(selectElement) {
    const section = selectElement.closest('.content-section');
    const singleColumnContent = section.querySelector('.single-column-content');
    const multiColumnContent = section.querySelector('.multi-column-content');
    const column3Field = section.querySelector('.column-3-field');
    
    const layoutType = selectElement.value;
    
    if (layoutType === 'single') {
        singleColumnContent.style.display = 'block';
        multiColumnContent.style.display = 'none';
    } else {
        singleColumnContent.style.display = 'none';
        multiColumnContent.style.display = 'block';
        
        // Show/hide column 3 based on layout type
        if (layoutType === 'three_column') {
            column3Field.style.display = 'block';
        } else {
            column3Field.style.display = 'none';
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
                if (!textarea.dataset.ckEditorInit) {
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

// Content type toggle function for columns
function toggleColumnContentType(selectElement, columnNumber) {
    console.log('toggleColumnContentType called:', selectElement.value, 'column:', columnNumber);
    
    const container = selectElement.closest('.column-content-container');
    if (!container) {
        console.error('Could not find column-content-container');
        return;
    }
    
    const allContentTypes = container.querySelectorAll('[class^="content-type-"]');
    const selectedType = selectElement.value;
    
    console.log('Found content type sections:', allContentTypes.length);
    console.log('Selected type:', selectedType);
    
    // Hide all content type sections
    allContentTypes.forEach(section => {
        section.style.display = 'none';
        console.log('Hiding section:', section.className);
    });
    
    // Show the selected content type section
    const targetSection = container.querySelector(`.content-type-${selectedType}`);
    if (targetSection) {
        targetSection.style.display = 'block';
        console.log('Showing section:', targetSection.className);
    } else {
        console.error('Could not find target section:', `.content-type-${selectedType}`);
    }
}

document.addEventListener('DOMContentLoaded', function() {

    // Initialize CKEditor for existing editors
    initializeCKEditor();

    // Initialize content type visibility - show default content type sections
    document.querySelectorAll('.column-type').forEach(select => {
        // Trigger the change event to show the default content type
        const event = new Event('change');
        select.dispatchEvent(event);
    });

    // Initialize section type visibility - show default section type (html)
    document.querySelectorAll('.section-type').forEach(select => {
        // Trigger the change event to show the default section type
        const event = new Event('change');
        select.dispatchEvent(event);
    });

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

        // Initialize CKEditor for the new section
        initializeCKEditorForSection(sectionCount);

        // Show remove buttons if more than one section
        updateRemoveButtons();
    });

    // Initialize CKEditor
    function initializeCKEditor() {
        document.querySelectorAll('.content-editor, .column-editor').forEach(textarea => {
            if (!textarea.dataset.ckEditorInit) {
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
        // Initialize all editors in the new section
        const section = document.querySelector(`#section-${sectionId}`);
        if (section) {
            section.querySelectorAll('.content-editor, .column-editor').forEach(textarea => {
                if (!textarea.dataset.ckEditorInit) {
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
                        <option value="html">Rich Text (HTML)</option>
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

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Layout Type</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 layout-type" name="content_sections[${sectionId - 1}][layout_type]" onchange="toggleColumnFields(this)">
                        <option value="single">Single Column</option>
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
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[${sectionId - 1}][column_1_type]" onchange="toggleColumnContentType(this, 1)">
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
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_1_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg\nhttps://example.com/image2.jpg\nhttps://example.com/image3.jpg"></textarea>
                            </div>
                            <div class="content-type-video" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_1_video]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="mb-6 border border-gray-200 rounded-lg p-4 column-2-field">
                        <h4 class="text-md font-medium text-gray-800 mb-3">Column 2</h4>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[${sectionId - 1}][column_2_type]" onchange="toggleColumnContentType(this, 2)">
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
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_2_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg\nhttps://example.com/image2.jpg\nhttps://example.com/image3.jpg"></textarea>
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
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 column-type" name="content_sections[${sectionId - 1}][column_3_type]" onchange="toggleColumnContentType(this, 3)">
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
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1" name="content_sections[${sectionId - 1}][column_3_gallery_urls]" rows="4" placeholder="https://example.com/image1.jpg\nhttps://example.com/image2.jpg\nhttps://example.com/image3.jpg"></textarea>
                            </div>
                            <div class="content-type-video" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Video URL</label>
                                <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="content_sections[${sectionId - 1}][column_3_video]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                            </div>
                        </div>
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