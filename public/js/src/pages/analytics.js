var dashboardHeaderDigits = $('.dashboard-header .big-digits');
if(dashboardHeaderDigits.length) {
    $('.dashboard-header-totalbtc-value').spincrement({
        thousandSeparator: "",
        decimalPlaces: 6,
        decimalPoint: ".",
        duration: 2000
    });
    $('.dashboard-header-totalpersents-value').spincrement({
        thousandSeparator: "",
        decimalPlaces: 2,
        decimalPoint: ".",
        duration: 1500
    });
    $('.dashboard-charts-total_histogram-btc').spincrement({
        thousandSeparator: "",
        decimalPlaces: 6,
        decimalPoint: ".",
        duration: 1500
    });
}
var instrumentCharts = {};

// Show Product Rates by Period
$('.dashboard-charts-cryptoassets-filter input').datepicker({
        autoSize: true,
        dateFormat: 'dd/mm/yy',
        autoclose: true,
        onSelect: function (e) {
            var complete = true;
            $('.dashboard-charts-cryptoassets-filter input').each(function () {
                if (!$(this).val()) complete = false;
            });
            if (complete) {
                $('.dashboard-charts-cryptoassets-filter .btn').prop('disabled', false);
            } else {
                $('.dashboard-charts-cryptoassets-filter .btn').prop('disabled', true);
            }
            if ($(this).hasClass('product-returns-date-start')) {
                $('input.product-returns-date-end').datepicker("option", "minDate", $(this).val());
            } else {
                $('input.product-returns-date-start').datepicker("option", "maxDate", $(this).val());
            }

        }
    }
);
$('.dashboard-charts-cryptoassets-filter .btn').on('click', function(){
    var link = $(this).data('targetUrl');
    var dateFrom = $('.dashboard-charts-cryptoassets-filter input.product-returns-date-start').val();
    var dateTo = $('.dashboard-charts-cryptoassets-filter input.product-returns-date-end').val();
    var btn = $(this);
    btn.prop('disabled', true);
    $.ajax({
        url: link,
        type: 'POST',
        cache: false,
        data: {dateFrom:dateFrom, dateTo:dateTo},
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {

            if (response.status == 'success') {
                /*
                response.data = {
                    conservative: '113.585',
                    classic: '375.283',
                    confident: '234.069'
                };
                */
                $.each(response.data, function(product, value) {
                    $('.dashboard-products-row-' + product + ' .dashboard-products-row-diff').html((1 * value < 0 ? '<span class="icon-color-arrow icon-color-arrow-down"></span>' : '<span class="icon-color-arrow icon-color-arrow-up"></span>+') + value);
                });
                btn.prop('disabled', false);
            } else {
                displayPopupMsg(response.error);
            }
        },
        complete: function () {
            btn.prop('disabled', false);
        }
    });
});

