var pricing = {
    side: 2,
    '1m': {
        conservative: {
            price: '3,000',
            fee: '2%',
            closing: '0.3%',
            penalty: '15%',
            share: '27%'
        },
        classic: {
            price: '6,500',
            fee: '2%',
            closing: '0.5%',
            penalty: '15%',
            share: '20%'
        },
        confident: {
            price: '13,000',
            fee: '2%',
            closing: '0.5%',
            penalty: '15%',
            share: '15%'
        }
    },
    '3m': {
        conservative: {
            price: '2,500',
            fee: '2%',
            closing: '0.3%',
            penalty: '15%',
            share: '25%'
        },
        classic: {
            price: '6,000',
            fee: '2%',
            closing: '0.5%',
            penalty: '15%',
            share: '20%'
        },
        confident: {
            price: '12,000',
            fee: '2%',
            closing: '0.5%',
            penalty: '15%',
            share: '14%'
        }
    },
    '6m': {
        conservative: {
            price: '2,000',
            fee: '2%',
            closing: '0.3%',
            penalty: '15%',
            share: '25%'
        },
        classic: {
            price: '5,500',
            fee: '2%',
            closing: '0.5%',
            penalty: '15%',
            share: '20%'
        },
        confident: {
            price: '11,000',
            fee: '2%',
            closing: '0.5%',
            penalty: '15%',
            share: '12%'
        }
    },
    '12m': {
        conservative: {
            price: '1,500',
            fee: '2%',
            closing: '0.3%',
            penalty: '15%',
            share: '25%'
        },
        classic: {
            price: '5,000',
            fee: '2%',
            closing: '0.5%',
            penalty: '15%',
            share: '20%'
        },
        confident: {
            price: '10,000',
            fee: '2%',
            closing: '0.5%',
            penalty: '15%',
            share: '10%'
        }
    }
};
function setPrices(period) {
    pricing.side = pricing.side>1?1:2;
    $('.pricing-plans-item').each(function (e) {
        var wrapper = $(this);
        var plan = wrapper.hasClass('pricing-plans-item-conservative')?'conservative':wrapper.hasClass('pricing-plans-item-classic')?'classic':'confident';
        console.log(period+' - '+plan);
        var values = '';
        for( var key in pricing[period][plan]) {
            if(key!='price') {
                values += '<li><span>'+pricing[period][plan][key]+'</span> '+key+'</li>';
            }
        }
        var div = wrapper.find('.pricing-plans-item-side-'+pricing.side);
        console.log(div);
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