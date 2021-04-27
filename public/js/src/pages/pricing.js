(function($, p) {
    let pricing = $.extend({side: 2}, p);

    function setPrices(period) {
        pricing.side = pricing.side>1?1:2;
        $('.pricing-plans-item').each(function (e) {
            var wrapper = $(this);
            var plan = wrapper.hasClass('pricing-plans-item-conservative')?'conservative':wrapper.hasClass('pricing-plans-item-classic')?'classic':'confident';
            var values = '';
            for( var key in pricing[period][plan]) {
                if(key!='price') {
                    values += '<li><span>'+pricing[period][plan][key]+'</span> '+key+'</li>';
                }
            }
            var div = wrapper.find('.pricing-plans-item-side-'+pricing.side);
            div.find('.big-digits').text(pricing[period][plan].price);
            div.find('ul').html(values);
        });
        $('.pricing-plans-wrap').toggleClass('rotate');
    }
    setPrices('6m');
    $('.pricing-periods-item').on('click', function() {
        $('.pricing-periods-item').removeClass('active');
        $(this).addClass('active');
        var indicatorOffset = this.offsetLeft;
        $('.pricing-periods-indicator').css('transform', 'translateX('+indicatorOffset+'px)');
        setPrices($(this).attr('data-period'));
    });

    window.addEventListener('load', function() {
    });
    window.addEventListener('scroll', function() {
        var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    });
})(jQuery, Window.Page.pricing);

