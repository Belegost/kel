window.addEventListener('load', function () {
    incrementDigits.init(".withdrawal-sidebar-body .big-digits", {decimalPlaces: 6, decimalPoint: ".", duration: 2500});
});

$('.withdrawal-wallet-proceed-btn .btn').each(function () {
    $(this).on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var amount = $($(this).data('amount')),
            formData = new FormData();

        if (amount.val().length !== 0) {
            formData.append('withdrawal[amount]', amount.val());
            formData.append('withdrawal[currency]', amount.data('currency'));
            formData.append('withdrawal[wallet_id]', amount.data('wallet-id'));

            $.ajax({
                url: '/withdrawal-order/create',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false
            }).done(function (response) {
                if (response.status !== 'success') {
                    var massage = $('<ul>');

                    $(response.data.errors).each(function (i, v) {
                        massage.append('<li>' + v + '</li>');
                    });

                    displayPopupMsg(massage);
                } else {
                    displayPopupMsg('<h5>Withdrawal order created successfully</h5>');
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                }
            });
        }

        return false;
    });
});

$('#walletname, #walletaddress, #walletcurrency').on('validate', function (e) {
    var target = $(e.currentTarget);

    if (!(target.val().length > 0)) {
        target.closest('fieldset').addClass('has_error');
        target.data('valid', false);
    } else {
        target.data('valid', true);
    }
});

$('#walletcurrency').on('focusin', function (e) {
    var context = $(this).closest('fieldset'),
        label = $('label[for="walletcurrency"]', context);
    if (!label.hasClass('focused')) {
        label.addClass('focused');
        $('.field-border', context).addClass('focused');
    }
}).on('focusout', function (e) {
    var context = $(this).closest('fieldset'),
        label = $('label[for="walletcurrency"]', context);
    if (label.hasClass('focused') && $(this).val().length === 0) {
        label.removeClass('focused');
        $('.field-border', context).removeClass('focused');
    }
});

$('.withdrawal-wallet-output .btn').on('click', function () {
    var proceed = $(this).closest('.withdrawal-wallet').find('.withdrawal-wallet-proceed');
    // if(!proceed.hasClass('visible')) {
    //     $('.withdrawal-wallet-proceed.visible').removeClass('visible');
    // }
    proceed.toggleClass('visible');
});

$('.withdrawal-wallet-amount-field input').on('input', function (e) {
    var wallet = $(this).closest('.withdrawal-wallet');
    var rate = 1 * $(this).data('rate');
    var fee = 1 * $(this).data('fee');
    var val = 1 * $(e.target).val();
    wallet.find('.withdrawal-wallet-inbtc i').text(val * rate);
    wallet.find('.withdrawal-wallet-additional-taxfee i').text(val * fee);
});

$('.popup-addwallet form').on('submit', function (e) {
    e.preventDefault();
    var wlt_name = $('#walletname'),
        wlt_address = $('#walletaddress'),
        wlt_currency = $('#walletcurrency');

    wlt_name.trigger('validate');
    wlt_address.trigger('validate');
    wlt_currency.trigger('validate');

    if (wlt_name.data('valid') && wlt_address.data('valid') && wlt_currency.data('valid')) {
        var link = $(this).data('link'),
            formData = new FormData();

        formData.append('wallet[name]', wlt_name.val());
        formData.append('wallet[address]', wlt_address.val());
        formData.append('wallet[currency]', wlt_currency.val());

        $.ajax({
            url: link,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (response) {
            if (response.status !== 'success') {
                displayPopupMsg('<h5>' + response.data.errors + '<h5>');
            } else {
                wlt_name.val(null);
                wlt_address.val(null);
                wlt_currency.val(null);

                displayPopupMsg('<h5>Wallet added<h5>');
                setTimeout(function () {
                    location.reload();
                }, 3000);
            }
        });
    }

    return false;

});
$('#withdrawalPeriod').on('change', function (e) {
    var link = $(this).data('link');
    var period = $(this).val();

    var formData = new FormData();
    formData.append('period', period);

    /*
    response.data = [
        {
            amount: '124 BTC',
            amountDesc: 'no fee',
            wallet: 'Main BTC Wallet',
            status: 'pending',
            date: '17 JUN, 2018',
            time: '12:43'
        },
        {
            amount: '124 ETH',
            amountDesc: '$23.00 transaction fee',
            wallet: 'Ethereum',
            status: 'complete',
            date: '23 JUN, 2018',
            time: '18:11'
        }
    ];
    */

    $.ajax({
        url: link,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false
    }).done(function (response) {
        if (response.status !== 'success') {
            displayPopupMsg('<h5>' + response.data.errors + '<h5>');
        } else {
            var withdrawalContent = '';
            $.each(response.data, function (key, item) {
                withdrawalContent += '<div class="withdrawal-history-row"><div class="withdrawal-history-amount">' + item.amount + '<small>' + item.amountDesc + '</small></div><div class="withdrawal-history-wallet">' + item.wallet + '</div><div class="withdrawal-history-status"><span class="withdrawal-status-' + item.status + '">' + item.status + '</span></div><div class="withdrawal-history-date">' + item.date + '<small>' + item.time + '</small></div></div>';
            });
            $('.withdrawal-history-content').html(withdrawalContent);
        }
    });
});