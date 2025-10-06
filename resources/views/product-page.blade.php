@extends('layouts.app')

@section('title', $product->name)
@section('body-class', 'product-page')
@section('styles')
<style>
    .color-grid {
     
            display: grid
;
    grid-template-columns: repeat(auto-fill, minmax(52px, 1fr));
   
     row-gap: 70px;  
      column-gap: 16px;
    }

   .color-swatch {
    width: 60px;
    height: 60px;
    border-radius: 0px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative; /* zaroori hai */
     cursor: pointer;
    
}

/* Label ko box ke neeche shift karo */
.color-swatch .color-label {
    position: absolute;
    bottom: -22px; /* box ke neeche push */
    left: 50%;
    transform: translateX(-50%);
    font-size: 14px;
    font-weight: 500;
    color: #333;
    white-space: nowrap;
}

    .color-swatch:last-child {
        background-color: transparent;
        border: 1px dashed #ccc;
    }
/* Modal (background overlay) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    background-color: rgba(0, 0, 0, 0.7); /* Black with opacity */
    backdrop-filter: blur(5px); /* Optional: Adds a blur effect */
    -webkit-backdrop-filter: blur(5px);
    justify-content: center;
    align-items: center;
    overflow: auto;
}

/* Modal content box */
.modal-content {
    background: #2b2b2b; /* Dark background */
    color: white;
    width: 90%;
    max-width: 600px; /* Max width to match image */
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    display: flex;
    position: relative;
    overflow: hidden;
}

/* Modal sections for header and body */
.modal-header {
    display: flex;
    width: 100%;
}

.modal-color-swatch {
    width: 50%;
    min-height: 400px;
    background-color: var(--modal-bg-color); /* Will be set by JS */
}

.modal-info {
    width: 50%;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.modal-title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.modal-subtitle {
    font-size: 1.2rem;
    color: #a0a0a0;
    margin-bottom: 20px;
}

.modal-association {
    font-size: 1rem;
    color: #ccc;
    margin-bottom: 10px;
}

.modal-description {
    font-size: 1rem;
    line-height: 1.4;
    margin-bottom: 20px;
}

.oclo-info {
    border-top: 1px solid #555;
    padding-top: 10px;
    font-size: 0.9rem;
}

/* Close button */
.close-btn {
    color: #fff;
    font-size: 2rem;
    font-weight: bold;
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
    z-index: 10;
}
    .product-page {
        background-image: url('{{ asset('images/background.webp') }}');
        background-size: auto;
        background-color: white;
        background-position: center;
        background-repeat: no-repeat;
        height: 800px;
    }

    /* Remove number input arrows (spinners) */
    input.no-spinner::-webkit-outer-spin-button,
    input.no-spinner::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input.no-spinner {
        -moz-appearance: textfield;
    }

    .header-slider {
        font-size: 25px;
        font-weight: 600;
        margin-bottom: 0;
        padding-left: 160px;
        text-align: left;
    }
    .slider-content {
        color: #818181;
        font-weight: 300;
        font-size: 15px;
        padding-left: 160px;
        margin-top: 4px;
        margin-bottom: 15px;
        text-align: left;
    }
    .intro {
        font-weight: 500;
        color: #818181;
        font-size: 15px;
        line-height: 22px;
        margin-bottom: 0;
        text-align: justify;
    }
    #rotate-btn:focus,
    #rotate-btn:focus-visible,
    #rotate-btn:active {
        outline: none !important;
        box-shadow: none !important;
        text-shadow: none;
        color: transparent;
    }

    /* --- CSS for the new "Design" section --- */

  /* Default state */
.nav-tabs .nav-link {
  border: none;
  color: #333;
  position: relative;
  padding: 4px 12px;
}

/* Active tab */
.nav-tabs .nav-link.active {
  color: #0d6efd !important; /* Text blue */
  font-weight: 500;
}

/* Active underline */
.nav-tabs .nav-link.active::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;           /* underline ko bilkul neeche la diya */
  height: 2px;
  background-color: #0d6efd;
  width: 100%;
  border-radius: 2px;
}


    .design-card {
        position: relative;
        cursor: pointer;
        overflow: hidden;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .design-card:hover {
        transform: translateY(-5px);
    }
    .design-card img {
        width: 100%;
        display: block;
    }
    .design-card .overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        color: #fff;
        text-align: center;
        padding: 0.5rem 0;
        font-weight: 600;
        font-size: 0.9rem;
        opacity: 1;
        transition: opacity 0.3s ease;
    }
    .custom-template-label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #28a745; /* green background */
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        font-weight: bold;
        white-space: nowrap;
    }
    .design-card:last-child .overlay {
        display: none; /* Hide the default overlay on the last card */
    }

    .line {
        width: 100%;
        height: 1px;
        background-color: #dee2e6;
        margin-top: 2rem;
    }
    .tabs-wrapper {
    margin: 0;
    padding: 0 30px;
    border-left: 1px solid #cdcdcd;
    border-right: 1px solid #cdcdcd;
    border-top: 1px solid #cdcdcd;
    background: #fff;
    width: 100%;
    max-height: 30px;
    text-align: justify;
     width: auto;          /* 👈 ab width sirf content jitni hogi */
    text-align: center;
    
}
.custom-template-label {
    padding: 1px;
    position: absolute;
    top: 90px;
    left: 50%;
    transform: translateX(-50%);
    width: 110px;
    height: 23px;
    background: #00A76F;
    color: #fff;
    text-align: center;
    line-height: 22px;
    font-size: 17px;
    font-weight: 500;
    border-radius: 0;  /* Removed rounded corners */
    box-shadow: 6px 6px 8px #333;
    z-index: 10;
}
.custom-templates {
    padding: 20px;
}

