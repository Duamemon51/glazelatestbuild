<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageContent;
use App\Models\SeoData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Display a listing of pages.
     */
    public function index(Request $request)
    {
        $query = Page::with('author');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.cms.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        return view('admin.cms.pages.create');
    }

    /**
     * Store a newly created page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|string|in:draft,published,scheduled,archived',
            'visibility' => 'required|string|in:public,private,password',
            'published_at' => 'nullable|date|after:now',
            'template' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'settings' => 'nullable|array',
            'content_sections' => 'nullable|array',
            'content_sections.*.section_type' => 'required|string|in:text,html,image,gallery,video,embed',
            'content_sections.*.section_name' => 'nullable|string|max:255',
            'content_sections.*.layout_type' => 'nullable|string|in:single,two_column,three_column',
            'content_sections.*.content' => 'nullable|string',
            'content_sections.*.column_1_content' => 'nullable|string',
            'content_sections.*.column_2_content' => 'nullable|string',
            'content_sections.*.column_3_content' => 'nullable|string',
            'content_sections.*.column_1_type' => 'nullable|string|in:content,image,gallery,video',
            'content_sections.*.column_2_type' => 'nullable|string|in:content,image,gallery,video',
            'content_sections.*.column_3_type' => 'nullable|string|in:content,image,gallery,video',
            'content_sections.*.column_1_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'content_sections.*.column_2_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'content_sections.*.column_3_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'content_sections.*.column_1_image_url' => 'nullable|url',
            'content_sections.*.column_2_image_url' => 'nullable|url',
            'content_sections.*.column_3_image_url' => 'nullable|url',
            'content_sections.*.column_1_gallery' => 'nullable|array',
            'content_sections.*.column_2_gallery' => 'nullable|array',
            'content_sections.*.column_3_gallery' => 'nullable|array',
            'content_sections.*.column_1_gallery.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'content_sections.*.column_2_gallery.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'content_sections.*.column_3_gallery.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'content_sections.*.column_1_gallery_urls' => 'nullable|string',
            'content_sections.*.column_2_gallery_urls' => 'nullable|string',
            'content_sections.*.column_3_gallery_urls' => 'nullable|string',
            'content_sections.*.column_1_video' => 'nullable|url',
            'content_sections.*.column_2_video' => 'nullable|url',
            'content_sections.*.column_3_video' => 'nullable|url',
            'content_sections.*.image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'content_sections.*.gallery' => 'nullable|array',
            'content_sections.*.gallery.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'content_sections.*.video_url' => 'nullable|url',
            'content_sections.*.video_caption' => 'nullable|string|max:255',
            'content_sections.*.embed_code' => 'nullable|string',
            'content_sections.*.embed_caption' => 'nullable|string|max:255',
            'content_sections.*.image_url' => 'nullable|url',
            'content_sections.*.image_caption' => 'nullable|string|max:255',
            'content_sections.*.image_alt' => 'nullable|string|max:255',
            'content_sections.*.gallery_urls' => 'nullable|string',
            'content_sections.*.gallery_caption' => 'nullable|string|max:255',
            'content_sections.*.sort_order' => 'required|integer',
            'content_sections.*.settings' => 'nullable|array',
            'content_sections.*.column_settings' => 'nullable|array',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title']);
            $validated['author_id'] = Auth::check() ? Auth::id() : null;

            // Set published_at for published status
            if ($validated['status'] === Page::STATUS_PUBLISHED && !isset($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
            }

            $page = Page::create($validated);

            // Create content sections
            if (!empty($validated['content_sections'])) {
                foreach ($validated['content_sections'] as $index => $sectionData) {
                    // Process special content type fields into data JSON field
                    $dataFields = [];
                    
                    // Handle main section image upload
                    if ($request->hasFile("content_sections.{$index}.image")) {
                        $dataFields['image_path'] = $request->file("content_sections.{$index}.image")->store('pages/content', 'public');
                    }
                    
                    // Handle main section gallery uploads
                    if ($request->hasFile("content_sections.{$index}.gallery")) {
                        $galleryPaths = [];
                        foreach ($request->file("content_sections.{$index}.gallery") as $galleryImage) {
                            $galleryPaths[] = $galleryImage->store('pages/gallery', 'public');
                        }
                        $dataFields['gallery_paths'] = $galleryPaths;
                    }
                    
                    // Handle column types and column-specific content
                    for ($col = 1; $col <= 3; $col++) {
                        if (isset($sectionData["column_{$col}_type"])) {
                            $dataFields["column_{$col}_type"] = $sectionData["column_{$col}_type"];
                            unset($sectionData["column_{$col}_type"]);
                        }
                        
                        // Handle column image uploads
                        if ($request->hasFile("content_sections.{$index}.column_{$col}_image")) {
                            $dataFields["column_{$col}_image_path"] = $request->file("content_sections.{$index}.column_{$col}_image")->store('pages/content', 'public');
                        }
                        
                        // Handle column image URLs
                        if (isset($sectionData["column_{$col}_image_url"])) {
                            $dataFields["column_{$col}_image_url"] = $sectionData["column_{$col}_image_url"];
                            unset($sectionData["column_{$col}_image_url"]);
                        }
                        
                        // Handle column gallery uploads
                        if ($request->hasFile("content_sections.{$index}.column_{$col}_gallery")) {
                            $galleryPaths = [];
                            foreach ($request->file("content_sections.{$index}.column_{$col}_gallery") as $galleryImage) {
                                $galleryPaths[] = $galleryImage->store('pages/gallery', 'public');
                            }
                            $dataFields["column_{$col}_gallery_paths"] = $galleryPaths;
                        }
                        
                        // Handle column gallery URLs
                        if (isset($sectionData["column_{$col}_gallery_urls"])) {
                            $dataFields["column_{$col}_gallery_urls"] = $sectionData["column_{$col}_gallery_urls"];
                            unset($sectionData["column_{$col}_gallery_urls"]);
                        }
                        
                        // Handle column videos
                        if (isset($sectionData["column_{$col}_video"])) {
                            $dataFields["column_{$col}_video"] = $sectionData["column_{$col}_video"];
                            unset($sectionData["column_{$col}_video"]);
                        }
                    }
                    
                    // Handle video content
                    if (isset($sectionData['video_url'])) {
                        $dataFields['video_url'] = $sectionData['video_url'];
                        unset($sectionData['video_url']);
                    }
                    if (isset($sectionData['video_caption'])) {
                        $dataFields['video_caption'] = $sectionData['video_caption'];
                        unset($sectionData['video_caption']);
                    }
                    
                    // Handle embed content
                    if (isset($sectionData['embed_code'])) {
                        $dataFields['embed_code'] = $sectionData['embed_code'];
                        unset($sectionData['embed_code']);
                    }
                    if (isset($sectionData['embed_caption'])) {
                        $dataFields['embed_caption'] = $sectionData['embed_caption'];
                        unset($sectionData['embed_caption']);
                    }
                    
                    // Handle image content
                    if (isset($sectionData['image_url'])) {
                        $dataFields['image_url'] = $sectionData['image_url'];
                        unset($sectionData['image_url']);
                    }
                    if (isset($sectionData['image_caption'])) {
                        $dataFields['image_caption'] = $sectionData['image_caption'];
                        unset($sectionData['image_caption']);
                    }
                    if (isset($sectionData['image_alt'])) {
                        $dataFields['image_alt'] = $sectionData['image_alt'];
                        unset($sectionData['image_alt']);
                    }
                    
                    // Handle gallery content
                    if (isset($sectionData['gallery_urls'])) {
                        $dataFields['gallery_urls'] = $sectionData['gallery_urls'];
                        unset($sectionData['gallery_urls']);
                    }
                    if (isset($sectionData['gallery_caption'])) {
                        $dataFields['gallery_caption'] = $sectionData['gallery_caption'];
                        unset($sectionData['gallery_caption']);
                    }
                    
                    // Add data fields to section data if any exist
                    if (!empty($dataFields)) {
                        $sectionData['data'] = $dataFields;
                    }
                    
                    $page->contentSections()->create($sectionData);
                }
            }

            // Create SEO data
            $page->seoData()->create([
                'title' => $validated['seo_title'] ?? null,
                'description' => $validated['seo_description'] ?? null,
                'keywords' => $validated['seo_keywords'] ?? null,
            ]);
        });

        return redirect()->route('admin.cms.pages.index')
                        ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified page.
     */
    public function show($slug)
    {
        // Fetch page by slug with relationships
        $page = Page::with(['contentSections', 'seoData', 'author'])->where('slug', $slug)->firstOrFail();

        // Check if page is published and public
        if (!$page->isPublished()) {
            abort(404);
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        $page->load(['contentSections', 'seoData']);

        return view('admin.cms.pages.edit', compact('page'));
    }

    /**
     * Update the specified page.
     */
    public function update(Request $request, Page $page)
    {
        // Debug logging
        Log::info('PageController update called', [
            'page_id' => $page->id,
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'has_content_sections' => $request->has('content_sections'),
            'content_sections_count' => is_array($request->input('content_sections')) ? count($request->input('content_sections')) : 0
        ]);

        // Validate the input
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content_sections' => 'nullable|array',
            'content_sections.*.section_type' => 'required|string|in:text,html,image,gallery,video,embed',
            'content_sections.*.layout_type' => 'required|string|in:single,two_column,three_column',
            'content_sections.*.section_name' => 'nullable|string|max:255',
            'content_sections.*.content' => 'nullable|string',
            'content_sections.*.single_column_type' => 'nullable|string|in:text,html,image,gallery,video,embed,content',
            'content_sections.*.single_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_sections.*.single_gallery' => 'nullable|array',
            'content_sections.*.single_gallery.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_sections.*.single_video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:10240',
            'content_sections.*.single_embed' => 'nullable|string',
            'content_sections.*.column_1_content' => 'nullable|string',
            'content_sections.*.column_1_type' => 'nullable|string|in:text,html,image,gallery,video,embed,content',
            'content_sections.*.column_1_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_sections.*.column_1_gallery' => 'nullable|array',
            'content_sections.*.column_1_gallery.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_sections.*.column_1_video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:10240',
            'content_sections.*.column_1_embed' => 'nullable|string',
            'content_sections.*.column_2_content' => 'nullable|string',
            'content_sections.*.column_2_type' => 'nullable|string|in:text,html,image,gallery,video,embed,content',
            'content_sections.*.column_2_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_sections.*.column_2_gallery' => 'nullable|array',
            'content_sections.*.column_2_gallery.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_sections.*.column_2_video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:10240',
            'content_sections.*.column_2_embed' => 'nullable|string',
            'content_sections.*.column_3_content' => 'nullable|string',
            'content_sections.*.column_3_type' => 'nullable|string|in:text,html,image,gallery,video,embed,content',
            'content_sections.*.column_1_image_url' => 'nullable|url',
            'content_sections.*.column_2_image_url' => 'nullable|url',
            'content_sections.*.column_3_image_url' => 'nullable|url',
            'content_sections.*.column_3_gallery' => 'nullable|array',
            'content_sections.*.column_3_gallery.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_sections.*.column_1_video' => 'nullable|url',
            'content_sections.*.column_2_video' => 'nullable|url',
            'content_sections.*.column_3_video' => 'nullable|url',
            'content_sections.*.column_3_embed' => 'nullable|string',
            'content_sections.*.gallery_files' => 'nullable|array',
            'content_sections.*.gallery_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_sections.*.video_url' => 'nullable|url',
            'content_sections.*.video_caption' => 'nullable|string',
            'content_sections.*.gallery_urls' => 'nullable|string',
            'content_sections.*.gallery_caption' => 'nullable|string',
            'content_sections.*.image_url' => 'nullable|url',
            'content_sections.*.image_caption' => 'nullable|string',
            'content_sections.*.image_alt' => 'nullable|string',
            'content_sections.*.embed_code' => 'nullable|string',
            'content_sections.*.embed_caption' => 'nullable|string',
        ];

        try {
            $validatedData = $request->validate($rules);
            Log::info('Validation passed', ['validated_data_keys' => array_keys($validatedData)]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'failed_rules' => $e->validator->failed()
            ]);
            throw $e;
        }

        // Update content sections
        $page->title = $validatedData['title'];
        $page->slug = $validatedData['slug'];
        $page->save();

        // Update content sections
        if (isset($validatedData['content_sections']) && is_array($validatedData['content_sections'])) {
            // Delete existing content sections and create new ones
            $page->contentSections()->delete();
            
            foreach ($validatedData['content_sections'] as $index => $sectionData) {
                Log::info('Creating content section', ['index' => $index, 'section_data' => $sectionData]);
                
                $dataFields = [];
                
                // Handle single column content types and uploads
                if (isset($sectionData['single_column_type'])) {
                    $dataFields['single_column_type'] = $sectionData['single_column_type'];
                }
                
                // Handle single image upload
                if ($request->hasFile("content_sections.{$index}.single_image")) {
                    $dataFields['single_image_path'] = $request->file("content_sections.{$index}.single_image")->store('pages/content', 'public');
                    Log::info('Single image uploaded', ['path' => $dataFields['single_image_path']]);
                }
                
                // Handle single gallery uploads
                if ($request->hasFile("content_sections.{$index}.single_gallery")) {
                    $galleryPaths = [];
                    foreach ($request->file("content_sections.{$index}.single_gallery") as $galleryImage) {
                        $galleryPaths[] = $galleryImage->store('pages/gallery', 'public');
                    }
                    $dataFields['single_gallery_paths'] = $galleryPaths;
                    Log::info('Single gallery uploaded', ['paths' => $galleryPaths]);
                }
                
                // Handle gallery files (alternative field name)
                if ($request->hasFile("content_sections.{$index}.gallery_files")) {
                    $galleryPaths = [];
                    foreach ($request->file("content_sections.{$index}.gallery_files") as $galleryImage) {
                        $galleryPaths[] = $galleryImage->store('pages/gallery', 'public');
                    }
                    $dataFields['gallery_paths'] = $galleryPaths;
                    Log::info('Gallery files uploaded', ['paths' => $galleryPaths]);
                }
                
                // Handle video content
                if (isset($sectionData['video_url'])) {
                    $dataFields['video_url'] = $sectionData['video_url'];
                    Log::info('Video URL saved to dataFields', ['video_url' => $sectionData['video_url']]);
                }
                if (isset($sectionData['video_caption'])) {
                    $dataFields['video_caption'] = $sectionData['video_caption'];
                }
                
                // Handle embed content
                if (isset($sectionData['embed_code'])) {
                    $dataFields['embed_code'] = $sectionData['embed_code'];
                }
                if (isset($sectionData['embed_caption'])) {
                    $dataFields['embed_caption'] = $sectionData['embed_caption'];
                }
                
                // Handle gallery content
                if (isset($sectionData['gallery_urls'])) {
                    $dataFields['gallery_urls'] = $sectionData['gallery_urls'];
                }
                if (isset($sectionData['gallery_caption'])) {
                    $dataFields['gallery_caption'] = $sectionData['gallery_caption'];
                }
                
                // Handle column types and column-specific content
                for ($col = 1; $col <= 3; $col++) {
                    if (isset($sectionData["column_{$col}_type"])) {
                        $dataFields["column_{$col}_type"] = $sectionData["column_{$col}_type"];
                    }
                    
                    // Handle column image uploads
                    if ($request->hasFile("content_sections.{$index}.column_{$col}_image")) {
                        $dataFields["column_{$col}_image_path"] = $request->file("content_sections.{$index}.column_{$col}_image")->store('pages/content', 'public');
                        Log::info("Column {$col} image uploaded", ['path' => $dataFields["column_{$col}_image_path"]]);
                    }
                    
                    // Handle column gallery uploads
                    if ($request->hasFile("content_sections.{$index}.column_{$col}_gallery")) {
                        $galleryPaths = [];
                        foreach ($request->file("content_sections.{$index}.column_{$col}_gallery") as $galleryImage) {
                            $galleryPaths[] = $galleryImage->store('pages/gallery', 'public');
                        }
                        $dataFields["column_{$col}_gallery_paths"] = $galleryPaths;
                        Log::info("Column {$col} gallery uploaded", ['paths' => $galleryPaths]);
                    }
                    
                    // Handle column video URLs
                    if (isset($sectionData["column_{$col}_video"])) {
                        $dataFields["column_{$col}_video"] = $sectionData["column_{$col}_video"];
                        Log::info("Column {$col} video URL saved", ['video_url' => $sectionData["column_{$col}_video"]]);
                    }
                    
                    // Handle column image URLs
                    if (isset($sectionData["column_{$col}_image_url"])) {
                        $dataFields["column_{$col}_image_url"] = $sectionData["column_{$col}_image_url"];
                        Log::info("Column {$col} image URL saved", ['image_url' => $sectionData["column_{$col}_image_url"]]);
                    }
                    
                    // Handle column gallery URLs
                    if (isset($sectionData["column_{$col}_gallery_urls"])) {
                        $dataFields["column_{$col}_gallery_urls"] = $sectionData["column_{$col}_gallery_urls"];
                        Log::info("Column {$col} gallery URLs saved", ['gallery_urls' => $sectionData["column_{$col}_gallery_urls"]]);
                    }
                }
                
                // Create the content section
                $contentSection = $page->contentSections()->create([
                    'section_type' => $sectionData['section_type'],
                    'layout_type' => $sectionData['layout_type'],
                    'section_name' => $sectionData['section_name'] ?? null,
                    'content' => $sectionData['content'] ?? $sectionData['single_content'] ?? $sectionData['column_1_content'] ?? null,
                    'column_1_content' => $sectionData['column_1_content'] ?? null,
                    'column_2_content' => $sectionData['column_2_content'] ?? null,
                    'column_3_content' => $sectionData['column_3_content'] ?? null,
                    'data' => !empty($dataFields) ? json_encode($dataFields) : null,
                    'sort_order' => $index,
                    'is_active' => 1
                ]);
                
                Log::info('Content section created successfully', [
                    'section_id' => $contentSection->id,
                    'layout_type' => $contentSection->layout_type,
                    'section_type' => $contentSection->section_type,
                    'section_data_received' => $sectionData,
                    'data_saved' => $contentSection->data
                ]);
            }
        }

        Log::info('Page update completed', [
            'page_id' => $page->id,
            'content_sections_count' => $page->contentSections()->count()
        ]);

        return redirect()->route('admin.cms.pages.index')->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.cms.pages.index')
                        ->with('success', 'Page deleted successfully.');
    }

    /**
     * Update SEO data for the page.
     */
    public function updateSeo(Request $request, Page $page)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|string|max:500',
            'twitter_card' => 'string|in:summary,summary_large_image',
            'structured_data' => 'nullable|array',
            'noindex' => 'boolean',
            'nofollow' => 'boolean',
        ]);

        $seoData = $page->seoData ?? new SeoData(['seoable_type' => Page::class, 'seoable_id' => $page->id]);
        $seoData->fill($validated);
        $seoData->updateSeoAnalysis();
        $seoData->save();

        return redirect()->back()->with('success', 'SEO data updated successfully.');
    }

    /**
     * Generate a unique slug for the page.
     */
    private function generateUniqueSlug(string $title, int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        $query = Page::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;

            $query = Page::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }
}