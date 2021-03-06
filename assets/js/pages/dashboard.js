var graphColors = [
    '#005f89',
    '#006c9b',
    '#107dac',
    '#189ad3',
    '#34aedb',
    '#71c7ec',
    '#007489',
    '#00839b',
    '#1094ac',
    '#18b5d3',
    '#34c5db',
    '#71d9ec',
    '#268900',
    '#2b9b00',
    '#3cac10',
    '#4cd318',
    '#67db34',
    '#94ec71',
    '#890087',
    '#9b009a',
    '#ac10ab',
    '#d318d1',
    '#d634db',
    '#ec71eb',
    '#89000b',
    '#9b000c',
    '#ac101d',
    '#d31827',
    '#db3448',
    '#ec717b',
    '#895200',
    '#9b5e00',
    '#ac6e10',
    '#d38918',
    '#db9334',
    '#ecbb71',
    '#fce5cd'
];
var lineDataDataset = {
    "pointBorderWidth": 0,
    "pointRadius": 1,
    "pointBorderColor": "rgba(255,255,255,0)",
    "borderWidth": 2,
    "lineTension": 0,
    "fill": false
};
var lineDataDatasetColors = [
    {
        pointBackgroundColor:  "rgba(1,149,232,1)",
        backgroundColor: "#0195E8",
        borderColor: "#0195E8"
    },
    {
        pointBackgroundColor:  "rgba(8,233,56,1)",
        backgroundColor: "#08e938",
        borderColor: "#08e938"
    },
    {
        pointBackgroundColor:  "rgba(232,43,7,1)",
        backgroundColor: "#e82b07",
        borderColor: "#e82b07"
    }
];
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

var totalHistogrammWrap = document.getElementById("total_histogramm");
// Tooltip for Total Equity Histogram
// var totalHistogrammTooltips = function (tooltip) {
//     var tooltipEl = document.getElementById('total_histogramm_tooltip');
//
//     if (!tooltipEl) {
//         tooltipEl = document.createElement('div');
//         tooltipEl.id = 'total_histogramm_tooltip';
//         tooltipEl.className = 'charts-tooltip';
//         this._chart.canvas.parentNode.appendChild(tooltipEl);
//         tooltipPointer = document.createElement('div');
//         tooltipPointer.className = 'charts-tooltip-pointer';
//         tooltipEl.appendChild(tooltipPointer);
//         tooltipWrapper = document.createElement('div');
//         tooltipWrapper.className = 'charts-tooltip-wrapper';
//         tooltipEl.appendChild(tooltipWrapper);
//     }
//
//     // Hide if no tooltip
//     if (tooltip.opacity === 0) {
//         tooltipEl.style.opacity = 0;
//         return;
//     }
//
//     // Set caret Position
//     tooltipEl.classList.remove('above', 'below', 'no-transform');
//     if (tooltip.yAlign) {
//         tooltipEl.classList.add(tooltip.yAlign);
//     } else {
//         tooltipEl.classList.add('no-transform');
//     }
//
//     function getBody(bodyItem) {
//         return bodyItem.lines;
//     }
//
//     // Set Text
//     if (tooltip.body) {
//         var titleLines = tooltip.title || [];
//         var bodyLines = tooltip.body.map(getBody);
//
//         var innerHtml = '<div class="tooltip-title">';
//
//         titleLines.forEach(function (title) {
//             innerHtml += '<div class="tooltip-title-item">' + title + '</div>';
//         });
//         innerHtml += '</div><div class="tooltip-body">';
//
//         bodyLines.forEach(function (body, i) {
//             var bodyParts = ('' + body).split(':');
//             innerHtml += '<div class="tooltip-body-item"><span style="background:' + tooltip.labelColors[i]['backgroundColor'] + '"></span>' + bodyParts[0] + ':<b>' + bodyParts[1] + '</b>' + '</div>';
//         });
//         innerHtml += '</div>';
//
//         tooltipWrapper.innerHTML = innerHtml;
//     }
//     var positionY = this._chart.canvas.offsetTop;
//     var positionX = this._chart.canvas.offsetLeft;
//
//     var canvasWidth = this._chart.canvas.clientWidth;
//     var canvasHeight = this._chart.canvas.clientHeight;
//
//     // Display, position, and set styles for font
//     tooltipEl.style.opacity = 1;
//     tooltipEl.style.left = positionX + 'px';
//     tooltipEl.style.top = positionY + 'px';
//     tooltipEl.style.width = canvasWidth + 'px';
//     tooltipEl.style.height = canvasHeight + 'px';
//     tooltipPointer.style.left = tooltip.caretX + 'px';
//
//     if (tooltip.caretX + tooltipWrapper.clientWidth / 2 + 30 < canvasWidth) {
//         tooltipWrapper.style.left = (tooltip.caretX + tooltipWrapper.clientWidth / 2 + 30) + 'px';
//     } else {
//         tooltipWrapper.style.left = (tooltip.caretX - tooltipWrapper.clientWidth / 2 - 30) + 'px';
//     }
// };

