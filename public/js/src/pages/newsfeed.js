var page = 0;
$('.load-more-news').on('click', function(e){
    e.preventDefault();
    var link = $(this).data('targetUrl');
    var btn = $(this);
    btn.prop('disabled', true);

    $.ajax({
        url: link,
        type: 'POST',
        cache: false,
        data: {currentpage:page},
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {

            if (response.status == 'success') {
                page++;
                btn.before(response.data);
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
var totalHistogrammTooltips = function (tooltip) {
    var tooltipEl = document.getElementById('total_histogramm_tooltip');

    if (!tooltipEl) {
        tooltipEl = document.createElement('div');
        tooltipEl.id = 'total_histogramm_tooltip';
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

var totalHistogramm = new Chart(totalHistogrammWrap, {
    type: 'line',
    data: {},
    options: {
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
            custom: totalHistogrammTooltips
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
                return ' ' + args.label + ' ' + args.percentage + '% ';
            },
            precision: 0,
            fontColor: '#767676',
            fontSize: 16,
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
    histogrammMenu(document.querySelector('.histogramm-menu'));

    // Set Product Prices for 6 months
    $.each(datasetDashboard.productPrices.m6, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-value').html('$ '+value);
    });
    // Set Product Returns for all time
    $.each(datasetDashboard.productReturns.all, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-diff').html((1*value<0?'<span class="icon-color-arrow icon-color-arrow-down"></span>':'<span class="icon-color-arrow icon-color-arrow-up"></span>+')+value);
    });
});

window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;

    if(dashboardPage.bigDigitsTotalHistogrammBtc&&isVisible(dashboardPage.bigDigitsTotalHistogrammBtc)) {
        incrementDigits.init(".dashboard-charts-total_histogramm-btc", {decimalPlaces:6,decimalPoint:".",duration: 1500});
    }
    if(dashboardPage.bigDigitsNoninvestedFounds&&isVisible(dashboardPage.bigDigitsNoninvestedFounds)) {
        incrementDigits.init(".dashboard-charts-noninvested_founds-body .big-digits", {thousandSeparator: ",",decimalPlaces:2,decimalPoint:".",duration: 1500});
    }
    if(dashboardPage.bigDigitsTotalinvestedFounds&&isVisible(dashboardPage.bigDigitsTotalinvestedFounds)) {
        incrementDigits.init(".dashboard-charts-totalinvested_founds-body .big-digits", {decimalPlaces:6,decimalPoint:".",duration: 1500});
    }

    if(dashboardPage.totalHistogramm&&isVisible(dashboardPage.totalHistogramm)) {
        dashboardPage.totalHistogramm = false;
        updateData(totalHistogramm, datasetDashboard.totalHistogramm.d1.labels, datasetDashboard.totalHistogramm.d1.datasets);
    }
    if(dashboardPage.cryptoassetsTotalPie&&isVisible(dashboardPage.cryptoassetsTotalPie)) {
        dashboardPage.cryptoassetsTotalPie = false;
        updateData(totalPie, datasetDashboard.productPie.conservative.labels, datasetDashboard.productPie.conservative.datasets);
    }

});

// Show Product Prices by Period
$('.pricing-periods-item').on('click', function(){
    $('.pricing-periods-item').removeClass('active');
    $(this).addClass('active');
    var indicatorOffset = this.offsetLeft;
    $('.pricing-periods-indicator').css('transform', 'translateX('+indicatorOffset+'px)');
    var periodPrices = datasetDashboard.productPrices[$(this).data('period')];
    $.each(periodPrices, function(product, value) {
        $('.dashboard-products-row-'+product+' .dashboard-products-row-value').html('$ '+value);
    });
});


// Show Product Pie
$('.dashboard-products-row').on('click', function() {
    var productName = $(this).data('product');
    $('.dashboard-charts-cryptoassets-body-col_2 h5').text(productName+' trust');
    updateData(totalPie, datasetDashboard.productPie[productName]['labels'], datasetDashboard.productPie[productName]['datasets']);
});
$('.histogramm-menu span').on('click', function() {
    var period = $(this).data('period');
    updateData(totalHistogramm, datasetDashboard.totalHistogramm[period]['labels'], datasetDashboard.totalHistogramm[period]['datasets']);
});
// Show Product Returns by Period
$('#product-returns').on('change', function() {
    var period = $(this).val();

    var periodReturns = datasetDashboard.productReturns[period];
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
