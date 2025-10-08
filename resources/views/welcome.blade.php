@extends('layouts.app')

@section('title', 'Home | Ricona')

@section('content')

    {{-- Hero Section Include --}}
    @include('components.hero')


 @include('components.favorites')

  @include('components.categories')
    {{-- Design Section Include --}}
    @include('components.design')
 @include('components.top-sellers')

    {{-- Page Content Sections --}}
    @if(isset($homePage) && $homePage && $homePage->contentSections->count() > 0)
        <div class="container my-5">
            @foreach($homePage->contentSections as $section)
                <div class="content-section mb-5">
                    @if($section->section_name)
                        <h3 class="text-center mb-4" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">
                            {{ $section->section_name }}
                        </h3>
                    @endif

                    @php
                        $layoutType = $section->layout_type ?? 'single';
                    @endphp

                    @if($layoutType === 'single')
                        {{-- Single Column Layout --}}
                        @switch($section->section_type)
                            @case('html')
                                <div class="content-html text-center" style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; max-width: 800px; margin: 0 auto;">
                                    {!! $section->content !!}
                                </div>
                                @break

                            @case('text')
                                <div class="content-text text-center" style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; max-width: 800px; margin: 0 auto;">
                                    <p>{{ $section->content }}</p>
                                </div>
                                @break

                            @case('image')
                                @php
                                    $imageUrl = $section->data['image_url'] ?? $section->content ?? null;
                                    $imageCaption = $section->data['image_caption'] ?? null;
                                    $imageAlt = $section->data['image_alt'] ?? $section->section_name ?? 'Page image';
                                @endphp

                                @if($imageUrl)
                                    <div class="text-center mb-4">
                                        <img src="{{ asset($imageUrl) }}" alt="{{ $imageAlt }}" class="img-fluid rounded shadow" style="max-width: 100%; height: auto;">
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
                                    <div class="row g-3 mb-3 justify-content-center">
                                        @foreach($galleryImages as $image)
                                            <div class="col-md-4 col-sm-6">
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
                                                $embedCode = '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' . $matches[1] . '?rel=0" frameborder="0" allowfullscreen></iframe>';
                                            }
                                        }
                                        // Convert YouTube short URLs
                                        elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                                            preg_match('/youtu\.be\/([^?]+)/', $videoUrl, $matches);
                                            if (isset($matches[1])) {
                                                $embedCode = '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' . $matches[1] . '?rel=0" frameborder="0" allowfullscreen></iframe>';
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
                                    <div class="text-center mb-4">
                                        <div class="video-container" style="max-width: 800px; margin: 0 auto;">
                                            {!! $embedCode !!}
                                        </div>
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
                                    <div class="embed-responsive mb-3 text-center" style="max-width: 800px; margin: 0 auto;">
                                        {!! $embedCode !!}
                                    </div>
                                    @if($embedCaption)
                                        <p class="text-center text-muted small">{{ $embedCaption }}</p>
                                    @endif
                                @endif
                                @break
                        @endswitch
                    @endif
                </div>
            @endforeach
        </div>
    @endif

@endsection