// Tooltip for Total Equity Histogram
var customTooltips = function (tooltip) {
    var tooltipEl = document.getElementById('total_histogram_tooltip');

    if (!tooltipEl) {
        tooltipEl = document.createElement('div');
        tooltipEl.id = 'total_histogram_tooltip';
        tooltipEl.className = 'charts-tooltip';
        this._chart.canvas.parentNode.appendChild(tooltipEl);
        tooltipPointer = document.createElement('div');
        tooltipPointer.className = 'charts-tooltip-pointer';
        tooltipEl.appendChild(tooltipPointer);
        tooltipWrapper = document.createElement('div');
        tooltipWrapper.className = 'charts-tooltip-wrapper';
        tooltipEl.appendChild(tooltipWrapper);
    }

    // Hide if no tooltip
    if (tooltip.opacity === 0) {
        tooltipEl.style.opacity = 0;
        return;
    }

    // Set caret Position
    tooltipEl.classList.remove('above', 'below', 'no-transform');
    if (tooltip.yAlign) {
        tooltipEl.classList.add(tooltip.yAlign);
    } else {
        tooltipEl.classList.add('no-transform');
    }

    function getBody(bodyItem) {
        return bodyItem.lines;
    }

    // Set Text
    if (tooltip.body) {
        var titleLines = tooltip.title || [];
        var bodyLines = tooltip.body.map(getBody);

        var innerHtml = '<div class="tooltip-title">';

        titleLines.forEach(function (title) {
            innerHtml += '<div class="tooltip-title-item">' + title + '</div>';
        });
        innerHtml += '</div><div class="tooltip-body">';

        bodyLines.forEach(function (body, i) {
            var bodyParts = ('' + body).split(':');
            innerHtml += '<div class="tooltip-body-item"><span style="background:' + tooltip.labelColors[i]['backgroundColor'] + '"></span>' + bodyParts[0] + ':<b>' + bodyParts[1] + '</b>' + '</div>';
        });
        innerHtml += '</div>';

        tooltipWrapper.innerHTML = innerHtml;
    }
    var positionY = this._chart.canvas.offsetTop;
    var positionX = this._chart.canvas.offsetLeft;

    var canvasWidth = this._chart.canvas.clientWidth;
    var canvasHeight = this._chart.canvas.clientHeight;

    // Display, position, and set styles for font
    tooltipEl.style.opacity = 1;
    tooltipEl.style.left = positionX + 'px';
    tooltipEl.style.top = positionY + 'px';
    tooltipEl.style.width = canvasWidth + 'px';
    tooltipEl.style.height = canvasHeight + 'px';
    tooltipPointer.style.left = tooltip.caretX + 'px';

    if (tooltip.caretX + tooltipWrapper.clientWidth / 2 + 30 < canvasWidth) {
        tooltipWrapper.style.left = (tooltip.caretX + tooltipWrapper.clientWidth / 2 + 30) + 'px';
    } else {
        tooltipWrapper.style.left = (tooltip.caretX - tooltipWrapper.clientWidth / 2 - 30) + 'px';
    }
};
var lineOptions = {
    responsive: true,
    scales: {
        xAxes: [{
            ticks: {
                maxTicksLimit: 6,
                beginAtZero: false
            },
            gridLines: {
                display: false
            }
        }],
        yAxes: [{
            ticks: {
                stepSize: 3000,
                suggestedMax: 15000,
                stacked: true,
                beginAtZero: false
            },
            gridLines: {
                display: true,
                zeroLineWidth: 0,
                color: 'rgba(0,0,0,.05)'
            },
            scaleLabel: {
                fontColor: '#FFF'
            }
        }]
    },
    tooltips: {
        enabled: false,
        mode: 'index',
        position: 'nearest',
        intersect: false,
        custom: customTooltips
    },
    title: {
        display: false
    },
    legend: {
        display: false
    },
    elements: {
        rectangle: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            borderWidth: 0
        },
        point: {
            radius: 2,
            hoverRadius: 4
        }
    },
    animation: {
        duration: 3000
    }
};

var totalHistogramWrap = document.querySelector("#total_histogram");
if(totalHistogramWrap) { var totalHistogram = new Chart(totalHistogramWrap, {
    type: 'line',
    data: {},
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            xAxes: [{
                ticks: {
                    maxTicksLimit: 6,
                    beginAtZero: false
                },
                gridLines: {
                    display: false
                }
            }],
            yAxes: [{
                ticks: {
                    stacked: true,
                    beginAtZero: false
                },
                gridLines: {
                    display: true,
                    zeroLineWidth: 0,
                    color: 'rgba(0,0,0,.05)'
                },
                scaleLabel: {
                    fontColor: '#FFF'
                }
            }]
        },
        tooltips: {
            custom: customTooltips
        },
        title: {
            display: false
        },
        legend: {
            display: false
        },
        elements: {
            rectangle: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                borderWidth: 0
            },
            point: {
                radius: 2,
                hoverRadius: 4
            }
        },
        animation: {
            duration: 3000
        }
    }
}); }

var totalCapWrap = document.querySelector("#total_cap");
if(totalCapWrap) {
    var totalCap = new Chart(totalCapWrap, {
        type: 'line',
        data: {},
        options: lineOptions
    });
}
var totalPieWrap = document.querySelector('#total_pie');
if(totalPieWrap) { var totalPie = new Chart(document.getElementById('total_pie'), {
    type: 'pie',
    data: {},
    options: {
        responsive: true,
        tooltips: {
            enabled: true,
            backgroundColor: 'rgba(255,255,255,0.94)',
            borderColor: 'rgba(0,0,0,0.15)',
            borderWidth: 1,
            titleFontColor: '#000',
            titleFontFamily: 'Montserrat',
            titleFontSize: 12,
            titleMarginBottom: 15,
            bodyFontColor: '#000',
            bodySpacing: 10,
            cornerRadius: 0,
            xPadding: 15,
            yPadding: 10
        },
        legend: {
            display: true,
            position: 'left'
        },
        title: false,
        elements: {
            arc: {
                borderWidth: 0
            }
        },
        maintainAspectRatio: true,
        // pieceLabel: {
        //     render: function (args) {
        //         return ' ' + args.label + ' ' + args.percentage + '% ';
        //     },
        //     precision: 0,
        //     fontColor: '#767676',
        //     fontSize: 16,
        //     fontStyle: '500',
        //     fontFamily: "'Montserrat', sans-serif",
        //     position: 'outside',
        //     overlap: true
        // },
        animation: {
            duration: 2000
        }
    }
}); }


