window.addEventListener('load', function() {
    $('.faq-item-btn').on('click', function() {
        var currItem = $(this).parent();
        if(!currItem.hasClass('active')) {
            var prevItem = $('.faq-item.active');
            prevItem.removeClass('active');
            currItem.toggleClass('active');
            currItem.find('p').slideToggle(300);
            prevItem.find('p').slideUp(300);
        }
    });
    $('.faq-menu > div').on('click', function() {
        if(!$(this).hasClass('active')) {
            $('.faq-menu > div.active').removeClass('active');
            $(this).addClass('active');
            $('body').scrollTo($('.' + $(this).attr('data-target')), 400);
            //$('body').animate({scrollTop: $('.' + $(this).attr('data-target')).offset().top-60}, 400);
        }
    });
    $('.faq-menu-wrap button').on('click', function() {
        $('body').scrollTo($('.block-anyquetions'), 400);
        //$('body').animate({scrollTop: $('.block-anyquetions').offset().top-60}, 400);
    });
});

var menuTimeout = false;
var faqMenuOffset, faqMenuBottom, faqContent;
function menuPoints() {
    var current = $('.faq-menu div')[0];
    $('.faq-content-section').each(function() {
        if(this.getBoundingClientRect().top<200) {
            current = $('.'+$(this).attr('data-menu'));
        }
    });
    $('.faq-menu > div.active').removeClass('active');
    $(current).addClass('active');
}
window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    //console.log(faqContent.top+':'+faqContent.bottom+' ----> '+scrolled);
    if(windowWidth>979) {
        faqContent = document.querySelector('.faq-content').getBoundingClientRect();
        faqMenuOffset = document.querySelector('.faq-content').offsetTop;
        faqMenuBottom = windowHeight-document.querySelector('.faq-menu-wrap').clientHeight+250;
        menuPoints();
        if (faqContent.top < 60 && faqContent.bottom > faqMenuBottom) {
            if (menuTimeout !== false) {
                clearTimeout(menuTimeout);
            }
            menuTimeout = setTimeout(function () {
                $('.faq-menu-wrap').css('transform', 'translateY(' + (60 + scrolled - faqMenuOffset) + 'px)');
            }, 100);
        } else if(faqContent.top > 60) {
            $('.faq-menu-wrap').css('transform', 'translateY(0)');
        }
    }
});