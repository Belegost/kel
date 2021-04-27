
var productsPage = (function(){
    return {
        individualSection: animatedBlocksParam('.product-structured-individual')
    }
})();
$('.product-slides-item .product-slides-item-wrap').mouseenter(function(e){
    if(windowWidth>979) {
        $(this).parent().css({'z-index': 120});
        $(this).animate(
            {transform: 'scale(1.05)'}, 500, 'linear', function () {
                $(this).mousemove(function (e) {
                    var w = this.offsetWidth;
                    var h = this.offsetHeight;
                    var offset = $(this).offset();
                    var x = (e.pageX - offset.left) - w / 2;
                    var y = (e.pageY - offset.top) - h / 2;
                    $(this).css({transform: 'perspective(1000px) rotateX(' + (-7*y/h) + 'deg) rotateY(' + (-15*x/w) + 'deg) translateZ(50px)'});
                });
            });
    }
});

$('.product-slides-item .product-slides-item-wrap').mouseleave(function() {
    $(this).unbind('mousemove');
    $(this).parent().css({'z-index': 110});
    $(this).animate({transform: 'scale(1)'}, 500, 'linear', function() {
        $(this).parent().css({'z-index': 100});
    });
    $(this).css({transform: 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(50px)'});
});
window.addEventListener('load', function() {
});
window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if(windowWidth>767) {
        if(productsPage.individualSection.section) productsPage.individualSection.sectionBg.style.transform = "translateY(" + 0.25 * (scrolled - productsPage.individualSection.sectionOffset) + "px)";
    }
});