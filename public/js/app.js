// public/js/app.js
document.addEventListener('DOMContentLoaded', function() {
    // --- Carousel Logic (Top Sellers & Category) ---
    const topSellersCarousel = document.getElementById('top-sellers-carousel');
    const topSellersScrollLeftBtn = document.getElementById('top-sellers-scroll-left');
    const topSellersScrollRightBtn = document.getElementById('top-sellers-scroll-right');
    const categoryCarousel = document.getElementById('category-carousel');
    const categoryScrollLeftBtn = document.getElementById('scroll-left');
    const categoryScrollRightBtn = document.getElementById('scroll-right');

    const scrollStep = 300; // Adjust scroll distance as needed

    // Function to handle carousel scrolling
    function setupCarousel(carousel, leftBtn, rightBtn) {
        if (!carousel || !leftBtn || !rightBtn) return; // Exit if elements don't exist
        
        leftBtn.addEventListener('click', (e) => {
            e.preventDefault();
            carousel.scrollBy({
                left: -scrollStep,
                behavior: 'smooth'
            });
        });

        rightBtn.addEventListener('click', (e) => {
            e.preventDefault();
            carousel.scrollBy({
                left: scrollStep,
                behavior: 'smooth'
            });
        });
    }

    // Initialize both carousels
    setupCarousel(topSellersCarousel, topSellersScrollLeftBtn, topSellersScrollRightBtn);
    setupCarousel(categoryCarousel, categoryScrollLeftBtn, categoryScrollRightBtn);
    
    // --- Overlay Logic (Search & Cart) ---
    const fullscreenSearchOverlay = document.getElementById('fullscreenSearchOverlay');
    const openFullscreenSearchBtn = document.getElementById('openFullscreenSearch');
    const closeFullscreenSearchBtn = document.getElementById('closeFullscreenSearch');

    const cartOverlay = document.getElementById('cartOverlay');
    const openCartOverlayBtn = document.getElementById('openCartOverlay');
    const closeCartOverlayBtn = document.getElementById('closeCartOverlay');

    // Function to handle overlay toggle
    function setupOverlay(openBtn, closeBtn, overlay) {
        if (!openBtn || !closeBtn || !overlay) return; // Exit if elements don't exist

        openBtn.addEventListener('click', function(event) {
            event.preventDefault();
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        });

        closeBtn.addEventListener('click', function(event) {
            event.preventDefault();
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        });
    }

    // Initialize both overlays
    setupOverlay(openFullscreenSearchBtn, closeFullscreenSearchBtn, fullscreenSearchOverlay);
    setupOverlay(openCartOverlayBtn, closeCartOverlayBtn, cartOverlay);
});