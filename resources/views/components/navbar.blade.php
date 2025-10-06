@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $isHomePage = $currentRoute === 'home';

    // Get CMS menu items for header location
    $cmsMenu = \App\Models\Menu::where('location', 'header')->where('is_active', true)->first();
    $cmsMenuItems = $cmsMenu ? $cmsMenu->rootMenuItems()->active()->with(['children' => function($query) {
        $query->where('is_active', true)->orderBy('sort_order');
    }])->get() : collect();

    // Get user initials for profile display
    $name = auth()->user()->name ?? '';
    $initials = collect(explode(' ', $name))->map(fn($part) => strtoupper(substr($part, 0, 1)))->join('');
    $currentParentCategory = null;
    $routeId = request()->route('id');

    if ($routeId) {
        if ($currentRoute === 'show_category') {
            $currentParentCategory = \App\Models\ParentCategory::with(['productTypes.subcategory'])->find($routeId);
        } elseif (in_array($currentRoute, ['show_product_type', 'product_details'], true)) {
            $productTypeForContext = \App\Models\ProductType::with('parentCategory')->find($routeId);
            $currentParentCategory = $productTypeForContext?->parentCategory;
        }
    }

    $productTypes = \App\Models\ProductType::with(['subcategory:id,parent_category_id,name'])->orderBy('name')->get();
    $parentCategories = \App\Models\ParentCategory::with('subcategories')->orderBy('name')->get();

    $baseProductTypes = $currentParentCategory
        ? $productTypes->filter(fn($type) => optional($type->subcategory)->parent_category_id === $currentParentCategory->id)->values()
        : $productTypes;

    $normalize = static fn($value) => strtolower(is_string($value) ? $value : '');
    $matchesKeywords = static function ($value, array $keywords) use ($normalize) {
        $haystack = $normalize($value);
        if ($haystack === '') {
            return false;
        }

        foreach ($keywords as $keyword) {
            if (str_contains($haystack, strtolower($keyword))) {
                return true;
            }
        }

        return false;
    };

    $filterProductTypes = static function (array $keywords, bool $checkSubcategory = false) use ($baseProductTypes, $matchesKeywords) {
        return $baseProductTypes->filter(function ($type) use ($keywords, $matchesKeywords, $checkSubcategory) {
            if ($matchesKeywords($type->name, $keywords)) {
                return true;
            }

            if ($checkSubcategory && $matchesKeywords(optional($type->subcategory)->name, $keywords)) {
                return true;
            }

            return false;
        })->values();
    };

    $audienceGroups = collect([
        "Men's" => $filterProductTypes(['men', 'male', "men's"]),
        "Women's" => $filterProductTypes(['women', 'female', 'lady', 'ladies', "women's"]),
        "Kid's" => $filterProductTypes(['kid', 'youth', 'junior', 'child']),
        'Goalkeepers' => $filterProductTypes(['goalkeeper', 'goalie', 'keeper']),
        'Coaches' => $filterProductTypes(['coach', 'coaching']),
    ])->filter(fn($items) => $items->isNotEmpty())
      ->map(fn($items, $title) => ['title' => $title, 'items' => $items->sortBy('name')->values()])
      ->values();

    $accessoryTypes = $filterProductTypes(['accessory', 'accessories', 'gear'], true)->sortBy('name')->take(6);
    $headNeckTypes = $filterProductTypes(['head', 'neck', 'cap', 'hood', 'beanie', 'scarf', 'gaiter'], true)->sortBy('name')->take(6);
    $merchandiseTypes = $filterProductTypes(['merch', 'souvenir', 'gift', 'promo'], true)->sortBy('name')->take(6);

    $genericSections = $audienceGroups
        ->concat(collect([
            ['title' => 'Accessories', 'items' => $accessoryTypes],
            ['title' => 'Head & Neck', 'items' => $headNeckTypes],
            ['title' => 'Merchandise', 'items' => $merchandiseTypes],
        ])->filter(fn($section) => $section['items']->isNotEmpty()))
        ->filter(fn($section) => $section['items']->isNotEmpty());

    $oftenSearchedTypes = collect();

    if ($currentParentCategory) {
        $currentParentCategory->loadMissing(['subcategories.productTypes.subcategory', 'productTypes.subcategory']);

        $sportSections = $currentParentCategory->subcategories
            ->sortBy('name')
            ->map(function ($subcategory) use ($baseProductTypes) {
                $items = $baseProductTypes
                    ->filter(fn($type) => optional($type->subcategory)->id === $subcategory->id)
                    ->sortBy('name')
                    ->values();

                return [
                    'title' => $subcategory->name,
                    'items' => $items,
                ];
            })->filter(fn($section) => $section['items']->isNotEmpty());

        $orphanTypes = $baseProductTypes
            ->filter(fn($type) => !$type->subcategory)
            ->sortBy('name')
            ->values();

        if ($orphanTypes->isNotEmpty()) {
            $sportSections = $sportSections->push([
                'title' => 'Other products',
                'items' => $orphanTypes,
            ]);
        }

        $productSections = $sportSections;
        $oftenSearchedTypes = $baseProductTypes->sortBy('name')->take(6);

        if ($productSections->isEmpty()) {
            $productSections = $genericSections->values();
        }
    } else {
        $productSections = $genericSections->values();
        $oftenSearchedTypes = $baseProductTypes->sortBy('name')->take(6);
    }

    $productSections = $productSections->values();

    // Calculate column classes after productSections is defined
    $productColumnCount = $productSections->count();
    $productColumnClass = $productColumnCount <= 2 ? 'col-12 col-md-6' : 'col-12 col-md-4';

    $productSectionColumns = $productSections->chunk(max(1, (int) ceil($productSections->count() / 3)));
    $oftenSearchedColumns = $oftenSearchedTypes->chunk(max(1, (int) ceil($oftenSearchedTypes->count() / 2)));
    $parentCategories = \App\Models\ParentCategory::with('subcategories')->orderBy('name')->get();

    $baseProductTypes = $currentParentCategory
        ? $productTypes->filter(fn($type) => optional($type->subcategory)->parent_category_id === $currentParentCategory->id)->values()
        : $productTypes;

    $normalize = static fn($value) => strtolower(is_string($value) ? $value : '');
    $matchesKeywords = static function ($value, array $keywords) use ($normalize) {
        $haystack = $normalize($value);
        if ($haystack === '') {
            return false;
        }

        foreach ($keywords as $keyword) {
            if (str_contains($haystack, strtolower($keyword))) {
                return true;
            }
        }

        return false;
    };

    $filterProductTypes = static function (array $keywords, bool $checkSubcategory = false) use ($baseProductTypes, $matchesKeywords) {
        return $baseProductTypes->filter(function ($type) use ($keywords, $matchesKeywords, $checkSubcategory) {
            if ($matchesKeywords($type->name, $keywords)) {
                return true;
            }

            if ($checkSubcategory && $matchesKeywords(optional($type->subcategory)->name, $keywords)) {
                return true;
            }

            return false;
        })->values();
    };

    $audienceGroups = collect([
        "Men's" => $filterProductTypes(['men', 'male', "men's"]),
        "Women's" => $filterProductTypes(['women', 'female', 'lady', 'ladies', "women's"]),
        "Kid's" => $filterProductTypes(['kid', 'youth', 'junior', 'child']),
        'Goalkeepers' => $filterProductTypes(['goalkeeper', 'goalie', 'keeper']),
        'Coaches' => $filterProductTypes(['coach', 'coaching']),
    ])->filter(fn($items) => $items->isNotEmpty())
      ->map(fn($items, $title) => ['title' => $title, 'items' => $items->sortBy('name')->values()])
      ->values();

    $accessoryTypes = $filterProductTypes(['accessory', 'accessories', 'gear'], true)->sortBy('name')->take(6);
    $headNeckTypes = $filterProductTypes(['head', 'neck', 'cap', 'hood', 'beanie', 'scarf', 'gaiter'], true)->sortBy('name')->take(6);
    $merchandiseTypes = $filterProductTypes(['merch', 'souvenir', 'gift', 'promo'], true)->sortBy('name')->take(6);

    $genericSections = $audienceGroups
        ->concat(collect([
            ['title' => 'Accessories', 'items' => $accessoryTypes],
            ['title' => 'Head & Neck', 'items' => $headNeckTypes],
            ['title' => 'Merchandise', 'items' => $merchandiseTypes],
        ])->filter(fn($section) => $section['items']->isNotEmpty()))
        ->filter(fn($section) => $section['items']->isNotEmpty());

    $oftenSearchedTypes = collect();

    if ($currentParentCategory) {
        $currentParentCategory->loadMissing(['subcategories.productTypes.subcategory', 'productTypes.subcategory']);

        $sportSections = $currentParentCategory->subcategories
            ->sortBy('name')
            ->map(function ($subcategory) use ($baseProductTypes) {
                $items = $baseProductTypes
                    ->filter(fn($type) => optional($type->subcategory)->id === $subcategory->id)
                    ->sortBy('name')
                    ->values();

                return [
                    'title' => $subcategory->name,
                    'items' => $items,
                ];
            })->filter(fn($section) => $section['items']->isNotEmpty());

        $orphanTypes = $baseProductTypes
            ->filter(fn($type) => !$type->subcategory)
            ->sortBy('name')
            ->values();

        if ($orphanTypes->isNotEmpty()) {
            $sportSections = $sportSections->push([
                'title' => 'Other products',
                'items' => $orphanTypes,
            ]);
        }

        $productSections = $sportSections;
        $oftenSearchedTypes = $baseProductTypes->sortBy('name')->take(6);

        if ($productSections->isEmpty()) {
            $productSections = $genericSections->values();
        }
    } else {
        $productSections = $genericSections->values();
        $oftenSearchedTypes = $baseProductTypes->sortBy('name')->take(6);
    }

    $productSections = $productSections->values();

    // Calculate column classes after productSections is defined
    $productColumnCount = $productSections->count();
    $productColumnClass = $productColumnCount <= 2 ? 'col-12 col-md-6' : 'col-12 col-md-4';

    $productSectionColumns = $productSections->chunk(max(1, (int) ceil($productSections->count() / 3)));
    $oftenSearchedColumns = $oftenSearchedTypes->chunk(max(1, (int) ceil($oftenSearchedTypes->count() / 2)));
    $routeId = request()->route('id');

    if ($routeId) {
        if ($currentRoute === 'show_category') {
            $currentParentCategory = \App\Models\ParentCategory::with(['productTypes.subcategory'])->find($routeId);
        } elseif (in_array($currentRoute, ['show_product_type', 'product_details'], true)) {
            $productTypeForContext = \App\Models\ProductType::with('parentCategory')->find($routeId);
            $currentParentCategory = $productTypeForContext?->parentCategory;
        }
    }

    $productTypes = \App\Models\ProductType::with(['subcategory:id,parent_category_id,name'])->orderBy('name')->get();
    $parentCategories = \App\Models\ParentCategory::with('subcategories')->orderBy('name')->get();

    $baseProductTypes = $currentParentCategory
        ? $productTypes->filter(fn($type) => optional($type->subcategory)->parent_category_id === $currentParentCategory->id)->values()
        : $productTypes;

    $normalize = static fn($value) => strtolower(is_string($value) ? $value : '');
    $matchesKeywords = static function ($value, array $keywords) use ($normalize) {
        $haystack = $normalize($value);
        if ($haystack === '') {
            return false;
        }

        foreach ($keywords as $keyword) {
            if (str_contains($haystack, strtolower($keyword))) {
                return true;
            }
        }

        return false;
    };

    $filterProductTypes = static function (array $keywords, bool $checkSubcategory = false) use ($baseProductTypes, $matchesKeywords) {
        return $baseProductTypes->filter(function ($type) use ($keywords, $matchesKeywords, $checkSubcategory) {
            if ($matchesKeywords($type->name, $keywords)) {
                return true;
            }

            if ($checkSubcategory && $matchesKeywords(optional($type->subcategory)->name, $keywords)) {
                return true;
            }

            return false;
        })->values();
    };

    $audienceGroups = collect([
        "Men's" => $filterProductTypes(['men', 'male', "men's"]),
        "Women's" => $filterProductTypes(['women', 'female', 'lady', 'ladies', "women's"]),
        "Kid's" => $filterProductTypes(['kid', 'youth', 'junior', 'child']),
        'Goalkeepers' => $filterProductTypes(['goalkeeper', 'goalie', 'keeper']),
        'Coaches' => $filterProductTypes(['coach', 'coaching']),
    ])->filter(fn($items) => $items->isNotEmpty())
      ->map(fn($items, $title) => ['title' => $title, 'items' => $items->sortBy('name')->values()])
      ->values();

    $accessoryTypes = $filterProductTypes(['accessory', 'accessories', 'gear'], true)->sortBy('name')->take(6);
    $headNeckTypes = $filterProductTypes(['head', 'neck', 'cap', 'hood', 'beanie', 'scarf', 'gaiter'], true)->sortBy('name')->take(6);
    $merchandiseTypes = $filterProductTypes(['merch', 'souvenir', 'gift', 'promo'], true)->sortBy('name')->take(6);

    $genericSections = $audienceGroups
        ->concat(collect([
            ['title' => 'Accessories', 'items' => $accessoryTypes],
            ['title' => 'Head & Neck', 'items' => $headNeckTypes],
            ['title' => 'Merchandise', 'items' => $merchandiseTypes],
        ])->filter(fn($section) => $section['items']->isNotEmpty()))
        ->filter(fn($section) => $section['items']->isNotEmpty());

    $oftenSearchedTypes = collect();

    if ($currentParentCategory) {
        $currentParentCategory->loadMissing(['subcategories.productTypes.subcategory', 'productTypes.subcategory']);

        $sportSections = $currentParentCategory->subcategories
            ->sortBy('name')
            ->map(function ($subcategory) use ($baseProductTypes) {
                $items = $baseProductTypes
                    ->filter(fn($type) => optional($type->subcategory)->id === $subcategory->id)
                    ->sortBy('name')
                    ->values();

                return [
                    'title' => $subcategory->name,
                    'items' => $items,
                ];
            })
            ->values();

        $orphanTypes = $baseProductTypes
            ->filter(fn($type) => !$type->subcategory)
            ->sortBy('name')
            ->values();

        if ($orphanTypes->isNotEmpty()) {
            $sportSections = $sportSections->push([
                'title' => 'Other products',
                'items' => $orphanTypes,
            ]);
        }

        $productSections = $sportSections;
        $oftenSearchedTypes = $baseProductTypes->sortBy('name')->take(6);

        if ($productSections->isEmpty()) {
            $productSections = $genericSections->values();
        }
    } else {
        $productSections = $genericSections->values();
        $oftenSearchedTypes = $baseProductTypes->sortBy('name')->take(6);
    }

    $productSections = $productSections->values();

    $productSectionColumns = $productSections->chunk(max(1, (int) ceil($productSections->count() / 3)));
    $oftenSearchedColumns = $oftenSearchedTypes->chunk(max(1, (int) ceil($oftenSearchedTypes->count() / 2)));
    $sportsColumns = $parentCategories->chunk(max(1, (int) ceil($parentCategories->count() / 3)));
    $productColumnCount = $productSectionColumns->count();
    $productColumnClass = $productColumnCount <= 2 ? 'col-12 col-md-6' : 'col-12 col-md-4';

    // Get user initials for profile display
    $name = auth()->user()->name ?? '';
    $initials = collect(explode(' ', $name))->map(fn($part) => strtoupper(substr($part, 0, 1)))->join('');
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 sticky-top" style="top: 0; z-index: 1020;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-danger fst-italic d-flex align-items-center" href="{{ route('home') }}">
            <svg width="36" height="28" viewBox="0 0 36 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                <path d="M18 0L35.3205 12.5V27.5H24.2487V17.5H11.7513V27.5H0.679492V12.5L18 0Z" fill="#dc3545"/>
            </svg>
            RICONA
        </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 page-navi-content">
                    <!-- Sports Mega Menu -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="sportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" fill="currentColor"/>
                                <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" fill="currentColor" opacity="0.2"/>
                            </svg>
                            Sports
                        </a>
                        <div class="dropdown-menu navi-submenu mega-menu" aria-labelledby="sportsDropdown">
                            <div class="mega-menu-inner">
                                <div class="row mega-row">
                                    @foreach($sportsColumns as $column)
                                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                                            <ul class="list-unstyled mb-0">
                                                @foreach($column as $parentCategory)
                                                    <li>
                                                        <a class="menu-link {{ $currentParentCategory && $currentParentCategory->id === $parentCategory->id ? 'active' : '' }}" href="{{ route('show_category', $parentCategory->id) }}">
                                                            {{ $parentCategory->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Products Mega Menu -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z" fill="currentColor"/>
                            </svg>
                            Products
                        </a>
                        <div class="dropdown-menu navi-submenu mega-menu" aria-labelledby="productsDropdown">
                            <div class="mega-menu-inner">
                                <div class="row mega-row">
                                    @foreach($productSectionColumns as $column)
                                        <div class="{{ $productColumnClass }} mb-3 mb-md-0">
                                            @foreach($column as $section)
                                                <div class="menu-section mb-3">
                                                    <p class="menu-heading text-uppercase small fw-semibold text-secondary mb-1">{{ $section['title'] }}</p>
                                                    <ul class="list-unstyled mb-0">
                                                        @forelse($section['items'] as $productType)
                                                            <li>
                                                                <a class="menu-link" href="{{ route('product_details', $productType->id) }}">{{ $productType->name }}</a>
                                                            </li>
                                                        @empty
                                                            <li class="menu-empty">More styles coming soon</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mega-divider my-4"></div>
                                <div class="row mega-row align-items-stretch">
                                    <div class="col-12 col-lg-8">
                                        <p class="menu-heading text-uppercase small fw-semibold text-secondary mb-1">Often searched</p>
                                        @if($oftenSearchedTypes->isNotEmpty())
                                            <div class="row row-cols-1 row-cols-sm-2 g-2">
                                                @foreach($oftenSearchedColumns as $column)
                                                    <div class="col">
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach($column as $productType)
                                                                <li><a class="menu-link" href="{{ route('product_details', $productType->id) }}">{{ $productType->name }}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="menu-empty mb-0">Popular picks will appear here soon.</p>
                                        @endif
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="menu-highlight p-3 rounded-3 h-100 d-flex flex-column justify-content-between gap-2">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-2">Design assistance</h6>
                                                <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="small mb-0">Need a hand bringing your custom kit to life? Share your ideas and our designers will help finish the look.</p>
                                            </div>
                                            <a class="btn btn-outline-dark btn-sm align-self-start mt-3" href="#">Start a project</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- CMS Menu Items -->
                    @foreach($cmsMenuItems as $menuItem)
                        <li class="nav-item {{ $menuItem->activeChildren()->count() > 0 ? 'dropdown' : '' }} me-2">
                            @if($menuItem->activeChildren()->count() > 0)
                                <!-- Dropdown Menu Item -->
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="menuItem{{ $menuItem->id }}Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if($menuItem->icon_class)
                                        <i class="{{ $menuItem->icon_class }} me-2"></i>
                                    @else
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                            <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" fill="currentColor"/>
                                        </svg>
                                    @endif
                                    {{ $menuItem->title }}
                                </a>
                                @php
                                    $isProductOrSport = in_array(strtolower($menuItem->title), ['products', 'sport', 'sports']);
                                @endphp
                                <div class="dropdown-menu navi-submenu {{ !$isProductOrSport ? 'two-col' : 'mega-menu mega-menu-narrow' }}" aria-labelledby="menuItem{{ $menuItem->id }}Dropdown">
                                    @if(!$isProductOrSport)
                                        @php
                                            $children = $menuItem->activeChildren()->get();
                                            $halfCount = ceil($children->count() / 2);
                                            $firstColumn = $children->take($halfCount);
                                            $secondColumn = $children->skip($halfCount);
                                        @endphp
                                        <div class="two-col-container">
                                            <div class="two-col-column">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($firstColumn as $child)
                                                        <li class="menu-item">
                                                            <a class="menu-link" href="{{ $child->full_url }}">
                                                                <span class="menu-link-title">{{ $child->title }}</span>
                                                                @if($child->description)
                                                                    <span class="menu-link-detail">{{ $child->description }}</span>
                                                                @endif
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="two-col-column">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($secondColumn as $child)
                                                        <li class="menu-item">
                                                            <a class="menu-link" href="{{ $child->full_url }}">
                                                                <span class="menu-link-title">{{ $child->title }}</span>
                                                                @if($child->description)
                                                                    <span class="menu-link-detail">{{ $child->description }}</span>
                                                                @endif
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mega-menu-inner">
                                            <div class="row mega-row">
                                                <div class="col-12">
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($menuItem->activeChildren()->get() as $child)
                                                            <li class="menu-item">
                                                                <a class="menu-link" href="{{ $child->full_url }}">
                                                                    <span class="menu-link-title">{{ $child->title }}</span>
                                                                    @if($child->description)
                                                                        <span class="menu-link-detail">{{ $child->description }}</span>
                                                                    @endif
                                                                </a>
                                                                <hr class="menu-separator">
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Single Menu Item -->
                                <a class="nav-link d-flex align-items-center" href="{{ $menuItem->full_url }}" {{ $menuItem->target ? 'target="' . $menuItem->target . '"' : '' }}>
                                    @if($menuItem->icon_class)
                                        <i class="{{ $menuItem->icon_class }} me-2"></i>
                                    @else
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                            <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" fill="currentColor"/>
                                        </svg>
                                    @endif
                                    {{ $menuItem->title }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>

            <div id="navIcons" class="d-flex gap-3 align-items-center ms-md-auto mt-2 mt-md-0">
                @auth
                    <div class="dropdown">
                        <a class="text-dark d-flex align-items-center" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle d-flex justify-content-center align-items-center" style="width:35px; height:35px; background-color:#d4d4d4; color:#333; font-weight:600; font-size:14px;">
                                {{ $initials }}
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-dark fs-5"><i class="bi bi-person"></i></a>
                @endauth

                <button class="btn p-0 border-0 text-dark fs-5" id="openFullscreenSearch">
                    <i class="bi bi-search"></i>
                </button>
                <button class="btn p-0 border-0 text-dark fs-5" id="openCartOverlay">
                    <i class="bi bi-cart"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<style>
    .navbar .mega-menu {
        width: clamp(420px, 72vw, 920px);
        padding: 1.1rem 1.2rem;
        border: none;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        border-radius: 1rem;
    }

    .navbar .mega-menu-narrow {
        width: clamp(420px, 72vw, 550px);
    }

    .navbar .navbar-nav .nav-link {
        padding: 0.45rem 0.6rem;
    }

    .navbar .mega-menu-inner {
        max-height: 60vh;
    }

    .navbar .mega-row {
        --bs-gutter-x: 0.9rem;
        --bs-gutter-y: 0.65rem;
    }

    .navbar .menu-heading {
        letter-spacing: .08em;
        margin-bottom: 0.25rem;
    }

    .navbar .menu-section {
        padding-bottom: 0.35rem;
    }

    .navbar .menu-link {
        display: block;
        color: #212529;
        font-weight: 500;
        text-decoration: none;
        line-height: 1.3;
        transition: color 0.2s ease;
    }

    .navbar .menu-link:hover,
    .navbar .menu-link.active {
        color: #dc3545;
    }

    .navbar .mega-menu ul li {
        margin-bottom: 0.22rem;
    }

    .navbar .mega-menu ul li:last-child {
        margin-bottom: 0;
    }

    .navbar .menu-item {
        margin-bottom: 0.25rem;
    }

    .navbar .menu-item:last-child {
        margin-bottom: 0;
    }

    .navbar .menu-link-title {
        display: block;
        font-weight: 600;
    }

    .navbar .menu-link-detail {
        display: block;
        font-size: 0.78rem;
        color: #6c757d;
        margin-top: 0.15rem;
        font-weight: 400;
    }

    .navbar .menu-separator {
        border: 0;
        border-top: 1px solid rgba(15, 23, 42, 0.08);
        margin: 0.45rem 0 0.3rem;
    }

    .navbar .menu-item:last-child .menu-separator {
        display: none;
    }

    .navbar .menu-empty {
        font-size: 0.78rem;
        color: #94a3b8;
        padding: 0.15rem 0;
    }

    .navbar .mega-divider {
        height: 1px;
        background: linear-gradient(90deg, rgba(226, 232, 240, 0), rgba(148, 163, 184, 0.5), rgba(226, 232, 240, 0));
    }

    .navbar .menu-highlight {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.9), rgba(226, 232, 240, 0.6));
        border: 1px solid rgba(226, 232, 240, 0.8);
        padding: 1.25rem;
    }

    .navbar .navi-submenu.two-col {
        width: clamp(480px, 65vw, 750px);
        min-width: 480px;
        padding: 1.5rem 1.75rem;
        border: none;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        border-radius: 1rem;
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        background: white;
    }

    .navbar .two-col-container {
        display: flex;
        gap: 2rem;
        justify-content: space-between;
        align-items: flex-start;
    }

    .navbar .two-col-column {
        flex: 1;
        min-width: 200px;
    }

    .navbar .two-col ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .navbar .two-col ul li {
        margin-bottom: 0.35rem;
    }

    .navbar .two-col ul li:last-child {
        margin-bottom: 0;
    }

    .navbar .two-col .menu-link {
        display: block;
        color: #212529;
        font-weight: 500;
        text-decoration: none;
        line-height: 1.3;
        transition: color 0.2s ease;
        padding: 0.25rem 0;
    }

    .navbar .two-col .menu-link:hover {
        color: #dc3545;
    }

    .navbar .two-col .menu-link-title {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .navbar .two-col .menu-link-detail {
        display: block;
        font-size: 0.78rem;
        color: #6c757d;
        margin-top: 0.15rem;
        font-weight: 400;
    }

    @media (max-width: 992px) {
        .navbar .mega-menu {
            width: calc(100vw - 2rem);
            padding: 1.25rem 1rem;
        }

        .navbar .mega-menu-inner {
            max-height: 55vh;
        }

        .navbar .navi-submenu.two-col {
            width: calc(100vw - 2rem);
            min-width: auto;
            padding: 1.25rem 1rem;
        }

        .navbar .two-col-container {
            flex-direction: column;
            gap: 1rem;
        }

        .navbar .two-col-column {
            min-width: auto;
        }
    }
</style>

<script>
// Dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.navbar .dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.navi-submenu');

        // Show dropdown on hover
        dropdown.addEventListener('mouseenter', function() {
            menu.style.display = 'block';
        });

        // Hide dropdown when mouse leaves
        dropdown.addEventListener('mouseleave', function() {
            menu.style.display = 'none';
        });

        // Toggle dropdown on click (for mobile)
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const isVisible = menu.style.display === 'block';
            // Hide all dropdowns first
            document.querySelectorAll('.navi-submenu').forEach(m => m.style.display = 'none');
            // Show this one if it was hidden
            if (!isVisible) {
                menu.style.display = 'block';
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.navbar .dropdown')) {
            document.querySelectorAll('.navi-submenu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });
});
</script>
        <div id="fullscreenSearchOverlay" class="search-overlay">
            <button class="close-overlay-btn" id="closeFullscreenSearch">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="search-overlay-content">
                <form class="d-flex justify-content-center">
                    <div class="input-group search-input-group">
                        <input type="text" class="form-control search-overlay-input" placeholder="Search our store" aria-label="Search">
                        <button class="btn btn-outline-secondary search-overlay-btn" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="cartOverlay" class="cart-overlay">
            <button class="close-overlay-btn" id="closeCartOverlay">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="cart-overlay-content">
                <div class="text-center">
                    <p class="fs-1 text-secondary mb-3"><i class="bi bi-bag"></i></p>
                    <h3 class="fw-bold">Your cart is empty</h3>
                    <button class="btn btn-dark mt-4">START SHOPPING</button>
                </div>
            </div>
        </div>

