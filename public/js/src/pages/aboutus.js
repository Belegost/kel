window.addEventListener('load', function() {
    $(".home-owl-carousel.owl-carousel").owlCarousel({
        autoplay: true,
        autoplayTimeout: 3000,
        items: 3,
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            1024: { items: 3 },
            1170: { items: 3 },
            1400: { items: 4 }
        }
    });
});
window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
});