window.addEventListener('load', function() {
    incrementDigits.init(".deposit-sidebar-body .big-digits", {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 2500});
    $('#code').mask('000000');
    changeStep('.makedeposit');

    $('.makedeposit button').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        btn.prop('disabled', true);

        $('.verification-form button').prop('disabled', false);
        $('.verification-form input[name=verifying_code]').val('').prop('readonly', false);
        $('.verification-form input[name=verifying_token]').val('btc-13-13-45');
        changeStep('.verification');
        timer.start();
        btn.prop('disabled', false);
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

                    timer.clear();
                    let coinbtc = (() => {
                        let coin = 'btc';
                        let tag = '<div style="user-select: text;">Tag(Memo): got it</div>';
                        let network =
                            `
                             <fieldset>
                                <label htmlFor="btc-link">Network: BTC <span style="float: right;">(Fee: 0.2 BTC)</span></label>
                                <input name="wallet_address_hash_btn" type="text" value="btc-12-23-34" readOnly/>
                                <div className="field-border"></div>
                                ${tag}
                            </fieldset>
                            `;
                        $('.' + coin + '-address').append(network);
                    })();
                    let coinusdt = (() => {
                        let coin = 'usdt';
                        let tag = '<div style="user-select: text;">Tag(Memo): got it</div>';
                        let network =
                            `
                             <fieldset>
                                <label htmlFor="btc-link">Network: USDT <span style="float: right;">(Fee: 0.2 USDT)</span></label>
                                <input name="wallet_address_hash_btn" type="text" value="btc-12-23-34" readOnly/>
                                <div className="field-border"></div>
                                ${tag}
                            </fieldset>
                            `;
                        $('.' + coin + '-address').append(network);
                    })();

                    $('.deposit-addresses label, .deposit-addresses .field-border').addClass('focused');
                    $('.deposit-main').css({
                        'overflow': 'scroll'
                    });

                    changeStep('.deposit-addresses');
                    btn.prop('disabled', false);
                inp.val('').prop('readonly', false);

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
