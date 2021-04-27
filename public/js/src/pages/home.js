(function() {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame']
            || window[vendors[x]+'CancelRequestAnimationFrame'];
    }
    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };
    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        };
}());
var homePage = (function(){
    return {
        videoSection: animatedBlocksParam('.homepage-videosection'),
        statsSection: animatedBlocksParam('.homepage-stats'),
        bigDigitsTrust:document.querySelector('.big-digits-trust'),
        bigDigitsStrategies: document.querySelector('.big-digits-strategies')

    }
})();
var k_x = -250;
var k_y = -250;

function Kaleydoskop() {
    requestAnimationFrame(Kaleydoskop);
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if(scrolled<windowHeight) {
        k_x++;
        k_y++;
        $('.kaleydoskop.selfmove .kaleydoskop-wrapper .ksc').each(function (i) {
            $(this).css({backgroundPosition: k_x + "px " + k_y + "px"});
        });
    }
}
Kaleydoskop();
$('.page-header').on({
    mousemove: function(e) {
        $('.kaleydoskop').removeClass('selfmove');
        $(this).find(".ksc").each(function (i) {
            k_x = k_x+0.0005*(windowWidth/2-e.pageX);
            k_y = k_y+0.0005*(windowHeight/2-e.pageY);
            $(this).css({backgroundPosition: k_x + "px " + k_y + "px"});
        });
    },
    mouseleave: function(e) {
        $('.kaleydoskop').addClass('selfmove');
    }
});

window.addEventListener('load', function() {
    $('.homepage .page-header .animate-visible').removeClass('animate-visible');
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
    if(windowWidth>767) {
        if(homePage.videoSection.section) homePage.videoSection.sectionBg.style.transform = "translateY(" + 0.25 * (scrolled - homePage.videoSection.sectionOffset) + "px)";
        if(homePage.statsSection.section) homePage.statsSection.sectionBg.style.transform = "translateY(" + 0.25 * (scrolled - homePage.statsSection.sectionOffset) + "px)";
    }
    if(homePage.bigDigitsTrust&&isVisible(homePage.bigDigitsTrust)) {
        incrementDigits.init(".big-digits-trust");
    }
    if(homePage.bigDigitsStrategies&&isVisible(homePage.bigDigitsStrategies)) {
        incrementDigits.init(".big-digits-strategies");
    }
});