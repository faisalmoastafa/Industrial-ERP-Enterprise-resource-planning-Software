$(document).ready(function () {
    const gridColor = 'rgba(148, 163, 184, 0.18)';
    const textColor = '#64748b';

    Chart.defaults.font.family = "'Nunito', 'Segoe UI', sans-serif";
    Chart.defaults.color = textColor;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.boxWidth = 8;
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15, 23, 42, 0.92)';
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 12;
    Chart.defaults.plugins.tooltip.yAlign = 'bottom';

    const makeGradient = (chart, topColor, bottomColor) => {
        const {ctx, chartArea} = chart;

        if (!chartArea) {
            return topColor;
        }

        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        gradient.addColorStop(0, topColor);
        gradient.addColorStop(1, bottomColor);

        return gradient;
    };

    const axisOptions = {
        x: {
            grid: {display: false},
            ticks: {color: textColor}
        },
        y: {
            beginAtZero: true,
            grid: {color: gridColor, drawBorder: false},
            ticks: {color: textColor}
        }
    };

    let salesPurchasesBar = document.getElementById('salesPurchasesChart');
    if (salesPurchasesBar) {
        $.get('/sales-purchases/chart-data', function (response) {
            new Chart(salesPurchasesBar, {
                type: 'bar',
                data: {
                    labels: response.sales.original.days,
                    datasets: [
                        {
                            label: 'Sales',
                            data: response.sales.original.data,
                            backgroundColor: (context) => makeGradient(context.chart, '#6366f1', '#38bdf8'),
                            borderRadius: 14,
                            borderSkipped: false,
                            barPercentage: 0.62,
                            categoryPercentage: 0.62
                        },
                        {
                            label: 'Purchases',
                            data: response.purchases.original.data,
                            backgroundColor: (context) => makeGradient(context.chart, '#f97316', '#fdba74'),
                            borderRadius: 14,
                            borderSkipped: false,
                            barPercentage: 0.62,
                            categoryPercentage: 0.62
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {position: 'top'}
                    },
                    scales: axisOptions
                }
            });
        });
    }

    let overviewChart = document.getElementById('currentMonthChart');
    if (overviewChart) {
        $.get('/current-month/chart-data', function (response) {
            new Chart(overviewChart, {
                type: 'doughnut',
                data: {
                    labels: ['Sales', 'Purchases', 'Expenses'],
                    datasets: [{
                        data: [response.sales, response.purchases, response.expenses],
                        backgroundColor: (context) => {
                            const colors = [
                                makeGradient(context.chart, '#fbbf24', '#f97316'),
                                makeGradient(context.chart, '#38bdf8', '#2563eb'),
                                makeGradient(context.chart, '#fb7185', '#ef4444')
                            ];

                            return colors[context.dataIndex] || '#38bdf8';
                        },
                        borderColor: 'rgba(255,255,255,0.92)',
                        borderRadius: 10,
                        borderWidth: 5,
                        hoverOffset: 14,
                        spacing: 4,
                    }]
                },
                options: {
                    cutout: '68%',
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {position: 'top'},
                        tooltip: { yAlign: 'bottom' }
                    }
                }
            });
        });
    }

    let stockMovementChart = document.getElementById('stockMovementChart');
    if (stockMovementChart) {
        $.get('/stock-movement/chart-data', function (response) {
            new Chart(stockMovementChart, {
                type: 'line',
                data: {
                    labels: response.days,
                    datasets: [
                        {
                            label: 'Stock In',
                            data: response.incoming,
                            borderColor: '#22c55e',
                            backgroundColor: (context) => makeGradient(context.chart, 'rgba(0, 0, 0, 0.15)', 'rgba(255, 255, 255, 0.0)'),
                            fill: true,
                            tension: 0.45,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#22c55e',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Stock Out',
                            data: response.outgoing,
                            borderColor: '#f43f5e',
                            backgroundColor: (context) => makeGradient(context.chart, 'rgba(0, 0, 0, 0.15)', 'rgba(255, 255, 255, 0.0)'),
                            fill: true,
                            tension: 0.45,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#f43f5e',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {position: 'top'}
                    },
                    scales: axisOptions
                }
            });
        });
    }

    let stockMovementWeeklyChart = document.getElementById('stockMovementWeeklyChart');
    if (stockMovementWeeklyChart) {
        $.get('/stock-movement/weekly-chart-data', function (response) {
            new Chart(stockMovementWeeklyChart, {
                type: 'line',
                data: {
                    labels: response.days,
                    datasets: [
                        {
                            label: 'Stock In',
                            data: response.incoming,
                            borderColor: '#22c55e',
                            backgroundColor: (context) => makeGradient(context.chart, 'rgba(0, 0, 0, 0.15)', 'rgba(255, 255, 255, 0.0)'),
                            fill: true,
                            tension: 0.45,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#22c55e',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Stock Out',
                            data: response.outgoing,
                            borderColor: '#f43f5e',
                            backgroundColor: (context) => makeGradient(context.chart, 'rgba(0, 0, 0, 0.15)', 'rgba(255, 255, 255, 0.0)'),
                            fill: true,
                            tension: 0.45,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#f43f5e',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {position: 'top'}
                    },
                    scales: axisOptions
                }
            });
        });
    }

    let paymentChart = document.getElementById('paymentChart');
    if (paymentChart) {
        $.get('/payment-flow/chart-data', function (response) {
            new Chart(paymentChart, {
                type: 'line',
                data: {
                    labels: response.months,
                    datasets: [
                        {
                            label: 'Payment Sent',
                            data: response.payment_sent,
                            fill: true,
                            backgroundColor: 'rgba(234, 88, 12, 0.12)',
                            borderColor: '#ea580c',
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#ea580c',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            tension: 0.38
                        },
                        {
                            label: 'Payment Received',
                            data: response.payment_received,
                            fill: true,
                            backgroundColor: 'rgba(37, 99, 235, 0.12)',
                            borderColor: '#2563eb',
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#2563eb',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            tension: 0.38
                        },
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {position: 'top'}
                    },
                    scales: axisOptions
                }
            });
        });
    }

    let payableReceivableChart = document.getElementById('payableReceivableChart');
    if (payableReceivableChart) {
        $.get('/payable-receivable/chart-data', function (response) {
            new Chart(payableReceivableChart, {
                type: 'line',
                data: {
                    labels: response.months,
                    datasets: [
                        {
                            label: 'Net Payable',
                            data: response.payable,
                            fill: true,
                            backgroundColor: 'rgba(168, 85, 247, 0.12)',
                            borderColor: '#a855f7',
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#a855f7',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            tension: 0.38
                        },
                        {
                            label: 'Net Receivable',
                            data: response.receivable,
                            fill: true,
                            backgroundColor: 'rgba(20, 184, 166, 0.12)',
                            borderColor: '#14b8a6',
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#14b8a6',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            tension: 0.38
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {position: 'top'}
                    },
                    scales: axisOptions
                }
            });
        });
    }

    let prepayChart = document.getElementById('prepayChart');
    let payLaterChart = document.getElementById('payLaterChart');
    if (prepayChart && payLaterChart) {
        $.get('/prepay-pay-later/chart-data', function (response) {
            new Chart(prepayChart, {
                type: 'bar',
                data: {
                    labels: response.months,
                    datasets: [
                        {
                            label: 'Customer Prepay',
                            data: response.customer_prepay,
                            backgroundColor: (context) => makeGradient(context.chart, '#6366f1', '#38bdf8'),
                            borderRadius: 10,
                            barPercentage: 0.6,
                            categoryPercentage: 0.6
                        },
                        {
                            label: 'Supplier Prepay',
                            data: response.supplier_prepay,
                            backgroundColor: (context) => makeGradient(context.chart, '#f97316', '#fdba74'),
                            borderRadius: 10,
                            barPercentage: 0.6,
                            categoryPercentage: 0.6
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {position: 'top'}
                    },
                    scales: axisOptions
                }
            });

            new Chart(payLaterChart, {
                type: 'bar',
                data: {
                    labels: response.months,
                    datasets: [
                        {
                            label: 'Customer Pay Later',
                            data: response.customer_pay_later,
                            backgroundColor: (context) => makeGradient(context.chart, '#14b8a6', '#2dd4bf'),
                            borderRadius: 10,
                            barPercentage: 0.6,
                            categoryPercentage: 0.6
                        },
                        {
                            label: 'Supplier Pay Later',
                            data: response.supplier_pay_later,
                            backgroundColor: (context) => makeGradient(context.chart, '#a855f7', '#d8b4fe'),
                            borderRadius: 10,
                            barPercentage: 0.6,
                            categoryPercentage: 0.6
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {position: 'top'}
                    },
                    scales: axisOptions
                }
            });
        });
    }

    let businessFlowChartCanvas = document.getElementById('businessFlowChart');
    if (businessFlowChartCanvas) {
        let sales = businessFlowChartCanvas.dataset.sales || 0;
        let purchases = businessFlowChartCanvas.dataset.purchases || 0;
        new Chart(businessFlowChartCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Sales', 'Purchases'],
                datasets: [{
                    data: [sales, purchases],
                    backgroundColor: ['#3b82f6', '#f97316'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});
