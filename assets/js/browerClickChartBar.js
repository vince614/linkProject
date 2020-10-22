/**
 * Draw bar
 *
 * @param datas
 * @param labels
 */
function drawBar(datas, labels) {
    var ctx = document.getElementById("myBarChart");
    myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Clicks",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderColor: "#4e73df",
                data: datas,
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
            legend: {
                display: false
            },
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function (tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                    }
                }
            },
        }
    });

    //Hide loader
    $("#loader-bar").hide();

    //Show canvas
    $('#myBarChart').show();

}

/**
 * Chart bar
 * @param code
 */
function browerClickChartBar(code) {
    $("#loader-bar").show();
    $('#myBarChart').hide();
    let datasCharts;
    let labels;
    $.ajax({
        type: "POST",
        url: hostUrl + "/charts",
        data: {
            code: code,
            chart: 'browserClicks'
        },
        success: function (data) {
            let obj = JSON.parse(data);
            datasCharts = Object.values(obj);
            labels = Object.keys(obj);
            drawBar(datasCharts, labels);
        }
    });

}