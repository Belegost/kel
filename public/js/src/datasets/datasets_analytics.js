var datasetAnalytics = {
    productPie: {
        conservative: {
            datasets: [{
                data: [16, 9, 8, 10, 6, 4],
                backgroundColor: [
                    '#004769',
                    '#015780',
                    '#0277a5',
                    '#1597cf',
                    '#1fbad6',
                    '#72c8ec'
                ]
            }],
            labels: [
                "LTC",
                "ETC",
                "ITC CT",
                "BTC",
                "ETH",
                "BCH"
            ]
        },
        classic: {
            datasets: [{
                data: [16, 9, 8, 10],
                backgroundColor: [
                    '#015780',
                    '#0277a5',
                    '#1597cf',
                    '#1fbad6',
                ]
            }],
            labels: [
                "LTC",
                "ETC",
                "ITC CT",
                "BTC",
                "ETH",
                "BCH",
            ]
        },
        confident: {
            datasets: [{
                data: [7, 16, 9, 8, 10, 6, 4, 12],
                backgroundColor: [
                    '#003650',
                    '#004769',
                    '#015780',
                    '#0277a5',
                    '#1597cf',
                    '#1fbad6',
                    '#72c8ec',
                    '#78d2f8',
                ]
            }],
            labels: [
                "LTC",
                "ETC",
                "ITC CT",
                "BTC",
                "ETH",
                "BCH",
                "ACH",
                "XVc",
            ]
        }
    },
    graph1: {
        day1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [8000, 7200, 6700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                },
                {
                    label: ' Price',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(204,204,204,1)',
                    pointBorderColor: 'rgba(204,204,204,0)',
                    backgroundColor: '#cccccc',
                    borderWidth: 2,
                    borderColor: '#cccccc',
                    lineTension: 0,
                    fill: false,
                    data: [3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        w1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [12000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m3: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [22000, 2200, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [32000, 2200, 1700, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y5: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        max: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400, 6100, 10400, 6700, 4900, 4400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        }
    },
    graph2: {
        d1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        w1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m3: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y5: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        max: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400, 6100, 10400, 6700, 4900, 4400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        }
    },
    graph3: {
        d1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        w1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m3: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y5: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        max: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400, 6100, 10400, 6700, 4900, 4400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        }
    },
    graph4: {
        d1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        w1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m3: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y5: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        max: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400, 6100, 10400, 6700, 4900, 4400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        }
    },
    graph5: {
        d1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        w1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m3: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y1: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 5800, 3300, 4900, 6400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y5: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        max: {
            datasets: [
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400, 6100, 10400, 6700, 4900, 4400]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        }
    },
    totalHistogramm: {
        d1: {
            datasets: [
                {
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
                    data: [3500, 2000, 2300, 1700, 4500, 3800, 5100, 3000, 5700, 6100, 12500, 9700, 7300, 5400, 8100, 7400, 8100, 11400, 6400, 8600, 5800, 4300, 13900]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: false,
                    data: [1500, 2600, 1900, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1500, 970, 1300, 2400, 3100, 2200, 1800, 1400, 3400, 2600, 2800, 1300, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        w1: {
            datasets: [
                {
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
                    data: [3500, 2000, 7300, 5400, 8100, 7400, 8100, 11400, 6400, 8600, 5800, 4300, 13900, 2300, 1700, 4500, 3800, 5100, 3000, 5700, 6100, 12500, 9700]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: false,
                    data: [3100, 2200, 1800, 1400, 3400, 2600, 2800, 1300, 1500, 2600, 1900, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1500, 970, 1300, 2400, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m1: {
            datasets: [
                {
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
                    data: [3500, 5100, 3000, 5700, 6100, 12500, 9700, 7300, 5400, 8100, 7400, 2000, 2300, 1700, 4500, 3800, 8100, 11400, 6400, 8600, 5800, 4300, 13900]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: false,
                    data: [1500, 2600, 1900, 1500, 970, 1300, 2400, 3100, 2200, 1800, 1400, 3400, 2600, 2800, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1300, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m3: {
            datasets: [
                {
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
                    data: [3500, 2000, 3000, 5700, 6100, 12500, 7300, 5400, 8100, 7400, 8100, 11400, 6400, 8600, 5800, 4300, 13900, 2300, 1700, 4500, 3800, 5100, 9700]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: false,
                    data: [3100, 2200, 1800, 1400, 3400, 2600, 2800, 1300, 1500, 2600, 1900, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1500, 970, 1300, 2400, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y1: {
            datasets: [
                {
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
                    data: [11400, 6400, 8600, 5800, 4300, 13900, 2300, 1700, 4500, 3800, 5100, 3000, 5700, 6100, 12500, 3500, 2000, 7300, 5400, 8100, 7400, 8100, 9700]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 5800, 3300, 4900, 6400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: false,
                    data: [3100, 2200, 1800, 1400, 3400, 2500, 3200, 2100, 1000, 1700, 2600, 2800, 1300, 1500, 2600, 1900, 1100, 2100, 1500, 970, 1300, 2400, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y5: {
            datasets: [
                {
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
                    data: [3500, 5100, 3000, 5700, 6100, 12500, 9700, 7300, 5400, 8100, 7400, 2000, 2300, 1700, 4500, 3800, 8100, 11400, 6400, 8600, 5800, 4300, 13900]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: false,
                    data: [1500, 2600, 1900, 1500, 970, 1300, 2400, 3100, 2200, 1800, 1400, 3400, 2600, 2800, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1300, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        max: {
            datasets: [
                {
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
                    data: [3500, 6400, 8600, 5800, 4300, 13900, 2300, 1700, 4500, 3800, 5100, 9700, 2000, 3000, 5700, 6100, 12500, 7300, 5400, 8100, 7400, 8100, 11400]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: false,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400, 6100, 10400, 6700, 4900, 4400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0.25,
                    fill: false,
                    data: [3100, 2200, 1800, 1400, 3400, 2600, 2800, 1300, 1500, 2400, 1900, 2600, 1900, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1500, 970, 1300]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        }
    },
    totalCap: {
        d1: {
            datasets: [
                {
                    label: ' Total Equity',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(1,149,232,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: 'rgba(1,149,232,0.1)',
                    borderWidth: 2,
                    borderColor: '#0195E8',
                    lineTension: 0,
                    data: [3500, 4500, 5100, 3000, 7300, 5400, 8100]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: 'rgba(8,233,56,0.1)',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    data: [2000, 2100, 9500, 6700, 7100, 5800, 10400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: 'rgba(232,43,7,0.1)',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: true,
                    data: [1500, 3100, 2200, 1800, 2800, 1300, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.8', 'Dec.12', 'Dec.17', 'Dec.21', 'Dec.25', 'Dec.30']
        },
        w1: {
            datasets: [
                {
                    label: ' Total Equity',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(1,149,232,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#0195E8',
                    borderWidth: 2,
                    borderColor: '#0195E8',
                    lineTension: 0,
                    fill: true,
                    data: [3500, 2000, 7300, 5400, 8100, 7400, 8100, 11400, 6400, 8600, 5800, 4300, 13900, 2300, 1700, 4500, 3800, 5100, 3000, 5700, 6100, 12500, 9700]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: true,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: true,
                    data: [3100, 2200, 1800, 1400, 3400, 2600, 2800, 1300, 1500, 2600, 1900, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1500, 970, 1300, 2400, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m1: {
            datasets: [
                {
                    label: ' Total Equity',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(1,149,232,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#0195E8',
                    borderWidth: 2,
                    borderColor: '#0195E8',
                    lineTension: 0,
                    fill: true,
                    data: [3500, 5100, 3000, 5700, 6100, 12500, 9700, 7300, 5400, 8100, 7400, 2000, 2300, 1700, 4500, 3800, 8100, 11400, 6400, 8600, 5800, 4300, 13900]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: true,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: true,
                    data: [1500, 2600, 1900, 1500, 970, 1300, 2400, 3100, 2200, 1800, 1400, 3400, 2600, 2800, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1300, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        m3: {
            datasets: [
                {
                    label: ' Total Equity',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(1,149,232,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#0195E8',
                    borderWidth: 2,
                    borderColor: '#0195E8',
                    lineTension: 0,
                    fill: true,
                    data: [3500, 2000, 3000, 5700, 6100, 12500, 7300, 5400, 8100, 7400, 8100, 11400, 6400, 8600, 5800, 4300, 13900, 2300, 1700, 4500, 3800, 5100, 9700]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: true,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: true,
                    data: [3100, 2200, 1800, 1400, 3400, 2600, 2800, 1300, 1500, 2600, 1900, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1500, 970, 1300, 2400, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y1: {
            datasets: [
                {
                    label: ' Total Equity',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(1,149,232,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#0195E8',
                    borderWidth: 2,
                    borderColor: '#0195E8',
                    lineTension: 0,
                    fill: true,
                    data: [11400, 6400, 8600, 5800, 4300, 13900, 2300, 1700, 4500, 3800, 5100, 3000, 5700, 6100, 12500, 3500, 2000, 7300, 5400, 8100, 7400, 8100, 9700]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: true,
                    data: [2000, 2200, 1700, 8100, 6100, 10400, 6700, 4900, 4400, 7100, 8600, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 5800, 3300, 4900, 6400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: true,
                    data: [3100, 2200, 1800, 1400, 3400, 2500, 3200, 2100, 1000, 1700, 2600, 2800, 1300, 1500, 2600, 1900, 1100, 2100, 1500, 970, 1300, 2400, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        y5: {
            datasets: [
                {
                    label: ' Total Equity',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(1,149,232,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#0195E8',
                    borderWidth: 2,
                    borderColor: '#0195E8',
                    lineTension: 0,
                    fill: true,
                    data: [3500, 5100, 3000, 5700, 6100, 12500, 9700, 7300, 5400, 8100, 7400, 2000, 2300, 1700, 4500, 3800, 8100, 11400, 6400, 8600, 5800, 4300, 13900]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: true,
                    data: [2000, 2200, 1700, 2500, 2600, 2100, 3000, 3700, 3500, 5100, 9500, 6700, 4900, 4400, 7100, 8600, 5800, 3300, 4900, 6400, 8100, 6100, 10400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: true,
                    data: [1500, 2600, 1900, 1500, 970, 1300, 2400, 3100, 2200, 1800, 1400, 3400, 2600, 2800, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1300, 1900]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        },
        max: {
            datasets: [
                {
                    label: ' Total Equity',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(1,149,232,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#0195E8',
                    borderWidth: 2,
                    borderColor: '#0195E8',
                    lineTension: 0,
                    fill: true,
                    data: [3500, 6400, 8600, 5800, 4300, 13900, 2300, 1700, 4500, 3800, 5100, 9700, 2000, 3000, 5700, 6100, 12500, 7300, 5400, 8100, 7400, 8100, 11400]
                },
                {
                    label: ' Bitcoin',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(8,233,56,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#08e938',
                    borderWidth: 2,
                    borderColor: '#08e938',
                    lineTension: 0,
                    fill: true,
                    data: [2000, 2200, 3500, 5100, 9500, 8100, 7100, 1700, 2500, 2600, 2100, 3000, 3700, 8600, 5800, 3300, 4900, 6400, 6100, 10400, 6700, 4900, 4400]
                },
                {
                    label: ' Etherium',
                    pointBorderWidth: 0,
                    pointRadius: 1,
                    pointBackgroundColor: 'rgba(232,43,7,1)',
                    pointBorderColor: 'rgba(255,255,255,0)',
                    backgroundColor: '#e82b07',
                    borderWidth: 2,
                    borderColor: '#e82b07',
                    lineTension: 0,
                    fill: true,
                    data: [3100, 2200, 1800, 1400, 3400, 2600, 2800, 1300, 1500, 2400, 1900, 2600, 1900, 1100, 2500, 3200, 2100, 1000, 1700, 2100, 1500, 970, 1300]
                }
            ],
            labels: ['Dec.3', 'Dec.4', 'Dec.5', 'Dec.6', 'Dec.7', 'Dec.8', 'Dec.9', 'Dec.10', 'Dec.11', 'Dec.12', 'Dec.13', 'Dec.14', 'Dec.15', 'Dec.16', 'Dec.17', 'Dec.18', 'Dec.19', 'Dec.20', 'Dec.21', 'Dec.22', 'Dec.23', 'Dec.24', 'Dec.25']
        }
    }
};