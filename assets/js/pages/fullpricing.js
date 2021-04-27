
$('.pricing-periods-item').on('click', function() {
    $('.pricing-periods-item').removeClass('active');
    $(this).addClass('active');
    var indicatorOffset = this.offsetLeft;
    $('.pricing-periods-indicator').css('transform', 'translateX('+indicatorOffset+'px)');
    setPrices($(this).attr('data-period'));
});

$('.p1').on('scroll', function(){
    var scroll=$(this).scrollLeft();
    $('.p2').scrollLeft(scroll);
});
window.addEventListener('load', function() {
    if($(window).width()>1139) {
        var plansWidth = $(window).width() - ($(window).width() - $('.full-pricing-plans-wrap').width()) / 2;
        $('.full-pricing-plans').width(plansWidth - 20);
    } else {
        $('.full-pricing-plans-item-wrapper').on('scroll', function(e) {
            var scroll = $(this).scrollLeft();
            $('.full-pricing-plans-item-wrapper').not(this).scrollLeft(scroll);
        });
    }
});
window.addEventListener('resize', function() {
    if($(window).width()>1139) {
        var plansWidth = $(window).width() - ($(window).width() - $('.full-pricing-plans-wrap').width()) / 2;
        $('.full-pricing-plans').width(plansWidth - 20);
    }
});