window.addEventListener('load', function() {
    incrementDigits.init(".big-digits-trust");
    incrementDigits.init(".big-digits-strategies");

    $('.crypto .hover').hover(function(){
        $(this).find('.panel').addClass('flip');
    },function(){
        $(this).find('.panel').removeClass('flip');
    });

    $(".crypto-overview-gallery.owl-carousel").owlCarousel({
        margin:10,
        autoWidth:true
    });
    $('.crypto .dashboard-menu-tabs-item').on('click', function() {

        $('.crypto .dashboard-menu-tabs-item.current').removeClass('current');
        $(this).addClass('current');
        var currTab = $(this).attr('data-target');
        var cryptoWrapHeight = (currTab=='tab1'?$('.crypto-overview').height():currTab=='tab2'?$('.crypto-market').height():$('.crypto-resourses').height())+60;
        document.querySelector('.crypto-tabs').className = 'crypto-tabs '+$(this).attr('data-target');
        $('.crypto-tabs').height(cryptoWrapHeight);
        if($(this).attr('data-target')=='tab3') {
            $('.crypto-resourses .crypto-resourses-feed').addClass('animate-visible');
            setTimeout(showVisible(), 1000);
        }
        if($(this).attr('data-target')=='tab2') {
            updateChart(cryptoGraph);
            $('.crypto-market-infobox .big-digits').spincrement({
                thousandSeparator: "",
                decimalPlaces: 2,
                decimalPoint: ".",
                duration: 2500
            });
            $('.crypto-market-inception .big-digits span').spincrement({
                thousandSeparator: "",
                decimalPlaces: 2,
                decimalPoint: ".",
                duration: 3500
            });
        }
    });
});
window.addEventListener('resize', function() {
    $('.crypto .dashboard-menu-tabs-item.current').click();
});
var cryptoGraphWrap = document.getElementById("crypto-market-graph");
if(cryptoGraphWrap) {
    var cryptoGraphTooltips = function(tooltip) {
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

            titleLines.forEach(function(title) {
                innerHtml += '<div class="tooltip-title-item">' + title + '</div>';
            });
            innerHtml += '</div><div class="tooltip-body">';

            bodyLines.forEach(function(body, i) {
                var bodyParts = (''+body).split(':');
                innerHtml += '<div class="tooltip-body-item"><span style="background:' + tooltip.labelColors[i]['backgroundColor'] + '"></span>'+bodyParts[0]+':<b>'+bodyParts[1]+'</b>'+'</div>';
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
        tooltipEl.style.left = positionX+'px';
        tooltipEl.style.top = positionY+'px';
        tooltipEl.style.width = canvasWidth+'px';
        tooltipEl.style.height = canvasHeight+'px';
        tooltipPointer.style.left = tooltip.caretX+'px';

        if (tooltip.caretX+tooltipWrapper.clientWidth/2+30<canvasWidth) {
            tooltipWrapper.style.left = (tooltip.caretX+tooltipWrapper.clientWidth/2+30) + 'px';
        } else {
            tooltipWrapper.style.left = (tooltip.caretX-tooltipWrapper.clientWidth/2-30) + 'px';
        }
    };
    var cryptoGraph = new Chart(cryptoGraphWrap, {
        type: 'line',
        data: {
            labels: ['Jan 13', 'Jan 14', 'Jan 15', 'Jan 16', 'Jan 17', 'Jan 18', 'Jan 19', 'Jan 20', 'Jan 21', 'Jan 22', 'Jan 23', 'Jan 24', 'Jan 25', 'Jan 26', 'Jan 27', 'Jan 28', 'Jan 29', 'Jan 30', 'Jan 31', 'Feb 1', 'Feb 2', 'Feb 3', 'Feb 5'],
            datasets: [{
                label: ' Market/Share  ',
                pointBorderWidth: 0,
                pointRadius: 1,
                pointBackgroundColor: 'rgba(117,68,249,1)',
                pointBorderColor: 'rgba(255,255,255,0)',
                backgroundColor: '#7544f9',
                borderWidth: 2,
                borderColor: '#7544f9',
                lineTension: 0,
                fill: false,
                data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            },
                {
                    label: ' Bitcoin Holdings/Share  ',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(38,212,252,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#26d4fc',
                    borderWidth: 2,
                    borderColor: '#26d4fc',
                    lineTension: 0,
                    fill: false,
                    data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
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
                display: true,
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
        [3500, 2000, 2300, 1700, 4500, 3800, 5100, 3000, 5700, 6100, 12500, 9700, 7300, 5400, 8100, 7400, 8100, 11400, 6400, 8600, 5800, 4300, 13900],
        [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
    ];
    chart.data.labels = labels;
    $.each(newdataset, function (key, value) {
        chart.data.datasets[key].data = value;
    });
    chart.update();
}