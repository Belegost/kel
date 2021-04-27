window.addEventListener('load', function() {

    $(document.forms.reset_password).on('submit', function (e) {
        e.stopPropagation();
        var formResetData = new FormData(this);
        var pass = $('#password').val();
        var pass_conf = $('#confirmpassword').val();
        var btn = $('.popup-forgot .btn');
        if(pass.length<8) {
            $('.reset-password').addClass('has_error');
        } else if(pass!=pass_conf) {
            $('.reset-confirmpassword').addClass('has_error');
        } else {
            btn.prop('disabled', true);
            $.ajax({
                url: "/password/reset",
                type: 'POST',
                data: formResetData,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == 'success') {
                        displayPopupMsg('<h3>Success!</h3>');
                        setTimeout(function(){
                            location.href = "/dashboard";
                        }, 2000);
                    } else {
                        displayPopupMsg('<h3>Error!</h3><p>'+response.message+'</p>');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false);
                    pass.val('');
                    pass_conf.val('');
                }
            });
        }

        return false;
    });
});