$('.analytics-row-show').on('click', function() {

    var prntRow = $(this).closest('.analytics-row');
    var info = $(this).closest('.analytics-row').find('.analytics-row-more');

    if(!prntRow.hasClass('analytics-all-row')) {
        var cnvs = info.find('canvas');
        var cnvsID = cnvs.attr('id');
        if (!instrumentCharts[cnvsID]) {

            var cOptions = lineOptions;
            cOptions.maintainAspectRatio = false;
            cOptions.tooltips = {
                enabled: true,
                mode: 'index',
                position: 'nearest',
                intersect: false,
                custom: false,
                backgroundColor: 'rgba(255,255,255,0.94)',
                titleFontColor: '#000',
                titleFontFamily: 'Open Sans',
                titleFontSize: 14,
                titleMarginBottom: 15,
                bodyFontColor: '#000',
                cornerRadius: 0,
                xPadding: 15,
                yPadding: 10
            };
            cnvs.height = 200;
            instrumentCharts[cnvsID] = new Chart(cnvs, {
                type: 'line',
                data: [],
                options: cOptions
            });
        }
        updateData(instrumentCharts[cnvsID], datasetAnalytics[cnvsID]['m1']['labels'], datasetAnalytics[cnvsID]['m1']['datasets']);
    }
    setTimeout(function() {info.slideToggle(300);}, 300);
});

$('.analytics-row-more .histogramm-menu span').on('click', function() {
    var period = $(this).data('period');
    var chartID = $(this).closest('.analytics-row').find('canvas').attr('id');
    console.log(chartID+' - '+period);
    console.log(datasetAnalytics[chartID][period]['datasets']);
    updateData(instrumentCharts[chartID], datasetAnalytics[chartID][period]['labels'], datasetAnalytics[chartID][period]['datasets']);
});

function updateData(chart, label, datasets) {
    chart.data.labels = label;
    chart.data.datasets = datasets;
    chart.update();
}

var dashboardPage = (function(){
    return {
        totalPie: document.querySelector('#total_pie'),
        totalHistogram: document.querySelector('#total_histogram'),
        totalCap: document.querySelector('#total_cap')
    }
})();

window.addEventListener('load', function() {
    $('.histogramm-menu').each(function() {
        histogrammMenu(this);
    });
});

window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;

    if(totalHistogramWrap&&isVisible(totalHistogramWrap)) {
        totalHistogramWrap = false;
        updateData(totalHistogram, datasetAnalytics.totalHistogramm.d1.labels, datasetAnalytics.totalHistogramm.d1.datasets);
    }
    if(totalCapWrap&&isVisible(totalCapWrap)) {
        totalCapWrap = false;
        updateData(totalCap, datasetAnalytics.totalCap.d1.labels, datasetAnalytics.totalCap.d1.datasets);
    }
    if(totalPieWrap&&isVisible(totalPieWrap)) {
        totalPieWrap = false;
        updateData(totalPie, datasetAnalytics.productPie.conservative.labels, datasetAnalytics.productPie.conservative.datasets);
    }

});

// Show Product Prices by Period
$('.pricing-periods-item').on('click', function(){
    $('.pricing-periods-item').removeClass('active');
    $(this).addClass('active');
    var indicatorOffset = this.offsetLeft;
    $('.pricing-periods-indicator').css('transform', 'translateX('+indicatorOffset+'px)');
    var periodPrices = datasetAnalytics.productPrices[$(this).data('period')];
    $.each(periodPrices, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-value').html('$ '+value);
    });
});


