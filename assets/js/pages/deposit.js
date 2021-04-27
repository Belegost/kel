window.addEventListener('load', function() {
    incrementDigits.init(".deposit-sidebar-body .big-digits", {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 2500});
    $('#code').mask('000000');
    changeStep('.makedeposit');

    $('.makedeposit button').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: "/deposit/make",
            type: 'POST',
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
                success: function (response) {
                    if (response.status == 'success') {
                        $('.verification-form button').prop('disabled', false);
                        $('.verification-form input[name=verifying_code]').val('').prop('readonly', false);
                        $('.verification-form input[name=verifying_token]').val(response.data.verifying_token);
                        changeStep('.verification');
                        timer.start();
                    } else {
                        $('.makedeposit-error').slideDown(500);
                        setTimeout(function() {$('.makedeposit-error').slideUp(500); }, 2000);
                    }
                },
                complete: function () {
                    btn.prop('disabled', false);
                }
        });
    });
    $('.verification-try-againe').on('click', function(e) {
        e.preventDefault();
        $('.makedeposit button').prop('disabled', false);
        $('.verification-error').fadeOut(100);
        changeStep('.makedeposit');
        timer.clear();
        setTimeout(function() {
            $('.makedeposit-error').hide();
            $('.verification-form, .verification-timer, .verification-msg').show();
        }, 1000);
    });

    $(document.forms.verification_form).on('submit', function () {

        timer.stop();
        var formVerification = new FormData(this);
        var btn = $('.verification-form button');
        var inp = $('.verification-form input[name=verifying_code]');
        btn.prop('disabled', true);
        inp.prop('readonly', true);

        $.ajax({
            url: "/deposit/address",
            type: 'POST',
            data: formVerification,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 'success') {
                    timer.clear();
                    $('.btc-address label, .btc-address .field-border').addClass('focused');
                    $('.btc-address input[name=wallet_address_hash]').val(response.data.wallet_address_hash);
                    // $('.deposit-qr img').attr('src', response.data.wallet_address_qr);

                    $('.usdt-address label, .usdt-address .field-border').addClass('focused');
                    $('.usdt-address input[name=wallet_address_hash_usdt]').val(response.data.waller_address_hash_usdt);
                    // $('.deposit-qr img').attr('src', response.data.wallet_address_qr);

                    changeStep('.btc-address');
                } else {
                    timer.stop();
                    $('.verification-msg').html(response.message).slideDown(500);
                    setTimeout(function() {$('.verification-msg').slideUp(500); }, 3000);
                }
            },
            complete: function () {
                btn.prop('disabled', false);
                inp.val('').prop('readonly', false);
            }
        });
        return false;
    });
});
var timer = function()  {
    var seconds = 119;
    var tmr;
    return {
        clear: function() {
            timer.stop();
            seconds = 119;
        },
        stop: function () {
            clearTimeout(tmr);
        },
        start: function() {
            seconds--;
            if(seconds>0) {
                vTimer.value(seconds);
                tmr = setTimeout(function() {
                    timer.start();
                }, 1000);
            } else {
                timer.stop();
                $('.verification-form, .verification-timer, .verification-msg').slideUp(400, function() {
                    setTimeout(function() { $('.verification-error').fadeIn(800); }, 600);
                });
            }
        }
    }
}();
var vTimer = radialIndicator('.verification-timer', {
    radius: 37,
    barWidth: 3,
    barColor: {
        0: '#e82b07',
        30: '#e82b07',
        35: '#0195e8',
        120: '#0195e8'
    },
    minValue: 0,
    maxValue: 120,
    initValue: 119,
    fontWeight: 'normal',
    roundCorner: false,
    format: function (value) {
        var data = value<60?'00 : '+(value<10?'0': '')+value:'01 :'+(value-60<10?'0': '')+(value-60);
        return data;
    }
});
function changeStep(el) {
    var current = $('.deposit-main .visible');
    current.addClass('hidden').removeClass('visible');
    setTimeout(function() { current.removeClass('hidden'); }, 600);
    $(el).addClass('visible');
}