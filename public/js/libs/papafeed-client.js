(function ($) {
    $ = $.extend($, {
        papafeed: function (method, options) {
            options = $.extend({
                autoRun: false,
                currencies: [],
                interval: 1000,
                response: function (response, intervalId) {
                    return false;
                }
            }, options);

            var url = '',
                formData = new FormData(),
                postHandler = function () {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false
                    }).done(function (response) {
                        options.response(response, intervalId);
                    });
                };

            switch (method) {
                case 'get-rates':
                    url = '/get-currency-rates';

                    $(options.currencies).each(function (i, v) {
                        formData.set('currencies[' + i + ']', v);
                    });
                    break;
            }

            var intervalId = setInterval(postHandler, options.interval);

            if(options.autoRun){
                postHandler();
            }
        }
    });
})(jQuery);