var totalHistogramm = new Chart(totalHistogrammWrap, {
    type: 'line',
    data: {},
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            xAxes: [{
                ticks: {
                    maxTicksLimit: 6,
                    beginAtZero: false,
                    maxRotation: 0,
                    minRotation: 0
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
            mode: 'index',
            position: 'average',
            intersect: false,
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
});
var totalPie = new Chart(document.getElementById('dashboard-cryptoassets-total'), {
    type: 'pie',
    data: {},
    options: {
        responsive: true,
        tooltips: {
            enabled: false
        },
        legend: {
            display: false
        },
        elements: {
            arc: {
                borderWidth: 0
            }
        },
        maintainAspectRatio: false,
        pieceLabel: {
            render: function (args) {
                return ' ' + args.label + ' ' /* + args.percentage + '% '*/;
            },
            precision: 0,
            fontColor: '#767676',
            fontSize: 11,
            fontStyle: '500',
            fontFamily: "'Montserrat', sans-serif",
            position: 'outside',
            overlap: true
        },
        animation: {
            duration: 2000
        }
    }
});

function updateData(chart, label, datasets) {
    chart.data.labels = label;
    chart.data.datasets = datasets;
    chart.update();
}

var dashboardPage = (function(){
    return {
        bigDigitsTotalHistogrammBtc:document.querySelector('.dashboard-charts-total_histogramm-btc'),
        bigDigitsNoninvestedFounds: document.querySelector('.dashboard-charts-noninvested_founds-body .big-digits'),
        bigDigitsTotalinvestedFounds: document.querySelector('.dashboard-charts-totalinvested_founds-body .big-digits'),
        cryptoassetsTotalPie: document.querySelector('#dashboard-cryptoassets-total'),
        totalHistogramm: document.querySelector('#total_histogramm')
    }
})();

window.addEventListener('load', function() {
    //histogrammMenu(document.querySelector('.histogramm-menu'));

    // Set Product Prices for 6 months
    $.each(integrityEnv.productPrices.m6, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-value').html('$ '+value);
    });
    // Set Product Returns for all time
    $.each(integrityEnv.productReturns.m6, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-diff').html((1*value<0?'<span class="icon-color-arrow icon-color-arrow-down"></span>':'<span class="icon-color-arrow icon-color-arrow-up"></span>+')+value);
    });
});

window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;

    // if(dashboardPage.bigDigitsTotalHistogrammBtc&&isVisible(dashboardPage.bigDigitsTotalHistogrammBtc)) {
    //     incrementDigits.init(".dashboard-charts-total_histogramm-btc", {decimalPlaces:8,decimalPoint:".",duration: 1500});
    //     dashboardPage.bigDigitsTotalHistogrammBtc = false;
    // }
    if(dashboardPage.bigDigitsNoninvestedFounds&&isVisible(dashboardPage.bigDigitsNoninvestedFounds)) {
        if(integrityEnv.isCurrentRateUSD) {
            $('.dashboard-charts-noninvested_founds-body .big-digits').addClass('big-digits_inDollars');
        }
        incrementDigits.init(".dashboard-charts-noninvested_founds-body .big-digits", {thousandSeparator: "",decimalPlaces:2,decimalPoint:".",duration: 1500});
    }
    if(dashboardPage.bigDigitsTotalinvestedFounds&&isVisible(dashboardPage.bigDigitsTotalinvestedFounds)) {
        incrementDigits.init(".dashboard-charts-totalinvested_founds-body .big-digits", {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 1500});
    }

    if(dashboardPage.totalHistogramm&&isVisible(dashboardPage.totalHistogramm)) {
        dashboardPage.totalHistogramm = false;

        // Update Line Chart
        var lineData = integrityEnv.totalHistogramm.m6;
        $.each(lineData.datasets, function(key, value) {
            lineData.datasets[key] = Object.assign(lineData.datasets[key], lineDataDataset, lineDataDatasetColors[key]);
        });
        updateData(totalHistogramm, lineData.labels, lineData.datasets);
    }
    if(dashboardPage.cryptoassetsTotalPie&&isVisible(dashboardPage.cryptoassetsTotalPie)) {
        dashboardPage.cryptoassetsTotalPie = false;
        var conservativeData = integrityEnv.productPie.conservative;
        conservativeData['datasets'][0]['backgroundColor'] = graphColors.slice(0, conservativeData.labels.length );
        updateData(totalPie, conservativeData.labels, conservativeData.datasets);
    }

});

