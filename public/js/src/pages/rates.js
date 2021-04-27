$('.rates-informer.owl-carousel').owlCarousel({
    responsive: {
        0: { items: 1 },
        480: { items: 2 },
        768: { items: 3 },
        1024: { items: 4 },
        1170: { items: 5 }
    }
});

var ratesHeaderDigits = $('.rates .big-digits');
if(ratesHeaderDigits.length) {
    $('.page-header .big-digits').spincrement({
        thousandSeparator: ",",
        decimalPlaces: 0,
        duration: 1500
    });
}

var totalHistogrammWrap = document.getElementById("total_histogramm");
if(totalHistogrammWrap) {
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
        data: {
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25'],
            datasets: [{
                label: ' Total Equity',
                pointBorderWidth: 0,
                pointRadius: 1,
                pointBackgroundColor: 'rgba(1,149,232,1)',
                pointBorderColor: 'rgba(255,255,255,0)',
                backgroundColor: '#0195E8',
                borderWidth: 2,
                borderColor: '#0195E8',
                lineTension: 0,
                fill: false,
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }]
        },
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
}

function createChart(id, type, options) {
    var data = {
        datasets: [{
            data: [20, 37, 43, 58, 66],
            backgroundColor: [
                '#bababa',
                '#747474',
                '#45cf8b',
                '#00a6e9',
                '#0074f3'
            ]
        }],
        labels: [
            "Bitcoin cash",
            "Litcoin",
            "Actions",
            "Blockchains",
            "CFD's"
        ]
    };

    return new Chart(document.getElementById(id), {
        type: type,
        data: data,
        options: options
    });
}
function updateChart(chart, data) {
    data = data || '';
    labels = ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25'];
    newdataset = [[3500, 2000, 2300, 1700, 4500, 3800, 5100, 3000, 5700, 6100, 12500, 9700, 7300, 5400, 8100, 7400, 8100, 11400, 6400, 8600, 5800, 4300, 13900]];
    chart.data.labels = labels;
    $.each(newdataset, function (key, value) {
        chart.data.datasets[key].data = value;
    });
    chart.update();
}

var ratesPage = (function(){
    return {
        bigDigitsTotalHistogrammBtc:document.querySelector('.rates-charts-total_histogramm-btc'),
        totalHistogramm: document.querySelector('#total_histogramm'),
        polarHistogramm: document.querySelector('#polar_histogramm'),
        cryptoGraphWrap: document.getElementById('crypto-market-graph')
    }
})();

window.addEventListener('load', function() {
    histogrammMenu(document.querySelector('.histogramm-menu'));
});
window.addEventListener('scroll', function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;

    if(ratesPage.bigDigitsTotalHistogrammBtc&&isVisible(ratesPage.bigDigitsTotalHistogrammBtc)) {
        incrementDigits.init(".rates-charts-total_histogramm-btc", {thousandSeparator: ",",decimalPlaces:0,decimalPoint:".",duration: 1500});
    }

    if(ratesPage.totalHistogramm&&isVisible(ratesPage.totalHistogramm)) {
        ratesPage.totalHistogramm = false;
        updateChart(totalHistogramm);
    }
    if(ratesPage.cryptoGraphWrap&&isVisible(ratesPage.cryptoGraphWrap)) {
        updateChart(cryptoGraph);
    }
    if(ratesPage.polarHistogramm&&isVisible(ratesPage.polarHistogramm)) {
        createChart('polar_histogramm', 'polarArea', {
            responsive: true,
            tooltips: {
                enabled: true
            },
            scale: {
                display: false
            },
            legend: {
                display: true,
                position: 'right',
                labels: {
                    padding: 30,
                    fontFamily: 'Open Sans',
                    fontSize: 14
                }
            },
            elements: {
                arc: {
                    borderWidth: 0
                }
            },
            animation: {
                duration: 2000
            }
        });
        ratesPage.polarHistogramm = false;
    }

});
if(ratesPage.cryptoGraphWrap) {
    var cryptoGraphTooltips = function (tooltip) {
        var tooltipEl = document.getElementById('crypto_graph_tooltip');

        if (!tooltipEl) {
            tooltipEl = document.createElement('div');
            tooltipEl.id = 'crypto_graph_tooltip';
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
    var cryptoGraph = new Chart(ratesPage.cryptoGraphWrap, {
        type: 'line',
        data: {
            labels: ['Jan 13', 'Jan 14', 'Jan 15', 'Jan 16', 'Jan 17', 'Jan 18', 'Jan 19', 'Jan 20', 'Jan 21', 'Jan 22', 'Jan 23', 'Jan 24', 'Jan 25', 'Jan 26', 'Jan 27', 'Jan 28', 'Jan 29', 'Jan 30', 'Jan 31', 'Feb 1', 'Feb 2', 'Feb 3', 'Feb 5'],
            datasets: [{
                label: ' BTC  ',
                pointBorderWidth: 0,
                pointRadius: 1,
                pointBackgroundColor: 'rgba(77,207,226,1)',
                pointBorderColor: 'rgba(255,255,255,0)',
                backgroundColor: '#4dcfe2',
                borderWidth: 2,
                borderColor: '#4dcfe2',
                lineTension: 0,
                fill: false,
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }]
        },
        options: {
            responsive: true,
            scales: {
                xAxes: [{
                    ticks: {
                        maxTicksLimit: 6,
                        beginAtZero: false,
                        fontColor: 'rgba(255,255,255,.65)'
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
                        beginAtZero: false,
                        fontColor: 'rgba(255,255,255,.65)'
                    },
                    gridLines: {
                        display: true,
                        zeroLineWidth: 0,
                        color: 'rgba(255,255,255,.15)'
                    },
                    scaleLabel: {
                        fontColor: '#ffffff'
                    }
                }]
            },
            tooltips: {
                enabled: false,
                mode: 'index',
                position: 'nearest',
                intersect: false,
                custom: cryptoGraphTooltips
            },
            title: {
                display: false
            },
            legend: {
                display: false,
                labels: {
                    fontColor: '#ffffff',
                    usePointStyle: true,
                    fillStyle: '#ffffff'
                }
            },
            elements: {
                rectangle: {
                    backgroundColor: 'rgba(255,255,255,0.8)',
                    borderWidth: 0
                },
                point: {
                    radius: 4,
                    hoverRadius: 4
                }
            },
            animation: {
                duration: 3000
            }
        }
    });
}
function updateChart(chart, data) {
    data = data || '';
    labels = ['Jan 13', 'Jan 14', 'Jan 15', 'Jan 16', 'Jan 17', 'Jan 18', 'Jan 19', 'Jan 20', 'Jan 21', 'Jan 22', 'Jan 23', 'Jan 24', 'Jan 25', 'Jan 26', 'Jan 27', 'Jan 28', 'Jan 29', 'Jan 30', 'Jan 31', 'Feb 1', 'Feb 2', 'Feb 3', 'Feb 5'];
    newdataset = [
        [3500, 2000, 2300, 1700, 4500, 3800, 5100, 3000, 5700, 6100, 12500, 9700, 7300, 5400, 8100, 7400, 8100, 11400, 6400, 8600, 5800, 4300, 13900]];
    chart.data.labels = labels;
    $.each(newdataset, function (key, value) {
        chart.data.datasets[key].data = value;
    });
    chart.update();
}