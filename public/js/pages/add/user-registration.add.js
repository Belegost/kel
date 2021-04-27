(function ($) {
    $('.avatars-set-item').on('click', function(){
        var img = $(this).find('img').attr('src');
        $('#sign_up_avatar').val(img);
        $('.edit-avatar-current-img').css('background-image', 'url('+img+')');
    });
})(jQuery);