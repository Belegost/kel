supportBlock.section = false;
var chartsPeriod = 'm1';
var ticksLimit = (windowWidth>1139?15:10);
var charts = {};
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
var lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        xAxes: [{
            ticks: {
                maxTicksLimit: ticksLimit,
                maxRotation: 0,
                minRotation: 0,
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
        mode: 'nearest',
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
};
var smallLineOptions = {
    scales: {
        xAxes: [{
            weight: 0,
            display: false,
            ticks: {
                display: true,
                backdropPaddingX: 0
            },
            gridLines: {
                display: false,
                color: 'rgba(0,0,0,0.05)',
                lineWidth: 1,
                drawBorder: false
            }
        }],
            yAxes: [{
            ticks: {
                display: false,
                backdropPaddingY: 0
            },
            gridLines: {
                display: true,
                color: 'rgba(0,0,0,0.05)',
                lineWidth: 1,
                drawBorder: false
            }
        }]
    },
    tooltips: {
        enabled: false
    },
    title: {
        display: false
    },
    legend: {
        display: false
    },
    animation: {
        duration: 2000
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

var totalHistogramm = new Chart(document.getElementById("total_histogramm"), {
    type: 'line',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    maxTicksLimit: ticksLimit-4,
                    maxRotation: 0,
                    minRotation: 0,
                    beginAtZero: false
                },
                gridLines: {
                    display: false
                }
            }],
            yAxes: [{
                ticks: {
                    display: false,
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
            titleFontFamily: 'Open Sans',
            titleFontSize: 14,
            titleMarginBottom: 15,
            bodyFontColor: '#000',
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
var totalPie = new Chart(document.getElementById("portfolio-product-pie"), {
    type: 'pie',
    data: {
        labels: ["No Items"],
        datasets: [{
            data: [100],
            backgroundColor: ["#f1f1f1"]
        }]
    },
    options: pieOptions
});
function updateSmallCharts() {
    $('.portfolio-row').each(function(e) {
        var row = this;
        var rid = this.id;
        var chartData = integrityEnv.instruments[rid].chart;
        if(!charts[rid]) charts[rid] = {};
        if(!charts[rid]['small']) {
            charts[rid]['small'] = new Chart($(row).find('.portfolio-row-graph canvas'), {
                type: 'line',
                options: smallLineOptions,
                data: {
                    datasets: [{
                        pointBorderWidth: 0,
                        pointRadius: 1,
                        pointBackgroundColor: 'rgba(8,233,56,1)',
                        borderWidth: 1,
                        lineTension: 0,
                        label: "",
                        fill: true,
                        backgroundColor: 'rgba(8,233,56,0.03)',
                        borderColor: 'rgba(8,233,56,1)',
                        data: chartData[chartsPeriod].data
                    }],
                    labels: chartData[chartsPeriod].labels
                }
            });
        } else {
            charts[rid]['small'].data.labels = chartData[chartsPeriod].labels;
            charts[rid]['small'].data.datasets.data = chartData[chartsPeriod].data;
            charts[rid]['small'].update();

            // charts[rid]['small'].data.labels = chartData[chartsPeriod].labels;
            // charts[rid]['small'].data.datasets.data = chartData[chartsPeriod].data;
            // charts[rid]['small'].update();
        }
    });
}
function updateMainCharts() {
    var newData = mainChartsData();
    totalHistogramm.data.labels = newData.line.labels;
    totalHistogramm.data.datasets = newData.line.datasets;
    totalHistogramm.update();

    totalPie.data.labels = newData.pie.labels;
    totalPie.data.datasets = newData.pie.datasets;
    totalPie.update();
}
function mainChartsData() {
    var allinstruments = integrityEnv.instruments;
    var lineDatasets = [];
    var lineLabels = [];
    var pieDataset = [];
    var pieBG = [];
    var pieLabels = [];
    var colorIndex = 0;
    $.each(changePercents.getInstruments(), function(code, value){
        lineDataset = {
            label: ' '+allinstruments[code].fullname,
            pointBorderWidth: 0,
            pointRadius: 1,
            pointBackgroundColor: graphColors[colorIndex],
            pointBorderColor: 'rgba(255,255,255,0)',
            backgroundColor: graphColors[colorIndex],
            borderWidth: 2,
            borderColor: graphColors[colorIndex],
            lineTension: 0,
            fill: false,
            data: allinstruments[code].chart[chartsPeriod].data
        };
        lineLabels = allinstruments[code].chart[chartsPeriod].labels.length>lineLabels.length?allinstruments[code].chart[chartsPeriod].labels:lineLabels;
        lineDatasets.push(lineDataset);

        pieLabels.push(code);
        pieDataset.push(value);
        pieBG.push(graphColors[colorIndex]);

        colorIndex++;
    });
    if(changePercents.totalPercent()<100) {
        pieLabels.push('No Items');
        pieDataset.push(100-changePercents.totalPercent());
        pieBG.push('#f1f1f1');
    }
    return {
        line: {
            datasets:lineDatasets,
            labels:lineLabels
        },
        pie: {
            datasets:[{
                data: pieDataset,
                backgroundColor: pieBG
            }],
            labels:pieLabels
        }
    }
}
function AddInstrument(code) {
    if(integrityEnv.instruments[code]) {

        var instrument = integrityEnv.instruments[code];

        var type = 1;
        var rowTitle = '';
        switch (code) {
            case 'conservative':
                rowTitle = '<div class="portfolio-row-product_title"><img src="/images/special_service1.jpg"><h6>'+code+' trust</h6><small>TOP-5 cryptocurrencies and classic money market fixed income instruments</small></div>';
                break;
            case 'classic':
                rowTitle = '<div class="portfolio-row-product_title"><img src="/images/special_service2.jpg"><h6>'+code+' trust</h6><small>TOP-10 cryptocurrencies and most prospect ICO tokens</small></div>';
                break;
            case 'confident':
                rowTitle = '<div class="portfolio-row-product_title"><img src="/images/special_service3.jpg"><h6>'+code+' trust</h6><small>Risky ICO of utility tokens and top-10 cryptocurrencies</small></div>';
                break;
            default:
                type=0;
                rowTitle = '<div class="portfolio-row-title"><h6>'+code+'/USD</h6><small>'+instrument.fullname+'</small></div><div class="portfolio-row-value">'+instrument.value+'</div>';
        }
        var row = $('<div class="portfolio-row" id="'+code+'">' + rowTitle +
                '<div class="portfolio-row-diff"><span class="icon-color-arrow icon-color-arrow-'+(1*instrument.diff<0?'down':'up')+'"></span>'+instrument.diff+'</div>' +
                '<div class="portfolio-row-graph"><canvas></canvas></div>' +
                '<div class="portfolio-row-percent">' +
                    '<div class="portfolio-row-percent-edit"><input type="text" placeholder="Enter %" /><button>Ok</button>' +
                        '<div class="portfolio-row-percent-error"></div>' +
                    '</div>' +
                    '<div class="portfolio-row-show"><span></span><span></span></div>' +
                    '<span>0%</span>' +
                '</div>' +
                '<div class="portfolio-row-more"><canvas></canvas></div>' +
            '</div>');
        if(type) {
            $('.portfolio-row-ready_products-add').before(row);
        } else {
            $('.portfolio-list').append(row);
        }
        var chartData = integrityEnv.instruments[code].chart;
        charts[code] = {};
        charts[code]['small'] = new Chart(row.find('.portfolio-row-graph canvas'), {
            type: 'line',
            options: smallLineOptions,
            data: {
                datasets: [{
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    borderWidth: 1,
                    lineTension: 0,
                    label: "",
                    fill: true,
                    backgroundColor: 'rgba(8,233,56,0.03)',
                    borderColor: 'rgba(8,233,56,1)',
                    data: chartData[chartsPeriod].data
                }],
                labels: chartData[chartsPeriod].labels
            }
        });
    }
}
var portfolioPage = (function(){
    return {
        bigDigitsPortfolioFree: document.querySelector('.portfolio-product-title-digits-free .big-digits'),
        bigDigitsPortfolioFunds: document.querySelector('.portfolio-product-title-digits-funds .big-digits'),
        bigDigitsPortfolioCreate: document.querySelector('.portfolio-product-title-digits-create .big-digits'),
        portfolioPie: document.querySelector('#portfolio-product-pie'),
        totalHistogramm: document.querySelector('#total_histogramm')
    }
})();

window.addEventListener('load', function() {
    var histMenu = document.querySelector('.histogramm-menu');
    var histMenuCurr = histMenu.querySelector('.current');
    var activeBorder = histMenu.querySelector('.active-border');
        activeBorder.style.marginLeft = histMenuCurr.offsetLeft+'px';
        activeBorder.style.width = histMenuCurr.offsetWidth+'px';
    histogrammMenu(histMenu);

    $('.portfolio-row').each(function(e) {
        var row = this;
        var rid = this.id;
        var chartData = integrityEnv.instruments[rid].chart;
        if(!charts[rid]) {
            charts[rid] = {};
            charts[rid]['small'] = new Chart($(row).find('.portfolio-row-graph canvas'), {
                type: 'line',
                options: smallLineOptions,
                data: {
                    datasets: [{
                        pointBorderWidth: 0,
                        pointRadius: 1,
                        pointBackgroundColor: 'rgba(8,233,56,1)',
                        borderWidth: 1,
                        lineTension: 0,
                        label: "",
                        fill: true,
                        backgroundColor: 'rgba(8,233,56,0.03)',
                        borderColor: 'rgba(8,233,56,1)',
                        data: chartData[chartsPeriod].data
                    }],
                    labels: chartData[chartsPeriod].labels
                }
            });
        }
    });
    $('.popup-portfolio-product').each(function(e) {
        var row = this;
        var rid = $(this).data('id');
        var chartData = integrityEnv.instruments[rid].chart;
        new Chart($(row).find('.popup-portfolio-product-graph canvas'), {
            type: 'line',
            options: smallLineOptions,
            data: {
                datasets: [{
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    borderWidth: 1,
                    lineTension: 0,
                    label: "",
                    fill: true,
                    backgroundColor: 'rgba(8,233,56,0.03)',
                    borderColor: 'rgba(8,233,56,1)',
                    data: chartData[chartsPeriod].data
                }],
                labels: chartData[chartsPeriod].labels
            }
        });
    });
});
window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;

    if(portfolioPage.bigDigitsPortfolioFree&&isVisible(portfolioPage.bigDigitsPortfolioFree)) {
        incrementDigits.init(".portfolio-product-title-digits-free .big-digits", {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 1500});
    }
    if(portfolioPage.bigDigitsPortfolioFunds&&isVisible(portfolioPage.bigDigitsPortfolioFunds)) {
        incrementDigits.init(".portfolio-product-title-digits-funds .big-digits", {decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 1500});
    }
    if(portfolioPage.bigDigitsPortfolioCreate&&isVisible(portfolioPage.bigDigitsPortfolioCreate)) {
        incrementDigits.init(".portfolio-product-title-digits-create .big-digits", {thousandSeparator: ",",decimalPlaces:(integrityEnv.isCurrentRateUSD?2:8),decimalPoint:".",duration: 1500});
    }
});


$('.popup-portfolio-product-btn').on('click', function() {
    $(this).prop('disabled', true);
    AddInstrument($(this).data('code'));
});

if(windowWidth<1140) {
    $('.portfolio-instruments').on('click', '.portfolio-row-percent > span', function() {
        var row = $(this).closest('.portfolio-row');
        row.find('.portfolio-row-percent-edit input').val(changePercents.getPersent(row.attr('id')));
        row.find('.portfolio-row-percent-edit').addClass('visible');
        row.find('.portfolio-row-percent-edit input').focus();
    });
    $('.portfolio-instruments').on('blur', '.portfolio-row-percent-edit input', function () {
        var row = $(this).closest('.portfolio-row');
        changePercents.update(row);
        if(row.find('.portfolio-row-percent-error').text() == '') {
            row.find('.portfolio-row-percent-edit').removeClass('visible');
            row.find('.portfolio-row-percent-error').removeClass('visible').text('');
        }
    });
} else {
    $('.portfolio-instruments').on('click', '.portfolio-row-percent-edit button', function() {
        changePercents.update($(this).closest('.portfolio-row'));
    });
    $('.portfolio-instruments').on('mouseenter', '.portfolio-row-percent > span', function() {
        var row = $(this).closest('.portfolio-row');
        row.find('.portfolio-row-percent-edit input').val(changePercents.getPersent(row.attr('id')));
        row.find('.portfolio-row-percent-edit').addClass('visible');
        row.find('.portfolio-row-percent-edit input').focus();
    });
    $('.portfolio-instruments').on('mouseleave', '.portfolio-row-percent', function () {
        $(this).find('.portfolio-row-percent-edit input').val('');
        $(this).find('.portfolio-row-percent-edit').removeClass('visible');
        $(this).find('.portfolio-row-percent-error').removeClass('visible').text('');
    });
}
$('.portfolio-instruments').on('focus', '.portfolio-row-percent-edit input', function() {
    $(this).closest('.portfolio-row').find('.portfolio-row-percent-error').removeClass('visible');
});
$('.portfolio-instruments').on('click', '.portfolio-row-show, .portfolio-row-graph canvas', function() {
    var row = $(this).closest('.portfolio-row');
    var rid = row.attr('id');
    //if(!rid in charts) charts[rid] = {};
    if(!row.hasClass('opened')) {
        if (!charts[rid]['large']) {
            charts[rid]['large'] = new Chart(row.find('.portfolio-row-more canvas'), {
                type: 'line',
                options: lineOptions,
                data: {
                    datasets: [{
                        label: ' ' + integrityEnv.instruments[rid]['fullname'],
                        pointBorderWidth: 0,
                        pointRadius: 1,
                        pointBackgroundColor: 'rgba(8,233,56,1)',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        borderWidth: 2,
                        borderColor: '#08e938',
                        lineTension: 0,
                        fill: true,
                        backgroundColor: 'rgba(0,167,120,.02)',
                        data: integrityEnv.instruments[rid].chart[chartsPeriod].data
                    }],
                    labels: integrityEnv.instruments[rid].chart[chartsPeriod].labels
                }
            });
        } else {
            console.log(integrityEnv.instruments[rid].chart[chartsPeriod].labels);
            charts[rid]['large'].data.labels = integrityEnv.instruments[rid].chart[chartsPeriod].labels;
            charts[rid]['large'].data.datasets.data = integrityEnv.instruments[rid].chart[chartsPeriod].data;
            charts[rid]['large'].update();
        }
    }
    row.find('.portfolio-row-more').slideToggle(300);
    row.toggleClass('opened');
});
$('.portfolio-product-title .btn_light_grey').on('click', function() {
    $('body').scrollTo('.portfolio-instruments', 400);
});
var changePercents = function() {
    var instruments = {};
    return {
        'getInstruments': function() {
          return instruments;
        },
        'getPersent': function(rid) {
          return (instruments[rid]&&instruments[rid]>0?instruments[rid]:'');
        },
        'totalPercent': function() {
            var total = 0;
            var totalSet = '<b>You already choose:</b>';
            $.each(instruments, function(key, item){
                totalSet += ' <span>'+key+'('+item+'%)</span>,';
                total += item;
            });
            if(total==100) {
                $('.portfolio-total-percent').text('');
                $('.portfolio-total-set').html(totalSet.substr(0, totalSet.length-1));
            } else if (total==0) {
                $('.portfolio-total-set').html('<b>You already choose:</b> no instruments');
                $('.portfolio-total-percent').text('Place 100% by instruments');
            } else {
                $('.portfolio-total-set').html(totalSet.substr(0, totalSet.length-1));
                $('.portfolio-total-percent').text('Place another '+(100-total)+'%');
            }
            return total;
        },
        'update': function(row) {
            var newPercent = parseInt(row.find('.portfolio-row-percent-edit input').val());
            var rid = row.attr('id');
            if(row.find('.portfolio-row-percent-edit input').val()=='' || newPercent == 0) {
                row.find('.portfolio-row-percent > span').text('0%');
                row.find('.portfolio-row-percent-edit').removeClass('visible');
                delete instruments[rid];
                updateMainCharts();
                this.totalPercent();
            } else if(newPercent&&(newPercent<=100-this.totalPercent()+(instruments[rid]?instruments[rid]:0))) {
                if($('.portfolio-product-body-graphs').css('display')=='none') {
                    $('.portfolio-product-body-graphs').slideDown(300);
                    $('body').scrollTo($('.portfolio-product-body-graphs'), 400);
                }
                row.find('.portfolio-row-percent > span').text(newPercent+'%');
                row.find('.portfolio-row-percent-edit').removeClass('visible');
                instruments[rid] = newPercent;
                if(this.totalPercent() == 100) {
                    $('#portfolio-create-product').prop('disabled', false);
                } else {
                    $('#portfolio-create-product').prop('disabled', true);
                }
                updateMainCharts();
            } else {
                this.totalPercent();
                var error = row.find('.portfolio-row-percent-error');
                error.text('Type the percent less than '+(100-this.totalPercent()+(instruments[rid]?instruments[rid]:0))).addClass('visible');
            }
        }
    }
}();
$('#portfolio-create-product').on('click', function() {
    var btn = $(this),
        formData = new FormData(),
        assets = changePercents.getInstruments();

    btn.prop('disabled', true);

    for(var code in assets){
        formData.set('assets['+code+']', assets[code]);
    }

    $.ajax({
        url: btn.data('link'),
        type: 'POST',
        cache: false,
        data: formData,
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


$('.portfolio-product-body-graph .histogramm-menu span').on('click', function() {
    chartsPeriod = $(this).data('period');
    $('.portfolio-row-more').slideUp(300);
    $('.portfolio-row.opened').removeClass('opened');
    updateMainCharts();
    updateSmallCharts();
});