// Show Product Pie
$('.dashboard-products-row').on('click', function() {
    var productName = $(this).data('product');
    $('.dashboard-charts-cryptoassets-body-col_2 h5').text(productName+' trust');
    updateData(totalPie, datasetAnalytics.productPie[productName]['labels'], datasetAnalytics.productPie[productName]['datasets']);
});
$('.analytics-total_histogram .histogram-menu span').on('click', function() {
    var period = $(this).data('period');
    updateData(totalHistogram, datasetAnalytics.totalHistogram[period]['labels'], datasetAnalytics.totalHistogram[period]['datasets']);
});
// Show Product Returns by Period
$('#product-returns').on('change', function() {
    var period = $(this).val();

    var periodReturns = datasetAnalytics.productReturns[period];
    $.each(periodReturns, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-diff').html((1*value<0?'<span class="icon-color-arrow icon-color-arrow-down"></span>':'<span class="icon-color-arrow icon-color-arrow-up"></span>+')+value);
    });
});


// Add Account Info
$('.popup-addaccount form').on('submit', function(e) {
    e.preventDefault();
    var btn = $('.popup-addaccount .btn');
    var aName = $('#accountname').val();
    var aAddress = $('#accountaddress').val();

    $('.popup-shadow div').show();
    $('.popup').removeClass('visible');
    $('.popup-shadow').fadeIn(300);

    $.ajax({
        url: '/',
        type: 'POST',
        cache: false,
        data: {name:aName, address:aAddress},
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success') {
                btn.prop('disabled', false);
                $('.popup.popup-bitfinex h4').text(aName);
                $('.popup.popup-bitfinex .popupbitfinexform-wrapper').html(response.data);
                $('.popup-bitfinex input[type=checkbox]').on({change: function () { $(this).closest('label').toggleClass('checked'); } });
                $('.popup-shadow div').hide();
                $('.popup.popup-bitfinex').addClass('visible');
            } else {
                displayPopupMsg(response.error);
            }
        },
        complete: function () {
            btn.prop('disabled', false);
        }
    });
});
$('.popup-bitfinex').on('submit', 'form', function() {
    var formNewAccountData = new FormData(this);
    var btn = $(this).find('.btn');
    $.ajax({
        url: "/set-new-account",
        type: 'POST',
        data: formNewAccountData,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == 'success') {
                displayPopupMsg('Account added!');
                setTimeout(function() { location.reload(); }, 2500);
            } else {
                displayPopupMsg(response.error);
            }
        },
        complete: function() {
            btn.prop('disabled', false);
        }
    });
    return false;
});


// Choose Product
$('.dashboard-products-row-qty input').on('blur', function(e){
    var qty = parseInt($(this).val());
    if(isNaN(qty)) {
        $(this).val(0);
    }
});
$('.dashboard-products-row-btn .btn').on('click', function(e){
    e.preventDefault();
    var period = $('.pricing-periods-item.active').data('period');
    var prnt = $(this).closest('.dashboard-products-row');
    var qty = prnt.find('.dashboard-products-row-qty input').val();
    var cost = prnt.find('.dashboard-products-row-value').text();
        cost = cost.replace(/[\s\$,]/g, '');
    var product = $(this).data('product');
    var link = $(this).data('targetUrl');

    if(qty>0) {
        var popupShadow = $('.popup-shadow');
        $('.popup-product_buy-info-name').html(prnt.find('.dashboard-products-row-name').html());
        $('.popup-product_buy-cost').text('$ '+cost);
        $('.popup-product_buy-qty input').val(qty);
        $('.popup-product_buy-total').text('$ '+(qty*cost));

        $('.popup-product_buy-info button').data({link:link, qty:qty, name:product, period: period});

        $('.popup').removeClass('visible');
        popupShadow.fadeIn(300, function() {
            $('.popup.popup-product_buy').addClass('visible');
        });

    } else {
        displayPopupMsg('<h4>Enter product quantity</h4>');
    }
});
$('.popup-product_buy-info button').on('click', function() {
    var btn = $(this);
    btn.prop('disabled', true);
    /*
    data = {
        name: "classic", // conservative, classic or confident
        period: "m1", // m12, m6, m3, m1
        qty: "12" // 1,2,3....
    }
    */
    $.ajax({
        url: btn.data('link'),
        type: 'POST',
        cache: false,
        data: {qty:btn.data('qty'), name:btn.data('name'), period: btn.data('period')},
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {

            if (response.status == 'success') {
                displayPopupMsg('<h4>Product Added!</h4>');
                setTimeout(function() {
                    window.location.href='/myproducts'
                }, 2000);
                btn.prop('disabled', false);
            } else {
                displayPopupMsg(response.error);
            }
        },
        complete: function () {
            btn.prop('disabled', false);
        }
    });
});