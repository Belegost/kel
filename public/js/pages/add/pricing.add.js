(function ($) {
    var popup = $('#popupProductOrder');

    $('button.btn-invest').on('click', function () {
        var period = $('div.pricing-periods-item.active'),
            name = $(this).data('product-name') + ' ( ' + period.text() + ' )',
            code = $(this).data('product-code') + '_' + period.data('period').toUpperCase() + 'O';

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