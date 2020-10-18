/**
 * Draw charts
 *
 * @param label
 * @param data
 */
function drawChart(label, data) {

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Kanit', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: label,
            datasets: [{
                label: "Clicks",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: data,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function (value, index, values) {
                            return number_format(value) + ' clicks';
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function (tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });

    //Hide loader 
    $("#loader-area").hide();

    //Show canvas 
    $("#myAreaChart").show();
}

/**
 * Chart Area
 *
 * @param code
 * @param date
 */
function clicksChartArea(code, date) {

    //Show loader & hide area
    $("#loader-area").show();
    $('#myAreaChart').hide();

    let labelsChart;
    let dataChart;

    //Ajax 
    $.ajax({
        type: "POST",
        url: "../charts",
        data: {
            code: code,
            date: date,
            chart: 'clicksCount'
        },
        success: function (data) {
            // Parse data
            let obj = JSON.parse(data);

            if (obj.date == 'day') {
                let hours = [];
                obj.time.forEach(item => {
                    hours.push(timeConverter(item));
                });

                //Labels
                labelsChart = hours;
                dataChart = obj.hours;

                //Destroy chart is exist
                if (typeof myLineChart !== 'undefined') {
                    myLineChart.destroy();
                }

                drawChart(labelsChart, dataChart);
                $('#chart-area-badge').text("day");

            } else if (obj.date == 'week') {
                let days = [];
                obj.time.forEach(item => {
                    days.push(timeConverter(item));
                });

                // Labels
                labelsChart = days;
                dataChart = obj.days;

                if (typeof myLineChart !== 'undefined') {
                    myLineChart.destroy();
                }

                drawChart(labelsChart, dataChart);
                $('#chart-area-badge').text("week");

            } else if (obj.date == 'month') {
                let days = [];
                obj.time.forEach(item => {
                    days.push(timeConverter(item));
                });

                // Labels
                labelsChart = days;
                dataChart = obj.month;

                if (typeof myLineChart !== 'undefined') {
                    myLineChart.destroy();
                }

                drawChart(labelsChart, dataChart);
                $('#chart-area-badge').text("month");

            } else if (obj.date == 'year') {
                let month = [];
                obj.time.forEach(item => {
                    month.push(timeConverter(item));
                });

                // Label
                labelsChart = month;
                dataChart = obj.year;

                if (typeof myLineChart !== 'undefined') {
                    myLineChart.destroy();
                }

                drawChart(labelsChart, dataChart);
                $('#chart-area-badge').text("year");
            }
        }
    });

}

