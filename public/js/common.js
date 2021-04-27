(function ($) {
    $('.header-user-menu-switcher').on('click', function () {
        var make = $(this).data('rate-usd') !== 'undefined' ? ($(this).data('rate-usd') === 'on' ? 'off' : 'on') : 'off';
        $.get('/switch/usd-rates/' + make, function () {
            location.reload(true);
        });
        return false;
    });
})(jQuery);