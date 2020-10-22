/**
 * Draw chart pie
 *
 * @param dataCharts
 */
function drawChartPie(dataCharts) {
    let ctx = document.getElementById("myPieChart");
    pieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Phone", "Tablet", "Desktop"],
            datasets: [{
                data: dataCharts,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: true,
                caretPadding: 10,
            },
            legend: {
                display: true
            },
            cutoutPercentage: 80,
        },
    });

    //Hide loader 
    $("#loader-pie").hide();

    //Show canvas 
    $("#myPieChart").show();
}

/**
 * Chart pie
 *
 * @param code
 * @param date
 */
function chartPie(code, date) {
    $("#loader-pie").show();
    $('#myPieChart').hide();

    let dataCharts;

    $.ajax({
        type: "POST",
        url: hostUrl + "/charts",
        data: {
            code: code,
            date: date,
            chart: 'deviceClick'

        },
        success: function (data) {
            let obj = JSON.parse(data);

            if (obj.date == 'day') {
                dataCharts = obj.pie;
                // Destroy chart is exist
                if (typeof pieChart !== 'undefined') {
                    pieChart.destroy();
                }
                // Time
                let time = timeConverter(obj.time);
                let timeNow = timeConverter(obj.timeNow);
                // DrawChart
                drawChartPie(dataCharts);
                $('#chart-pie-badge').text("day");

            } else if (obj.date == 'week') {
                dataCharts = obj.pie;

                // Destroy chart is exist
                if (typeof pieChart !== 'undefined') {
                    pieChart.destroy();
                }
                //Time
                let time = timeConverter(obj.time);
                let timeNow = timeConverter(obj.timeNow);
                //DrawChart
                drawChartPie(dataCharts);
                $('#chart-pie-badge').text("week");

            } else if (obj.date == 'month') {
                dataCharts = obj.pie;

                // Destroy chart is exist
                if (typeof pieChart !== 'undefined') {
                    pieChart.destroy();
                }
                //Time
                let time = timeConverter(obj.time);
                let timeNow = timeConverter(obj.timeNow);
                //DrawChart
                drawChartPie(dataCharts);
                $('#chart-pie-badge').text("month");

            } else if (obj.date == 'year') {
                dataCharts = obj.pie;

                // Destroy chart is exist
                if (typeof pieChart !== 'undefined') {
                    pieChart.destroy();
                }
                //Time
                let time = timeConverter(obj.time);
                let timeNow = timeConverter(obj.timeNow);
                //DrawChart
                drawChartPie(dataCharts);
                $('#chart-pie-badge').text("year");
            }
        }
    });
}