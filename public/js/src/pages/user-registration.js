window.addEventListener('load', function() {
    $("#sign_up_phone").intlTelInput({
        utilsScript: "/js/libs/intlTelInputUtils.min.js"
    });
    $("#sign_up_phone").mask('+000000000000');

    $('.avatars-set-item').on('click', function(){
        var img = $(this).find('img').attr('src');
        $('input[name=avatar_file_name]').val(img);
        $('.edit-avatar-current-img').css('background-image', 'url('+img+')');
    });
});