// Show Product Prices by Period
$('.pricing-periods-item').on('click', function(){
    $('.pricing-periods-item').removeClass('active');
    $(this).addClass('active');
    var indicatorOffset = this.offsetLeft;
    $('.pricing-periods-indicator').css('transform', 'translateX('+indicatorOffset+'px)');

    var period = $(this).data('period');
    var periodName = '';
    switch (period) {
        case 'm12':
            periodName = '1 year';
            break;
        case 'm6':
            periodName = '6 months';
            break;
        case 'm3':
            periodName = '3 months';
            break;
        case 'm1':
            periodName = '1 month';
            break;
    }
    var periodPrices = integrityEnv.productPrices[period];

    // Set Product Periods&Prices
    $.each(periodPrices, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-name small').html(periodName);
        $('.dashboard-products-row-'+product+' .dashboard-products-row-value').html('$ '+value);
    });
    // Set Product Returns
    $.each(integrityEnv.productReturns[period], function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-diff').html((1*value<0?'<span class="icon-color-arrow icon-color-arrow-down"></span>':'<span class="icon-color-arrow icon-color-arrow-up"></span>+')+value);
    });

    // Update Line Chart
    var lineData = integrityEnv.totalHistogramm[period];
    $.each(lineData.datasets, function(key, value) {
        lineData.datasets[key] = Object.assign(lineData.datasets[key], lineDataDataset, lineDataDatasetColors[key]);
    });
    updateData(totalHistogramm, lineData.labels, lineData.datasets);
});


// Show Product Pie
$('.dashboard-products-row').on('click', function() {
    var productName = $(this).data('product');
    $('.dashboard-charts-cryptoassets-body-col_2 h5').text(productName+' trust');

    var pieData = integrityEnv.productPie[productName];
    pieData['datasets'][0]['backgroundColor'] = graphColors.slice(0, pieData.labels.length );
    updateData(totalPie, pieData.labels, pieData.datasets);
});
$('.histogramm-menu span').on('click', function() {
    var period = $(this).data('period');
    updateData(totalHistogramm, integrityEnv.totalHistogramm[period]['labels'], integrityEnv.totalHistogramm[period]['datasets']);
});
// Show Product Returns by Period
$('#product-returns').on('change', function() {
    var period = $(this).val();

    var periodReturns = integrityEnv.productReturns[period];
    $.each(periodReturns, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-diff').html((1*value<0?'<span class="icon-color-arrow icon-color-arrow-down"></span>':'<span class="icon-color-arrow icon-color-arrow-up"></span>+')+value);
    });
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
    var qty = 1 /*prnt.find('.dashboard-products-row-qty input').val()*/;
    var cost = prnt.find('.dashboard-products-row-value').text();
        cost = cost.replace(/[\s\$,]/g, '');
    var product = $(this).data('product');

    if(qty==0) {
        qty = 1;
    }
    var popupShadow = $('.popup-shadow');
    $('.popup-product_buy-info-name').html(prnt.find('.dashboard-products-row-name').html());
    $('.popup-product_buy-cost').text('$ '+cost);
    $('.popup-product_buy-qty input').val(qty);
    $('.popup-product_buy-total').text('$ '+(qty*cost));

    popupBuyBtn = {qty:qty, name:product, period: period};

    $('.popup').removeClass('visible');
    popupShadow.fadeIn(300, function() {
        $('.popup.popup-product_buy').addClass('visible');
    });
});
