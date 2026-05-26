$(document).ready(function () {
    if ($('.product-slider.owl-carousel').length) {
        $('.product-slider.owl-carousel').owlCarousel({
            items: 4,
            loop: true,
            margin: 16,
            autoplay: true,
            autoplayTimeout: 3800,
            autoplayHoverPause: true,
            smartSpeed: 600,
            responsive: {
                0:   { items: 1 },
                576: { items: 2 },
                768: { items: 3 },
                992: { items: 4 }
            }
        });
    }
});