.go-to-top {
    display: block;
    text-align: right;
    margin-top: 20px;
    text-decoration: none;
    color: #007bff;
}

.go-to-top i {
    font-size: 1.5rem;
}

.go-to-top span {
    display: block;
    font-size: 0.8rem;
}

.star-rating .fa-star, .star-rating .fa-star-half-alt {
    color: #ffc107;
}

</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 p-0">
            <div class="container my-5">
                <div class="row">
                    <div class="col-md-7">
         <h1 class="header-slider">
    @if($product->productType && $product->productType->subcategory && $product->productType->subcategory->parentCategory)
        {{ ucwords(strtolower($product->productType->subcategory->parentCategory->name)) }}
    @endif 
    {{ ucwords(strtolower(str_ireplace('jerseys', '', $product->name))) }}
</h1>



            @if($product->designs->count())
                @php $design = $product->designs->first(); @endphp
                <h4 class="slider-content" id="design-name">Design {{ $design->name }}</h4>
            @else
                <h4 class="slider-content" id="design-name">No Design</h4>
            @endif

            <div class="d-flex flex-column align-items-center">
                @if($product->designs->count())
                    @php $design = $product->designs->first(); @endphp
                    <div id="design-images" class="mb-3" style="  margin-left: 140px;  width: 355px;
    height: 355px; position: relative; overflow:hidden;">
                        <img id="design-front" src="{{ asset('storage/'.$design->front_image) }}" style="    width: 355px;
    height: 355px; display:block; transition: opacity 0.5s;">
                        <img id="design-back" src="{{ asset('storage/'.$design->back_image) }}" style="    width: 355px;
    height: 355px; display:none; transition: opacity 0.5s;">
                        <img id="design-left" src="{{ asset('storage/'.$design->left_image) }}" style="    width: 355px;
    height: 355px; display:none; transition: opacity 0.5s;">
                        <img id="design-right" src="{{ asset('storage/'.$design->right_image) }}" style="    width: 355px;
    height: 355px; display:none; transition: opacity 0.5s;">
                    </div>
                @else
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-width:400px; height:400px;">
                @endif
            </div>

            <div class="d-flex">
                <div style="display: flex; align-items: center; margin-left: 230px;">
                    <button class="btn" id="prev-design">
                        <img src="{{ asset('images/left.svg') }}" alt="Left" width="24" height="24">
                    </button>
                    <button class="btn">
                        <img src="{{ asset('images/product-icon.svg') }}" alt="Rotate" width="50" height="50" style="margin-left: -27px;">
                    </button>
                    <button class="btn" id="next-design">
                        <img src="{{ asset('images/right.svg') }}" alt="Right" width="24" height="24" style="margin-left: -27px;">
                    </button>
                </div>
                <button class="btn btn-sm" id="rotate-btn">
                    <img src="{{ asset('images/rotate.svg') }}" alt="Rotate" width="29" height="29" style="margin-left: 65px;">
                </button>
            </div>
        </div>

        <div class="col-md-5" style="margin-top: 30px;">
            <div class="intro col-md-6">
                {{ $product->description }}
            </div>

            {{-- Product Details --}}
            @php
                $details = is_array($product->details) ? $product->details : (!empty($product->details) ? explode(',', $product->details) : []);
                $details = array_map('trim', $details);
            @endphp
            @if(!empty($product->details))
                <h4 class="mt-4" style="font-size: 15px;">Product Features</h4>
                <ul style="font-size: 13px; line-height: 22px; margin-bottom: 0px; margin-left: -17px;">
                    @foreach(preg_split('/\r\n|\r|\n/', $product->details) as $line)
                        @if(trim($line))
                            <li>{{ $line }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif

            {{-- Quantity Selector --}}
            <div class="d-flex align-items-center mt-2">
                <span class="me-2">Amount</span>
                <input type="number" id="quantity" class="form-control no-spinner" value="{{ $product->quantity }}" style="width: 50px; height: 30px; border-radius: 0;" min="1" max="999">
                <span class="ms-2">pieces</span>
            </div>

            <div class="mt-2">
                <span class="h3" id="price" style="color: #0071B9; font-size: 25px; font-weight: 700;">
                    ${{ number_format($product->price, 2) }}
                </span>
                <small>price per unit</small>
            </div>

            <div class="col-md-5">
                <p style="font-size: 16px; line-height: 22px; font-style: italic; color: #818181; margin-bottom: 0;">
                    <small>All logos and texts are included in the price</small>
                </p>
            </div>
            <p style="font-size: 16px; line-height: 22px; color: #0071B9;">
                <small>Delivery time: Normal (4 weeks)</small>
            </p>

            <button class="btn btn-primary btn-lg mt-4" style="display: block; height: 36px; width: 200px; padding: 0px; text-align: center; font-size: 16px; font-weight: 500; border-radius: 0; background-color: #0071B9; border-color: #0071B9;">
                Open in 3D Kit Designer
            </button>
        </div>
    </div>
</div>
        </div>
    </div>
</div>



<script>
  // Let Bootstrap handle the tab switching with data-bs-toggle="tab"
  // Only add custom styling behavior if needed
</script>


</section>
<div style="background: white">
<section class="py-5 container" style="background: white">
  <div class="container" style="margin-top: -78px;">
    <div class="text-center">
      <div class="tabs-wrapper d-inline-block ">
       <ul class="nav nav-tabs mb-0 gap-4" style="border-bottom: none !important;" id="customTabs" role="tablist">
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#details">Details</a></li>
  <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#designs">Designs</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#colors">Colors</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#patterns">Patterns</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#fonts">Fonts</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#logos">Logos</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#prices">Prices and Lead Times</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#sizes">Sizes</a></li>
</ul>

      </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content mt-5 container" style="background: white">

      <!-- Details (empty for now) -->
    <div class="tab-pane fade" id="details"> 
  <!-- Heading & Description -->
  <h2 class="fw-bold mb-3 text-center">F3 Basic Soccer Jerseys</h2>
  <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="text-center w-75 mx-auto">
    The F3 Basic is an entry model in our soccer line up. 
    The classic straight cut and clean workmanship give you and your team the best price/performance ratio available. 
    It’s perfect for any level of play and especially popular amongst teams looking to limit their budget.
  </p>

  <!-- Main Jersey Image -->
  <div class="text-center my-4">
    <img src="your-jersey-image.png" alt="Soccer Jersey" class="img-fluid" style="max-width:350px;">
  </div>

  <!-- Image counter (1/4) -->
  <p class="text-center fw-bold">1 / 4</p>

  <!-- Features Section -->
  <div class="row text-center mt-4">
    <!-- Feature 1 -->
    <div class="col-md-3 col-6 mb-4">
      <div class="position-relative d-inline-block">
        <span class="badge rounded-circle position-absolute top-0 start-0 translate-middle" style="background-color: rgb(51, 50, 49) !important; color: rgb(255, 255, 255) !important;">1</span>
        <img src="feature1.png" alt="V-Neck" class="rounded-circle img-fluid" style="width:120px; height:120px; object-fit:cover;">
      </div>
      <h6 class="fw-bold mt-2">V-Neck</h6>
      <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="small">The classic V-collar you are used to</p>
    </div>

    <!-- Feature 2 -->
    <div class="col-md-3 col-6 mb-4">
      <div class="position-relative d-inline-block">
        <span class="badge rounded-circle position-absolute top-0 start-0 translate-middle" style="background-color: rgb(51, 50, 49) !important; color: rgb(255, 255, 255) !important;">2</span>
        <img src="feature2.png" alt="Regular Fit" class="rounded-circle img-fluid" style="width:120px; height:120px; object-fit:cover;">
      </div>
      <h6 class="fw-bold mt-2">Regular Fit</h6>
      <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="small">Loose fit with anatomically shaped seams</p>
    </div>

    <!-- Feature 3 -->
    <div class="col-md-3 col-6 mb-4">
      <div class="position-relative d-inline-block">
        <span class="badge rounded-circle position-absolute top-0 start-0 translate-middle" style="background-color: rgb(51, 50, 49) !important; color: rgb(255, 255, 255) !important;">3</span>
        <img src="feature3.png" alt="TS-Tex ultra.dry" class="rounded-circle img-fluid" style="width:120px; height:120px; object-fit:cover;">
      </div>
      <h6 class="fw-bold mt-2">TS-Tex ultra.dry</h6>
      <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="small">A soft feel with moisture wicking and fast drying technology</p>
    </div>

    <!-- Feature 4 -->
    <div class="col-md-3 col-6 mb-4">
      <div class="position-relative d-inline-block">
        <span class="badge rounded-circle position-absolute top-0 start-0 translate-middle" style="background-color: rgb(51, 50, 49) !important; color: rgb(255, 255, 255) !important;">4</span>
        <img src="feature4.png" alt="Ergonomic Sleeves" class="rounded-circle img-fluid" style="width:120px; height:120px; object-fit:cover;">
      </div>
      <h6 class="fw-bold mt-2">Ergonomic Sleeves</h6>
      <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="small">Loose fitting for maximum mobility</p>
    </div>
  </div>
</div>


      <!-- ✅ Designs -->
      <!-- ✅ Designs -->
<div class="tab-pane fade show active container col-8" id="designs" >
  <div class="text-left">
    <h2 class="fw-bold mb-3" style="margin-bottom: 25px;
    font-size: 20px;
    line-height: 1.45em;">Design Overview</h2>
    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 16px;
    line-height: 22px;
    margin-bottom: 25px;" class="mb-1">
      Whether classic, futuristic or simply crazy, we offer {{ $product->designs->count() }} design templates so you'll be sure
      to find just what you are looking for. Make any design your own by changing the colors and
      adding texts and logos - all included in the price!
    </p>

   <!-- Unique Designs Section -->
<div style="padding-top: 70px; padding-bottom: 70px; border-bottom: 2px solid #cdcdcd; margin-bottom: 40px;"> 
    <h3 class="fw-semibold mb-4" style="
        text-transform: none;
        font-weight: 600;
        font-size: 16px;
        line-height: 22px;
        margin-bottom: 20px;  /* space between heading and images */
    ">
        Unique Designs
    </h3>

    <div class="row g-4">
        @php
            $uniqueDesigns = $product->designs->where('is_unique', 1);
        @endphp

        {{-- Loop through unique designs --}}
       @forelse($uniqueDesigns as $design)
    <div class="col-auto" style="flex: 0 0 20%; max-width: 20%;">
        <div>
            <img src="{{ asset('storage/' . $design->preview_img) }}" 
                 class="img-fluid design-preview" 
                 alt="{{ $design->name }}"
                 data-front="{{ asset('storage/' . $design->front_image) }}"
                 data-back="{{ asset('storage/' . $design->back_image) }}"
                 data-left="{{ asset('storage/' . $design->left_image) }}"
                 data-right="{{ asset('storage/' . $design->right_image) }}"
                 data-name="{{ $design->name }}">

            <div class="{{ $design->is_custom ? 'custom-template-label' : 'overlay' }}" 
                 style="display: flex; justify-content: center; color: #818181; align-items: center; font-size: 17px; height: 100%;">
                {{ $design->name }}
            </div>
        </div>
    </div>
@empty
    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">No unique designs available for this product.</p>
@endforelse


        {{-- Always show custom.avif image after unique designs --}}
        <div class="col-auto" style="flex: 0 0 20%; max-width: 20%;">
            <div>
                <div style="position: relative;">
    <img src="{{ asset('images/custom.avif') }}" class="img-fluid" alt="Custom Design">
    <div class="custom-template-label">Custom <br>Template</div>
</div>

            </div>
        </div>
    </div>
</div>


    <h3 class="fw-semibold mb-4" style="text-transform: none;
    font-weight: 600;
    margin-bottom: 40px; line-height: 22px;
    font-size: 16px;">Designs</h3>
   <div class="row g-4 ">
 @foreach($product->designs as $i => $design)
<div class="col-auto" style="flex: 0 0 20%; max-width: 20%;">
    <div>
        <img src="{{ asset('storage/' . $design->preview_img) }}"
             class="img-fluid design-preview"
             alt="{{ $design->name }}"
             data-index="{{ $i }}"
             data-front="{{ asset('storage/' . $design->front_image) }}"
             data-back="{{ asset('storage/' . $design->back_image) }}"
             data-left="{{ asset('storage/' . $design->left_image) }}"
             data-right="{{ asset('storage/' . $design->right_image) }}"
             data-name="{{ $design->name }}">
        <div class="{{ $design->is_custom ? 'custom-template-label' : 'overlay' }}" 
             style="display: flex; justify-content: center; color: #818181; align-items: center; font-size: 17px; height: 100%;">
             {{ $design->name }}
        </div>
    </div>
</div>
@endforeach

</div>
<hr style="margin-top: 100px;">
  </div>
   <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <img src="{{ asset('images/sample.webp') }}" class="img-fluid" style="      margin-top: 40px;  max-width: 500px;
    height: auto;" alt="Custom T-shirts">
        </div>

        <div class="col-md-4" >
            <div class="custom-templates">
                <h3 style="
        text-transform: none;
        font-weight: 600;
        font-size: 16px;
        line-height: 22px;
        margin-bottom: 20px; 
         
    ">Custom templates</h3>
                <p style="font-size: 14px;
    line-height: 22px;
    margin-bottom: 25px;">Would you like to order a special design that can't be created using our 3D Designer?</p>
                <p style="font-size: 14px;
    line-height: 22px;
    margin-bottom: 25px;">Then use our <a href="#" style="color:#0071B9">Special Design Service.</a></p>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12 text-center">
            <hr>
            <h4>CUSTOMER REVIEWS</h4>
            <div class="d-flex justify-content-center align-items-center mb-3">
                <div class="star-rating me-2">
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star-half-alt text-warning"></i>
                </div>
                <span class="fs-5">4.8 out of 5</span>
            </div>
            <a style="color: black" href="#" class="go-to-top">
                <i class="fas fa-arrow-up"></i>
                <span>Go to top</span>
            </a>
        </div>
    </div>
</div>



    <div class="tab-pane fade container my-5" id="colors">
    <div class="row">
        <div class="col-8 mx-auto">
            <h1 class="fw-bold mb-3" style="margin-bottom: 25px;
    font-size: 20px;
    line-height: 1.45em;">Colors and Combinations</h1>
            <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 14px;
    line-height: 22px;
    margin-bottom: 25px;" class="mb-5">
                You will have to choose your color layout for your design – we know this not easy when looking at the 152 preset color options
                provided and considering the nearly 100,000 possible color combinations. If you already have a very exact understanding of the
                color you would like to use (because you have corporate identity colors, or work in the printing business), please feel free to
                communicate your RAL or PMS values to your ricona account manager – we will then add this to your 3D profile so you can give
                your design the last touch.
            </p>
        </div>
    </div>

   <div class="row g-4 justify-content-center align-items-start">
    <!-- Left Column -->
    <div class="col-md-4">
        <h3 class="fw-bold mb-3" style="text-transform: none;
    font-weight: 600;
    margin-bottom: 40px; font-size: 20px;">Standard Colors</h3>
        <div class="color-grid border-end pe-3" style=" border-right: 4px; border-color:#cdcdcd;">
            <div class="color-swatch" style="background-color: #fcd60f;">
                <span class="color-label">Yellow 3</span>
            </div>
            <div class="color-swatch" style="background-color: #ff8326;">
                <span class="color-label">Yellow 3</span>
            </div>
            <div class="color-swatch" style="background-color: #d52231;">
                <span class="color-label">Yellow 3</span>
            </div>
            <div class="color-swatch" style="background-color: #6d2a31;">
                <span class="color-label">Orange 3</span>
            </div>
            <div class="color-swatch" style="background-color: #da3b6f;">
                <span class="color-label">Red 3</span>
            </div>
            <div class="color-swatch" style="background-color: #017abb">
                <span class="color-label">Red 5</span>
            </div>
            <div class="color-swatch" style="background-color: #323950;">
                <span class="color-label">Magenta 3</span>
            </div>
            <div class="color-swatch" style="background-color: #2e8c3e;">
                <span class="color-label">Blue 3</span>
            </div>
            <div class="color-swatch" style="background-color: #9bc94a;">
                <span class="color-label">Royal 5</span>
            </div>
            <div class="color-swatch" style="background-color: #939794;">
                <span class="color-label">Green 3</span>
            </div>
        </div>
        <h3 class="fw-bold mb-3" style=" text-transform: none;
    font-weight: 600;
    margin-bottom: 40px; margin-top: 180px;font-size: 20px;">Glaze Colors</h3>

    <div class="color-grid border-end pe-3" 
        style="border-right: 4px solid #cdcdcd; display: flex; flex-wrap: wrap; ">

        <!-- First Row: White + Black -->
        <div class="color-swatch" style="background-color: #ffffff;  border: 1px solid grey;">
            <span class="color-label">White</span>
        </div>
        <div class="color-swatch" style="background-color: #000000;">
            <span class="color-label ">Black</span>
        </div>

        <!-- Break to start next row -->
        <div style="flex-basis: 100%;"></div>

        <!-- Second Row onward -->
        <div class="color-swatch" style="background-color: #fffa84; ">
            <span class="color-label">Red</span>
        </div>
        <div class="color-swatch" style="background-color: #fee837; ">
            <span class="color-label">Orange 3</span>
        </div>
        <div class="color-swatch" style="background-color: #fcd60f;">
            <span class="color-label">Red 3</span>
        </div>
        <div class="color-swatch" style="background-color: #fac80b; ">
            <span class="color-label">Red 5</span>
        </div>
        <div class="color-swatch" style="background-color: #e4b010;">
            <span class="color-label">Magenta 3</span>
        </div>
        <div class="color-swatch" style="background-color: #ffcd55; ">
            <span class="color-label">Blue 3</span>
        </div>
        <div class="color-swatch" style="background-color: #ffb637; ">
            <span class="color-label">Royal 5</span>
        </div>
        <div class="color-swatch" style="background-color: #ff8326; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #f95c1b; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #cf4216; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #ffba9c; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #ff7a62; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #f03b2b; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #b92b32; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #6d2a31; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #ffa6a9">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #ff5e75; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #e91c56; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #e91c56; ">
            <span class="color-label">Green 3</span>
        </div>
        <div class="color-swatch" style="background-color: #a5123d; ">
            <span class="color-label">Green 3</span>
    </div>
    <div class="color-swatch" style="background-color:#ff9fc1;">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #fa72a9;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color: #da3b6f;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #bc3266;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #862b5a;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color: #efc3e1;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #ce89bb;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:  #af5589;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #833f6e;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #6d3359;">
                <span class="color-label">Steel Blue 5</span>
            </div>
             <div class="color-swatch" style="background-color: #c0afd0;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #a081ba;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:  #695190;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #593979;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #452757;">
                <span class="color-label">Steel Blue 5</span>
            </div>
    <div class="color-swatch" style="background-color:#bfcdd0;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color:#9bb3bd;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:  #5b7f96;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #3b5769;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #282e3b;">
                <span class="color-label">Steel Blue 5</span>
            </div>

      <div class="color-swatch" style="background-color:#a0bcd4;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color:#628ec0;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:   #345188;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #2d3f6d;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #323950;">
                <span class="color-label">Steel Blue 5</span>
            </div>
      <div class="color-swatch" style="background-color:#96c8da;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color:#4aaad6;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:   #017abb;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #1f5a91;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #2a415f;">
                <span class="color-label">Steel Blue 5</span>
            </div>
              <div class="color-swatch" style="background-color:#95d5e6;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color:#66bdd0;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:   #1287ab;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #116c8a;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #0b4861;">
                <span class="color-label">Steel Blue 5</span>
            </div>
  <div class="color-swatch" style="background-color:#8fdad5;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color:#60c8c2;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:   #299c9d;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #12797c;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #0a5860;">
                <span class="color-label">Steel Blue 5</span>
            </div>
              <div class="color-swatch" style="background-color:#c5dedc;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color:#9cc2c4;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:   #6fa2aa;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #4d7c87;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #195b6d;">
                <span class="color-label">Steel Blue 5</span>
            </div>


             <div class="color-swatch" style="background-color:#baeed1;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color:#7fc9b1;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:   #29a67f;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #06826d;">
                <span class="color-label">Steel Blue 5</span>
            </div>
               <div class="color-swatch" style="background-color:  #0c5b56;">
                <span class="color-label">Steel Blue 5</span>
            </div>
</div>
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
        <h3 class="fw-bold mb-3" style="text-transform: none;
    font-weight: 600;
    margin-bottom: 40px;font-size: 20px;">Colors in vogue</h3>
        <div class="color-grid ps-0">
            <div class="color-swatch" style="background-color: #345188">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #c12519;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color: #f95c1b;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #fffa84;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #676d69;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color: #949029;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #60c8c2;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color: #282e3b">
                <span class="color-label">Steel Blue 5</span>
            </div>

            <div class="color-swatch" 
                 style="position: relative; 
                        background-image: url('/images/sonderfarben.webp'); 
                        background-size: cover; 
                        background-position: center;     
                        width: 140px;">
                <span style="width: 110px;
                             position: absolute;
                             top: 15px;
                             left: 50%;
                             transform: translateX(-50%);
                             background: #fff;
                             text-align: center;
                             color: #333;
                             box-shadow: 6px 6px 8px #333;" 
                      class="text-dark">
                      Special Colors
                </span>
                <span class="color-label">Steel Blue 5</span>
            </div>

            
        </div>
         <h3 class="fw-bold mb-3" style=" text-transform: none;
    font-weight: 600;
    margin-bottom: 40px; margin-top: 180px; font-size: 20px;">Colors in vogue</h3>
        <div class="color-grid ps-0">
            <div class="color-swatch" style="background-color: #cbe8bc;">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #87d18c;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color: #37a469;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #138b63;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #166d56;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color: #c1e1c4;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #a1c99d;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#70a479;">
                <span class="color-label">Steel Blue 5</span>
            </div>
<div class="color-swatch" style="background-color: #518365;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#2b4f43;">
                <span class="color-label">Steel Blue 5</span>
            </div>
             <div class="color-swatch" style="background-color: #9bd68d;">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #5fae40;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color: #2e8c3e;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #01733a;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #214a35;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color: #cae76e;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #9bc94a;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#6c9f3f;">
                <span class="color-label">Steel Blue 5</span>
            </div>
<div class="color-swatch" style="background-color: #577b3d;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#365535;">
                <span class="color-label">Steel Blue 5</span>
            </div>
                 <div class="color-swatch" style="background-color: #cfd958;">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #c0bb16;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color: #949029;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #5b5d20;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #414523;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color: #ded45b;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #d7bc32;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#c6a02e;">
                <span class="color-label">Steel Blue 5</span>
            </div>
<div class="color-swatch" style="background-color: #a18528;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#5f521b;">
                <span class="color-label">Steel Blue 5</span>
            </div>
                        <div class="color-swatch" style="background-color: #e2d5b3;">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #cdba91;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color:#b09a6d;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #9e7f4b;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #725b35;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color:#f7bb75;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #b48844;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#8e5d29;">
                <span class="color-label">Steel Blue 5</span>
            </div>
<div class="color-swatch" style="background-color: #644a25;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#4d3f27;">
                <span class="color-label">Steel Blue 5</span>
            </div>
                         <div class="color-swatch" style="background-color: #f3bb79;">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #c68448;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color:#ab5b2d;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #753a1b;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #543826;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color:#f2d7b8;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #edbe91;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#c87f58;">
                <span class="color-label">Steel Blue 5</span>
            </div>
<div class="color-swatch" style="background-color: #a85541;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#603a36;">
                <span class="color-label">Steel Blue 5</span>
            </div>
                          <div class="color-swatch" style="background-color: #ced2cd;">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #aeb3b0;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color:#939794;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #676d69;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #3a403e;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color:#dbd2c3;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #bab1a0;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#9d9180;">
                <span class="color-label">Steel Blue 5</span>
            </div>
<div class="color-swatch" style="background-color: #837969;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#585047;">
                <span class="color-label">Steel Blue 5</span>
            </div>



                            <div class="color-swatch" style="background-color: #e0eae9;">
                <span class="color-label">Royal 3</span>
            </div>
            <div class="color-swatch" style="background-color: #e5f7e2;">
                <span class="color-label">Orange Red 4</span>
            </div>
            <div class="color-swatch" style="background-color:#ffd9cf;">
                <span class="color-label">Orange 4</span>
            </div>
            <div class="color-swatch" style="background-color: #faebf4;">
                <span class="color-label">Yellow 1</span>
            </div>
            <div class="color-swatch" style="background-color: #ffffc1;">
                <span class="color-label">Cool Grey 4</span>
            </div>
            <div class="color-swatch" style="background-color:#1bb0dd;">
                <span class="color-label">Olive 3</span>
            </div>
            <div class="color-swatch" style="background-color: #b3e754;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#ff366d;">
                <span class="color-label">Steel Blue 5</span>
            </div>
<div class="color-swatch" style="background-color: #fd3832;">
                <span class="color-label">Lagoon 2</span>
            </div>
            <div class="color-swatch" style="background-color:#f9f944;">
                <span class="color-label">Steel Blue 5</span>
            </div>
    </div>
</div>
<div id="colorModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div class="modal-header">
            <div id="modal-color-box" class="modal-color-swatch"></div>
            <div class="modal-info">
                <h2 id="modal-color-name" class="modal-title"></h2>
                <p id="modal-color-code" class="modal-subtitle"></p>
               
                <div class="oclo-info">
                    <p>oclo</p>
                    <p id="modal-oclo-match"></p>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>

      

    </div>
    <!-- Patterns Section -->
      <div class="tab-pane fade container my-5" id="patterns">
    <div class="row">
        <div class="col-8 mx-auto">
        <h2 class="fw-bold mb-3 text-center">Available Patterns</h2>
        
        <!-- Debug info -->
        <div class="text-center mb-3">
          <small style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">Product has {{ $product->patterns ? $product->patterns->count() : 0 }} patterns assigned</small>
        </div>
        
        @if($product->patterns && $product->patterns->count() > 0)
          <div class="row g-3">
            @foreach($product->patterns as $pattern)
              <div class="col-6 col-md-4 col-lg-3">
                <div class="design-card text-center p-3" style="border: 1px solid #ddd; border-radius: 8px;">
                  <div class="bg-light d-flex align-items-center justify-content-center mb-2" 
                       style="height: 120px; border-radius: 0.5rem;">
                    @if($pattern->svg_content)
                      <div style="width: 100%; height: 100%; overflow: hidden;">
                        {!! $pattern->svg_content !!}
                      </div>
                    @else
                      <i class="fas fa-palette fa-2x" style="color: rgb(51, 50, 49);"></i>
                    @endif
                  </div>
                  <h6 class="mb-1">{{ $pattern->name }}</h6>
                  <small style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">SVG Pattern (ID: {{ $pattern->id }})</small>
                </div>
              </div>
            @endforeach
          </div>
          <script>
            console.log('Patterns rendered:', {{ $product->patterns->count() }});
          </script>
        @else
          <div class="text-center py-5">
            <i class="fas fa-palette fa-3x mb-3" style="color: rgb(51, 50, 49);"></i>
            <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">No patterns assigned to this product yet.</p>
          </div>
        @endif
        </div>
    </div>
      </div>

      <!-- Fonts (empty for now) -->
      <div class="tab-pane fade" id="fonts">
        <h2 class="fw-bold mb-3 text-center">Fonts Section</h2>
        <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="text-center">Fonts ka content baad me add hoga.</p>
      </div>

      <!-- Logos (empty for now) -->
      <div class="tab-pane fade" id="logos">
        <h2 class="fw-bold mb-3 text-center">Logos Section</h2>
        <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="text-center">Logos ka content baad me add hoga.</p>
      </div>

      <!-- Prices (empty for now) -->
      <div class="tab-pane fade" id="prices">
        <h2 class="fw-bold mb-3 text-center">Prices Section</h2>
        <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="text-center">Prices aur lead times ka content baad me add hoga.</p>
      </div>

      <!-- Sizes (empty for now) -->
      <div class="tab-pane fade" id="sizes">
        <h2 class="fw-bold mb-3 text-center">Sizes Section</h2>
        <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="text-center">Sizes ka content baad me add hoga.</p>
      </div>
  </div>
 
   

</section>

</div>
@php
$designsArray = $product->designs->map(function($d) {
    return [
        'id' => $d->id,
        'name' => $d->name,
        'front' => asset('storage/'.$d->front_image),
        'back' => asset('storage/'.$d->back_image),
        'left' => asset('storage/'.$d->left_image),
        'right' => asset('storage/'.$d->right_image),
    ];
})->toArray();
@endphp

<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("colorModal");
    const closeBtn = document.querySelector(".close-btn");
    const colorSwatches = document.querySelectorAll(".color-swatch");
    
    // Function to open the modal
    function openModal(colorInfo) {
        // Set dynamic content
        document.getElementById("modal-color-name").textContent = colorInfo.name;
        document.getElementById("modal-color-code").textContent = `Color-Code: ${colorInfo.code}`;
       
        document.getElementById("modal-oclo-match").textContent = colorInfo.ocloMatch;
        document.getElementById("modal-color-box").style.backgroundColor = colorInfo.backgroundColor;

        // Display the modal
        modal.style.display = "flex";
    }

    // Event listener for each color swatch
    colorSwatches.forEach(swatch => {
        swatch.addEventListener("click", () => {
            // Get color information from the clicked swatch
            const colorName = swatch.querySelector(".color-label").textContent;
            const colorCode = swatch.style.backgroundColor;
            const backgroundColor = swatch.style.backgroundColor;

            // Prepare dynamic data (you would need to fetch this from a data source)
            const colorData = {
                name: colorName,
                code: colorCode,
               
                ocloMatch: "ricona color matching CM5",
                backgroundColor: backgroundColor
            };

            openModal(colorData);
        });
    });

    // Event listener to close the modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal if user clicks outside of it
    window.addEventListener("click", (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {

    // Prices
    let prices = @json($product->prices ?? []);
    if (typeof prices === 'string') prices = JSON.parse(prices);
    
    // Convert object format to array format if needed
    if (prices && typeof prices === 'object' && !Array.isArray(prices)) {
        prices = Object.values(prices);
    }
    
    // Ensure we have at least one price entry
    if (!prices || prices.length === 0) {
        prices = [{ min_quantity: 1, price: {{ $product->price ?? 0 }} }];
    }

    const quantityInput = document.getElementById('quantity');
    const priceDisplay = document.getElementById('price');

    function updatePrice() {
        let qty = parseInt(quantityInput.value) || 1;
        if (qty > 999) { qty = 999; quantityInput.value = 999; }

        let selectedPrice = parseFloat(prices[0].price);
        prices.forEach(p => {
            if (qty >= parseInt(p.min_quantity)) selectedPrice = parseFloat(p.price);
        });

        priceDisplay.textContent = `$${selectedPrice.toFixed(2)}`;
    }

    updatePrice();
    quantityInput.addEventListener('input', updatePrice);

    // Design sides logic
    const sides = ['front', 'right', 'back', 'left'];
    let currentSide = 0;

    function showDesignFromPreview(img) {
        document.getElementById('design-name').textContent = 'Design ' + img.dataset.name;
        document.getElementById('design-front').src = img.dataset.front;
        document.getElementById('design-back').src = img.dataset.back;
        document.getElementById('design-left').src = img.dataset.left;
        document.getElementById('design-right').src = img.dataset.right;

        // Show only front side
        sides.forEach(s => {
            document.getElementById('design-' + s).style.display = (s === 'front') ? 'block' : 'none';
        });
        currentSide = 0;
    }

    // Rotate button
    document.getElementById('rotate-btn').addEventListener('click', function() {
        const img = document.getElementById('design-' + sides[currentSide]);
        sides.forEach(s => document.getElementById('design-' + s).style.display = 'none');
        img.style.display = 'block';
        currentSide = (currentSide + 1) % sides.length;
    });

    // Left/Right buttons (optional if you want)
    let currentDesignImg = document.querySelector('.design-preview'); // start with first preview
    document.getElementById('prev-design').addEventListener('click', function() {
        let previews = Array.from(document.querySelectorAll('.design-preview'));
        let idx = previews.indexOf(currentDesignImg);
        idx = (idx - 1 + previews.length) % previews.length;
        currentDesignImg = previews[idx];
        showDesignFromPreview(currentDesignImg);
    });
    document.getElementById('next-design').addEventListener('click', function() {
        let previews = Array.from(document.querySelectorAll('.design-preview'));
        let idx = previews.indexOf(currentDesignImg);
        idx = (idx + 1) % previews.length;
        currentDesignImg = previews[idx];
        showDesignFromPreview(currentDesignImg);
    });

    // Preview images click
    document.querySelectorAll('.design-preview').forEach(function(img) {
        img.addEventListener('click', function() {
            currentDesignImg = this;
            showDesignFromPreview(this);
        });
    });

});
</script>






@endsection