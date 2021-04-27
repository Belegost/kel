
var integrityEnv = { isCurrentRateUSD: true };
window.addEventListener('load', function() {

    $("#sign_up_phone").intlTelInput({
        utilsScript: "/js/libs/intlTelInputUtils.min.js"
    });
    $("#sign_up_phone").mask('+000000000000');

    var dashboardHeaderDigits = $('.dashboard-header .big-digits');
    if(dashboardHeaderDigits.length) {
        $('.dashboard-header-totalbtc-value').spincrement({
            thousandSeparator: "",
            decimalPlaces: (integrityEnv.isCurrentRateUSD?2:8),
            decimalPoint: ".",
            duration: 2000
        });
        $('.dashboard-header-totalpersents-value').spincrement({
            thousandSeparator: "",
            decimalPlaces: 2,
            decimalPoint: ".",
            duration: 1500
        });
        $('.dashboard-charts-total_histogramm-btc').spincrement({
            thousandSeparator: "",
            decimalPlaces: (integrityEnv.isCurrentRateUSD?2:8),
            decimalPoint: ".",
            duration: 1500
        });
    }

    $('.avatars-set-item').on('click', function(){
        var img = $(this).find('img').attr('src');
        $('input[name=avatar_file_name]').val(img);
        $('.edit-avatar-current-img').css('background-image', 'url('+img+')');
    });
});