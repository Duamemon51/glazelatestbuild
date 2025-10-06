@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 p-0">

        <div class="hero-section">
    @php
        // Use the featured_image field for hero image
        $heroImage = $page->featured_image;
    @endphp
    <img
        src="{{ $heroImage ? asset('storage/' . $heroImage) : asset('images/default.jpg') }}"
        class="img-fluid w-100"
        alt="{{ $page->title }}">
</div>



           <div class="container py-1">
    <div class="row text-center justify-content-center">
        <div class="col-6 col-md-3 mb-3">
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ asset('images/download.svg') }}" alt="Download Icon" width="35" height="35" class="me-2">
                <small style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">All printing costs included</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ asset('images/download (1).svg') }}" alt="Delivery Icon" width="40" height="40" class="me-2">
                <small style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">Quick Delivery</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ asset('images/ekomi_gold_small.webp') }}" alt="Rating Icon" width="26" height="26" class="me-2">
                <small style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">4.8/5.0 out of 3,456 Customer Reviews</small>
            </div>
        </div>
    </div>
</div>


            <div class="container text-center py-5">
                <h2 class="mb-3" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">{{ $page->title }}</h2>
                @if($page->excerpt)
                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; margin-bottom: 0.5rem;">{{ $page->excerpt }}</p>
                @endif
            </div>

            <!-- Page Content -->
            <div class="page-content">
                @forelse($page->contentSections as $section)
                    <div class="content-section mb-5">
                        @if($section->section_name)
                            <h5 class="col-12 mb-4" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">
                                {{ strtoupper($section->section_name) }}
                            </h5>
                        @endif

                        @php
                            $layoutType = $section->layout_type ?? 'single';
                        @endphp

                        @if($layoutType === 'single')
                            {{-- Single Column Layout --}}
                            @switch($section->section_type)
                                @case('html')
                                    <div class="content-html" style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">
                                        {!! $section->content !!}
                                    </div>
                                    @break

                                @case('text')
                                    <div class="content-text" style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">
                                        <p style="margin-bottom: 0.5rem;">{{ $section->content }}</p>
                                    </div>
                                    @break

                                @case('image')
                                    @php
                                        $imageUrl = $section->data['image_url'] ?? $section->content ?? null;
                                        $imageCaption = $section->data['image_caption'] ?? null;
                                        $imageAlt = $section->data['image_alt'] ?? $section->section_name ?? 'Page image';
                                    @endphp
                                    
                                    @if($imageUrl)
                                        <div class="text-center mb-3">
                                            <img src="{{ asset($imageUrl) }}" alt="{{ $imageAlt }}" class="img-fluid rounded shadow">
                                        </div>
                                        @if($imageCaption)
                                            <p class="text-center text-muted small">{{ $imageCaption }}</p>
                                        @endif
                                    @endif
                                    @break

                                @case('gallery')
                                    @php
                                        $galleryImages = [];
                                        $galleryCaption = $section->data['gallery_caption'] ?? null;
                                        
                                        // Handle gallery URLs from data field
                                        if ($section->data && isset($section->data['gallery_urls'])) {
                                            $urls = explode("\n", $section->data['gallery_urls']);
                                            $galleryImages = array_filter(array_map('trim', $urls));
                                        }
                                        
                                        // Handle uploaded gallery images (new uploaded files)
                                        if ($section->data && isset($section->data['gallery_paths'])) {
                                            foreach ($section->data['gallery_paths'] as $path) {
                                                $galleryImages[] = 'storage/' . $path;
                                            }
                                        }
                                        
                                        // Also check if images are stored in data array format
                                        if ($section->data && is_array($section->data) && isset($section->data['images'])) {
                                            $galleryImages = array_merge($galleryImages, $section->data['images']);
                                        }
                                    @endphp
                                    
                                    @if($galleryImages)
                                        <div class="row g-3 mb-3">
                                            @foreach($galleryImages as $image)
                                                <div class="col-md-4">
                                                    <img src="{{ asset(trim($image)) }}" alt="Gallery image" class="img-fluid rounded shadow">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($galleryCaption)
                                            <p class="text-center text-muted small">{{ $galleryCaption }}</p>
                                        @endif
                                    @endif
                                    @break

                                @case('video')
                                    @php
                                        $videoUrl = $section->data['video_url'] ?? $section->content ?? null;
                                        $videoCaption = $section->data['video_caption'] ?? null;
                                        $embedCode = null;
                                        
                                        if ($videoUrl) {
                                            // Convert YouTube URLs to embed format
                                            if (strpos($videoUrl, 'youtube.com/watch') !== false) {
                                                preg_match('/[?&]v=([^&]+)/', $videoUrl, $matches);
                                                if (isset($matches[1])) {
                                                    $embedCode = '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' . $matches[1] . '" frameborder="0" allowfullscreen></iframe>';
                                                }
                                            }
                                            // Convert YouTube short URLs
                                            elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                                                preg_match('/youtu\.be\/([^?]+)/', $videoUrl, $matches);
                                                if (isset($matches[1])) {
                                                    $embedCode = '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' . $matches[1] . '" frameborder="0" allowfullscreen></iframe>';
                                                }
                                            }
                                            // Convert Vimeo URLs
                                            elseif (strpos($videoUrl, 'vimeo.com/') !== false) {
                                                preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches);
                                                if (isset($matches[1])) {
                                                    $embedCode = '<iframe width="100%" height="400" src="https://player.vimeo.com/video/' . $matches[1] . '" frameborder="0" allowfullscreen></iframe>';
                                                }
                                            }
                                            // Handle direct video files
                                            elseif (preg_match('/\.(mp4|webm|ogg)$/i', $videoUrl)) {
                                                $embedCode = '<video class="img-fluid rounded shadow" controls style="max-height:500px; width: 100%; object-fit: cover;"><source src="' . asset($videoUrl) . '" type="video/mp4">Your browser does not support the video tag.</video>';
                                            }
                                        }
                                    @endphp
                                    
                                    @if($embedCode)
                                        <div class="text-center mb-3">
                                            {!! $embedCode !!}
                                        </div>
                                        @if($videoCaption)
                                            <p class="text-center text-muted small">{{ $videoCaption }}</p>
                                        @endif
                                    @endif
                                    @break

                                @case('embed')
                                    @php
                                        $embedCode = $section->data['embed_code'] ?? $section->content ?? null;
                                        $embedCaption = $section->data['embed_caption'] ?? null;
                                    @endphp
                                    
                                    @if($embedCode)
                                        <div class="embed-responsive mb-3">
                                            {!! $embedCode !!}
                                        </div>
                                        @if($embedCaption)
                                            <p class="text-center text-muted small">{{ $embedCaption }}</p>
                                        @endif
                                    @endif
                                    @break

                                @default
                                    <div class="content-default" style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">
                                        {!! $section->content !!}
                                    </div>
                            @endswitch
                        @else
                            {{-- Multi-Column Layout --}}
                            <div class="row">
                                @php
                                    $columnClass = $layoutType === 'two_column' ? 'col-md-6' : 'col-md-4';
                                    $columns = $section->getAllColumnContents();
                                @endphp

                                @foreach($columns as $index => $columnContent)
                                    @if($columnContent)
                                        <div class="{{ $columnClass }} mb-4">
                                            <div class="column-content" style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">
                                                {!! $columnContent !!}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">
                            <i class="fas fa-file-alt fa-3x mb-3"></i>
                            <p>This page is currently being updated. Please check back soon.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-content {
        font-size: 1.1rem;
        line-height: 1.7;
        font-family: 'Instrument Sans', sans-serif;
        color: rgb(51, 50, 49);
    }

    .content-section h2 {
        border-bottom: 2px solid rgb(229, 226, 223);
        padding-bottom: 0.5rem;
        color: rgb(26, 26, 25);
        font-family: 'Instrument Sans', sans-serif;
    }

    .content-html h3 {
        color: rgb(26, 26, 25);
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-family: 'Instrument Sans', sans-serif;
    }

    .content-html ul {
        padding-left: 1.5rem;
    }

    .content-html li {
        margin-bottom: 0.5rem;
        color: rgb(51, 50, 49);
        font-family: 'Instrument Sans', sans-serif;
    }

    /* Column Layout Styles */
    .column-content {
        padding: 1rem;
        border-radius: 0.5rem;
        background-color: rgba(248, 250, 252, 0.5);
        height: 100%;
    }

    .column-content h3,
    .column-content h4,
    .column-content h5 {
        color: rgb(26, 26, 25);
        margin-bottom: 1rem;
        font-family: 'Instrument Sans', sans-serif;
    }

    .column-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .column-content ul {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .column-content li {
        margin-bottom: 0.5rem;
    }

    /* Responsive column layout */
    @media (max-width: 768px) {
        .row > [class*="col-"] {
            margin-bottom: 1.5rem;
        }
    }
    }

    .content-html strong {
        color: rgb(26, 26, 25);
    }

    .embed-responsive {
        position: relative;
        display: block;
        width: 100%;
        padding: 0;
        overflow: hidden;
    }

    .embed-responsive iframe {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        height: 100%;
        width: 100%;
        border: 0;
    }
</style>
@endpush