window.addEventListener('load', function() {
    $('.terms-nav li').on('click', function() {
        $('.terms-nav li.current').removeClass('current');
        var destination = $(this).attr('data-target');
        $('body').scrollTo($('.term-content-'+destination), 400);
        $(this).addClass('current');
    });
});