<div class="container py-5">
    <div class="d-flex justify-content-center align-items-center mb-4">
        <a class="text-decoration-none text-black me-3" href="#" id="scroll-left">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h2 class="text-center mb-0">Popular categories</h2>
        <a class="text-decoration-none text-black ms-3" href="#" id="scroll-right">
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>

    <div class="overflow-auto scroll-snap-x" id="category-carousel">
        <div class="row g-4">
            @foreach($parentCategories as $category)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <a href="{{ route('product_details', $category->id) }}" class="card border-0 shadow-sm category-card" style="background-color: rgb(51, 50, 49) !important;">
                    @php
                        $homeImage = $category->home_image ?? $category->image;
                    @endphp
                    <img src="{{ $homeImage ? asset('storage/' . $homeImage) : asset('images/default.jpg') }}" 
                         class="card-img category-img" 
                         alt="{{ $category->name }}">
                    <div class="card-img-overlay d-flex flex-column justify-content-center p-3">
                        <h5 class="card-title fw-bold" style="color: rgb(255, 255, 255) !important;">{{ $category->name }}</h5>
                        <p class="mb-0 underline-text smaller-p-text" style="color: rgb(255, 255, 255) !important;">SHOP NOW →</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    const carousel = document.getElementById('category-carousel');
    const scrollLeftBtn = document.getElementById('scroll-left');
    const scrollRightBtn = document.getElementById('scroll-right');

    const scrollAmount = 300; // How much it scrolls per click

    scrollLeftBtn.addEventListener('click', (e) => {
        e.preventDefault();
        carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });

    scrollRightBtn.addEventListener('click', (e) => {
        e.preventDefault();
        carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });
</script>
