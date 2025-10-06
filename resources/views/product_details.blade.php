@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 p-0">

        <div class="hero-section">
    @php
        $fallbackImage = $parentCategory?->category_image
            ?? $parentCategory?->home_image
            ?? $parentCategory?->image;
    @endphp
    <img
        src="{{ $firstProductType && $firstProductType->image
                ? asset('storage/' . $firstProductType->image)
                : ($fallbackImage
                    ? asset('storage/' . $fallbackImage)
                    : asset('images/default.jpg')) }}"
        class="img-fluid w-100"
        alt="{{ $firstProductType->name ?? $parentCategory->name ?? 'Image' }}">
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
                <h2 class="mb-3" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">Custom Soccer kits</h2>
                <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; margin-bottom: 0.5rem;">ricona manufactures custom soccer jerseys, shirts and team uniforms of professional quality. Your</p>
                <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; margin-bottom: 0.5rem;">soccer jerseys are created according to your exact specifications. Choose your own design, colors,</p>
                <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; margin-bottom: 0.5rem;">texts and add any logos. All for no extra cost.</p>
                <a href="#" class="btn btn-primary btn-lg mt-4" style="background-color: rgb(51, 50, 49); border-color: rgb(51, 50, 49); color: rgb(255, 252, 250); font-family: 'Instrument Sans', sans-serif;">Design in 3D now</a>
            </div>

         <div class="row mt-5">
    {{-- Category / Product Type Title --}}
    <h5 class="col-12 mb-5" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">
        {{ strtoupper($firstProductType->name ?? $parentCategory->name ?? "Products") }}
    </h5>

    @forelse($products as $product)
        <div class="col-md-4 mb-4">
            <a href="{{ route('product-page', $product->id) }}" style="text-decoration: none; color: inherit;">
                {{-- Product Image --}}
                <img class="img-fluid rounded shadow mb-3"
                     src="{{ asset('storage/' . ($product->image ?? 'default.png')) }}"
                     alt="{{ $product->name }}"
                     style="height: 250px; object-fit: cover;">

                {{-- Product Name --}}
                <h6 class="mb-2"
                    style="color: {{ $colors[$product->product_type_id] ?? '#333' }}; font-family: 'Instrument Sans', sans-serif;">
                    {{ $product->name }}
                </h6>

                {{-- Product Description --}}
                @if(!empty($product->details))
                    <div style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 0.9rem; margin-bottom: 1rem;">
                        @foreach(explode("\n", $product->details) as $line)
                            <p style="margin-bottom: 0.25rem;">{{ $line }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Prices --}}
                @php
                    $prices = $product->getPricingTiers();
                    $priceMapping = [
                        1  => '1 piece',
                        10 => '10 pieces',
                        50 => '50 pieces'
                    ];
                @endphp

                @if(!empty($prices))
                    @foreach($prices as $priceEntry)
                        <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.25rem;">
                            {{ $priceMapping[$priceEntry['min_quantity']] ?? $priceEntry['min_quantity'] . ' pieces' }}: ${{ number_format(floatval($priceEntry['price']), 2) }} per piece
                        </p>
                    @endforeach
                @else
                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 0.9rem; font-weight: bold;">
                        Price: ${{ number_format(floatval($product->price ?? 0), 2) }}
                    </p>
                @endif
            </a>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <p>No products available for this category.</p>
            </div>
        </div>
    @endforelse
</div>


 <hr class="mt-8">
<div class="row mt-6">
    <h5 class="col-12 mb-4" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">SEE OTHER PRODUCTS FROM OUR CATALOG</h5>
   <div class="d-flex flex-wrap justify-content-center mb-4">
    @foreach($productTypes as $type)
        <a href="{{ route('show_product_type', $type->id) }}"
           class="btn m-2"
           style="background-color: rgb(51, 50, 49); border-color: rgb(51, 50, 49); color: rgb(255, 252, 250); font-family: 'Instrument Sans', sans-serif; border-radius: 0; padding: 10px 20px;">
           {{ $type->name }}
        </a>
    @endforeach
</div>
</div>

  <div class="row mt-5">
                <h5 class="col-12 mb-4" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">HERE'S HOW THE 3D DESIGNER WORKS</h5>

 <div class="col-12 d-flex justify-content-center">
  <video class="img-fluid rounded shadow" controls style="max-height:500px; width: 100%; object-fit: cover;">

        <source src="{{ asset($designerVideo ?? 'images/designer_configurator_soccer_english.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
  </video>
</div>



</div>

            <div class="row mt-5">
                <h5 class="col-12 mb-4" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">FEEDBACK ON SOCCER JERSEYS</h5>
                <div class="col-md-3 mb-4">
                   <div class="d-flex mb-2">
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
</div>

                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Appreciated the assistance with my questions and the ability to design exactly what I wanted for a special gift. Will recommend
                    </p>
                   <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-weight: bold;">5/5</p>

                </div>
                <div class="col-md-3 mb-4">
                   <div class="d-flex mb-2">
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
</div>

                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Fantastic quality & customization with quick shipping & active response team when needed
                    </p>
                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-weight: bold;">5/5</p>

                </div>
                <div class="col-md-3 mb-4">
                   <div class="d-flex mb-2">
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
</div>

                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Me and My youth bowler absolutely love the shirts. The came out very good and will be back to order more in the future.
                    </p>
                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-weight: bold;">5/5</p>

                </div>
                <div class="col-md-3 mb-4">
                    <div class="d-flex mb-2">
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
  <i class="fas fa-star" style="color: rgb(51, 50, 49);"></i>
</div>

                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Great as always
                    </p>
                    <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif; font-weight: bold;">5/5</p>

                </div>
               <div class="col-12 text-end">
  <a href="#" style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">See more reviews</a>
</div>
</div>

            <div class="row mt-5">
    <h5 class="col-12 mb-4" style="color: rgb(26, 26, 25); font-family: 'Instrument Sans', sans-serif;">EXAMPLES</h5>
    <div class="row justify-content-center">
        @forelse($examples as $example)
            <div class="col-12 col-md-4 d-flex justify-content-center mb-3">
                @if($example->image)
                    <img class="img-fluid rounded shadow" src="{{ asset('storage/' . $example->image) }}"
                         alt="{{ $example->name }}" style="height: 250px; object-fit: cover;">
                @else
                    <img class="img-fluid rounded shadow" src="{{ asset('images/default.jpg') }}"
                         alt="No Image" style="height: 250px; object-fit: cover;">
                @endif
            </div>
        @empty
            <p style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;" class="col-12 text-center">No examples available.</p>
        @endforelse
    </div>
    <div class="col-12 text-end mt-2">
        <a href="#" style="color: rgb(51, 50, 49); font-family: 'Instrument Sans', sans-serif;">See more examples</a>
    </div>
</div>

            </div>
        </div>


    </div>
</div>
@endsection