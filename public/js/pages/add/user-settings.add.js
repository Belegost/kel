(function ($) {
    $('.avatars-set-item').on('click', function () {
        var img = $(this).find('img').attr('src');
        $('#user_settings_avatar').val(img);
        $('.edit-avatar-current-img').css('background-image', 'url(' + img + ')');
    });
})(jQuery);