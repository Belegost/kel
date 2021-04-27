supportBlock.section = false;

// var customTooltips = function (tooltip) {
//     var tooltipEl = document.getElementById('total_histogram_tooltip');
//
//     if (!tooltipEl) {
//         tooltipEl = document.createElement('div');
//         tooltipEl.id = 'total_histogram_tooltip';
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
var graphColors = [
    '#002737',
    '#00334b',
    '#003f5f',
    '#005273',
    '#015d87',
    '#01719b',
    '#0181af',
    '#0190c3',
    '#1fb1cd',
    '#1fbbd7',
    '#72c7eb',
    '#75dbff'
];
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
        mode: 'x',
        position: 'nearest',
        intersect: false,
        backgroundColor: 'rgba(255,255,255,0.94)',
        borderColor: 'rgba(0,0,0,0.15)',
        borderWidth: 1,
        titleFontColor: '#000',
        titleFontFamily: 'Open Sans',
        titleFontSize: 14,
        titleMarginBottom: 15,
        bodyFontColor: '#000',
        cornerRadius: 0,
        xPadding: 15,
        yPadding: 10
        //custom: customTooltips
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
            radius: 4,
            hitRadius: 30,
            hoverRadius: 6
        }
    },
    animation: {
        duration: 3000
    }
};
var pieOptions = {
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
};
var barOptions = {
    barThickness: 10,
    maxBarThickness: 10,
    responsive: true,
    tooltips: {
        enabled: true
    },
    scales: {
        xAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    },
    legend: {
        display: false
    },
    elements: {
    },
    maintainAspectRatio: false,
    barLabel: {
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
};
var productCharts = {};


$(".crypto-overview-gallery.owl-carousel").owlCarousel({
    margin:10,
    autoWidth:true
});
$('.crypto-overview-gallery-item').hover(function(){
    $(this).find('.hover').addClass('flip');
},function(){
    $(this).find('.hover').removeClass('flip');
});
$('.cabinet-product .cabinet-product-show').on('click', function() {
    var wrap = $(this).closest('.cabinet-product');
    var pID = wrap.data('productid');
    if(!productCharts[pID]) {
        createCharts(pID);
        console.log(pID);
    }
    wrap.toggleClass('active');
    setTimeout(function() {
        wrap.find('.cabinet-product-body').slideToggle(600);
    }, 300);
});

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

/*
function updateData(chart, label, datasets) {
    chart.data.labels = label;
    chart.data.datasets = datasets;
    chart.update();
}
*/
function createCharts(pID) {
    var dataPie = integrityEnv.productList[pID].productCharts.pie;
    var dataBar = integrityEnv.productList[pID].productCharts.horizontalBar;
    var dataLine = integrityEnv.productList[pID].productCharts.line;
    productCharts[pID] = {};
    productCharts[pID]['pie'] = new Chart($('#product-' + pID + ' .cabinet-product-body-pie canvas'), {
        type: 'pie',
        data: {
            datasets: [{
                data: dataPie.data,
                backgroundColor: graphColors.slice(0, dataPie.labels.length)
            }],
            labels: dataPie.labels
        },
        options: pieOptions
    });
    productCharts[pID]['bar'] = new Chart($('#product-' + pID + ' .cabinet-bar-wrapper canvas'), {
        type: 'horizontalBar',
        data: {
            datasets: [{
                data: dataBar.data,
                backgroundColor: graphColors.slice(0, dataPie.labels.length)
            }],
            labels: dataBar.labels
        },
        options: barOptions
    });
    productCharts[pID]['line'] = new Chart($('#product-' + pID + ' .cabinet-product-body-line canvas'), {
        type: 'line',
        data: {
            datasets: [{
                data: dataLine.datasets[0]['data'].map(function(data) {
                    return data.toFixed(6);
                }),
                label: dataLine.datasets[0]['label'],
                pointBorderWidth: 0,
                pointRadius: 1,
                pointBackgroundColor: 'rgba(1,149,232,1)',
                pointBorderColor: 'rgba(255,255,255,0)',
                backgroundColor: '#0195E8',
                borderWidth: 2,
                borderColor: '#0195E8',
                lineTension: 0,
                fill: false,
            }],
            labels: dataLine.labels
        },
        options: lineOptions
    });

    console.log(productCharts);
}
var myProducts = (function(){
    return {
        bigDigitsCabinetFunds: document.querySelectorAll('.cabinet-product-title-digits-funds .big-digits'),
        bigDigitsCabinetBtc: document.querySelectorAll('.cabinet-product-title-digits-btc .big-digits'),
        bigDigitsCabinetPercent: document.querySelectorAll('.cabinet-product-title-digits-percent .big-digits')
    }
})();


window.addEventListener('load', function() {
    //histogrammMenu(document.querySelector('.histogramm-menu'));

    $.each(myProducts.bigDigitsCabinetFunds, function(key,item){
        if(myProducts.bigDigitsCabinetFunds[key]&&isVisible(this)) {
            incrementDigits.init(this, {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 1500});
            myProducts.bigDigitsCabinetFunds[key] = false;
        }
    });
    $.each(myProducts.bigDigitsCabinetBtc, function(key,item){
        if(myProducts.bigDigitsCabinetBtc[key]&&isVisible(this)) {
            incrementDigits.init(this, {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 1500});
            myProducts.bigDigitsCabinetBtc[key] = false;
        }
    });
    $.each(myProducts.bigDigitsCabinetPercent, function(key,item){
        if(myProducts.bigDigitsCabinetPercent[key]&&isVisible(this)) {
            incrementDigits.init(this, {decimalPlaces:2,decimalPoint:".",duration: 1500});
            myProducts.bigDigitsCabinetPercent[key] = false;
        }
    });
});

window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;

    $.each(myProducts.bigDigitsCabinetFunds, function(key,item){
        if(myProducts.bigDigitsCabinetFunds[key]&&isVisible(this)) {
            incrementDigits.init(this, {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 1500});
            myProducts.bigDigitsCabinetFunds[key] = false;
        }
    });
    $.each(myProducts.bigDigitsCabinetBtc, function(key,item){
        if(myProducts.bigDigitsCabinetBtc[key]&&isVisible(this)) {
            incrementDigits.init(this, {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 1500});
            myProducts.bigDigitsCabinetBtc[key] = false;
        }
    });
    $.each(myProducts.bigDigitsCabinetPercent, function(key,item){
        if(myProducts.bigDigitsCabinetPercent[key]&&isVisible(this)) {
            incrementDigits.init(this, {decimalPlaces:2,decimalPoint:".",duration: 1500});
            myProducts.bigDigitsCabinetPercent[key] = false;
        }
    });

});