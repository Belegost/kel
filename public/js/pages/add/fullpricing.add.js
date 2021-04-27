(function ($) {
    var popup = $('#popupProductOrder');

    $('button.btn-invest').on('click', function () {
        var name = $('#productCode').find('option:selected').text() + '( ' + $('#productTerm').find('option:selected').text() + ' )',
            code = $('#productCode').val() + '_' + $('#productTerm').val();

        $('#popupProductName', popup).html(name);
        $('#fieldProductCode', popup).val(code);
        $('#fieldProductQuantity', popup)
            .val(null)
            .trigger('focusout');
        $('#popupProductRequest', popup).removeClass('dn');
        $('#popupProductResponse', popup).addClass('dn');
        $('#popupRequestErrors', popup).html(null).addClass('dn');
        $('fieldset', popup).removeClass('has_error');
    });
})(jQuery);