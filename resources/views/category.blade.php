@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 p-0">

        <div class="hero-section">
    @php
        $heroImage = $parentCategory?->category_image
            ?? $parentCategory?->home_image
            ?? $parentCategory?->image;
    @endphp
    <img
        src="{{ $heroImage ? asset('storage/' . $heroImage) : asset('images/default.jpg') }}"
        class="img-fluid w-100"
        alt="{{ $parentCategory->name ?? 'Category Image' }}">
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
                <h2 class="mb-3 custom-text">Custom {{ $parentCategory->name }} Equipment</h2>
                <p class="mb-1 custom-text-p">ricona manufactures custom {{ strtolower($parentCategory->name) }} jerseys, shirts and team uniforms of professional quality. Your</p>
                <p class="mb-1 custom-text-p">{{ strtolower($parentCategory->name) }} jerseys are created according to your exact specifications. Choose your own design, colors,</p>
                <p class="mb-1 custom-text-p">texts and add any logos. All for no extra cost.</p>
                <a href="#" class="btn btn-primary btn-lg mt-5">Design in 3D now</a>
            </div>

         <div class="row mt-5">
    {{-- Category / Product Type Title --}}
    <h5 class="col-12 mb-5">
        {{ strtoupper($parentCategory->name) }} PRODUCTS
    </h5>

    @forelse($productTypes as $productType)
        <div class="col-md-4 mb-4">
            <a href="{{ route('product_details', $productType->id) }}">
                {{-- Product Type Image --}}
                <img class="img-fluid"
                     src="{{ asset('storage/' . ($productType->image ?? 'default.png')) }}"
                     alt="{{ $productType->name }}">

                {{-- Product Type Name --}}
                <h6 class="mt-3 product-title"
                    style="color: {{ $colors[$productType->id % 5 + 1] ?? '#333' }}">
                    {{ $productType->name }}
                </h6>

                {{-- Product Type Description --}}
                <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="small">{{ $productType->subcategory->name ?? '' }}</p>
            </a>
        </div>
    @empty
        <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="col-12 text-center">No product types available for this category.</p>
    @endforelse
</div>


 <hr class="mt-8">
<div class="row mt-6">
    <h5 class="col-12 mb-4">SEE OTHER SPORTS FROM OUR CATALOG</h5>
   <div class="d-flex flex-wrap justify-content-center mb-4">
    @foreach(\App\Models\ParentCategory::all() as $otherCategory)
        @if($otherCategory->id != $parentCategory->id)
            <a href="{{ route('show_category', $otherCategory->id) }}"
               class="btn btn-outline-primary m-2">
               {{ $otherCategory->name }}
            </a>
        @endif
    @endforeach
</div>
</div>

  <div class="row mt-5">
                <h5 class="col-12 mb-4">HERE'S HOW THE 3D DESIGNER WORKS</h5>

 <div class="col-12 d-flex justify-content-center">
  <video class="img-fluid" controls style="height:500px; object-fit:cover;">

        <source src="{{ asset('images/designer_configurator_soccer_english.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
  </video>
</div>



</div>

            </div>

            <div class="row mt-5">
                <h5 class="col-12 mb-4">FEEDBACK ON {{ strtoupper($parentCategory->name) }} EQUIPMENT</h5>
                <div class="col-md-3 mb-4">
                   <div class="d-flex mb-2">
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
</div>

                    <p class="mb-1">Appreciated the assistance with my questions and the ability to design exactly what I wanted for a special gift. Will recommend</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="d-flex mb-2">
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
</div>
                    <p class="mb-1">Great quality and fast delivery. The customization options are amazing and the customer service is top notch.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="d-flex mb-2">
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
</div>
                    <p class="mb-1">Perfect for our team! The jerseys look professional and the players love the comfort and fit.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="d-flex mb-2">
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
  <i class="fas fa-star text-dark fa-2x"></i>
</div>
                    <p class="mb-1">Excellent value for money. The printing quality is outstanding and the delivery was faster than expected.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection