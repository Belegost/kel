(function ($) {
    var popup = $('#popupProductOrder');

    $('button.btn-invest').on('click', function () {
        var name = $(this).data('product-name'),
            code = $(this).data('product-code');

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

    $('#fieldProductQuantity', popup).on('focusin', function () {
        if (!$(this).val().length) {
            var fieldSet = $(this).closest('fieldset');

            $('label[for="fieldProductQuantity"]', fieldSet).addClass('focused');
            $('div.field-border', fieldSet).addClass('focused');
        }
        return false;
    }).on('focusout', function () {
        if (!$(this).val().length) {
            var fieldSet = $(this).closest('fieldset');

            $('label[for="fieldProductQuantity"]', fieldSet).removeClass('focused');
            $('div.field-border', fieldSet).removeClass('focused');

        }
        return false;
    });

    $('#popupButtonContinue', popup).on('click', function () {
        var fieldQuantity = $('#fieldProductQuantity', popup),
            fieldCode = $('#fieldProductCode', popup);

        if (!fieldQuantity.val().length) {
            fieldQuantity.closest("fieldset").addClass('has_error');
            return false;
        }

        var formData = new FormData();

        formData.set(fieldQuantity.attr('name'), fieldQuantity.val());
        formData.set(fieldCode.attr('name'), fieldCode.val());

        $.ajax({
            url: '/order/create',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (response) {
            if (response.status !== 'success') {
                $('#popupRequestErrors', popup).html(response.data.errors).removeClass('dn');
            } else {
                $('#popupProductRequest', popup).addClass('dn');
                $('#popupProductResponse', popup).removeClass('dn');
            }
        });
        return false;
    });
})(jQuery);