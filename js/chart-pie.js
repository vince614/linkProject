
chartPie('all', 'day');

function timeConverter(UNIX_timestamp) {
    var a = new Date(UNIX_timestamp * 1000);
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes() < 10 ? '0' + a.getMinutes() : a.getMinutes();
    var sec = a.getSeconds() < 10 ? '0' + a.getSeconds() : a.getSeconds();
    var time = date + ' ' + month + ' ' + year;
    return time;
}


function drawChartPie() {

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart Example
    var ctx = document.getElementById("myPieChart");
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


function chartPie(code, date) {

    //Show loader
    $("#loader-pie").show();

    //Hide canvas
    $('#myPieChart').hide();

    //Ajax 
    $.ajax({
            type: "POST",
            url: "./functions/chart_pie.php",
            data: {
                code: code,
                date: date

            },
            success: function(data) {

                //Recupérer le fichier json en objet
                var obj = JSON.parse(data);
                console.log(obj);

                if (obj.date == 'day') {

                    //Datas
                    dataCharts = obj.pie;

                    //Destroy chart is exist 
                    if(typeof pieChart !== 'undefined') {

                        pieChart.destroy();

                    }

                    //Time
                    var time = timeConverter(obj.time);
                    var timeNow = timeConverter(obj.timeNow);

                    //DrawChart
                    drawChartPie();

                    //Change h6 
                    $('#chart-pie-h6').text("Devices clicks [Day]");
                    $('#time-pie').html(timeNow + ' <b>to</b> ' + time);

                } else if (obj.date == 'week') {

                    //Datas
                    dataCharts = obj.pie;

                    //Destroy chart is exist 
                    if(typeof pieChart !== 'undefined') {

                        pieChart.destroy();

                    }

                    //Time
                    var time = timeConverter(obj.time);
                    var timeNow = timeConverter(obj.timeNow);

                    //DrawChart
                    drawChartPie();

                    //Change h6 
                    $('#chart-pie-h6').text("Devices clicks [Week]");
                    $('#time-pie').html(timeNow + ' <b>to</b> ' + time);


                } else if (obj.date == 'month') {

                    //Datas
                    dataCharts = obj.pie;

                    //Destroy chart is exist 
                    if(typeof pieChart !== 'undefined') {

                        pieChart.destroy();

                    }

                    //Time
                    var time = timeConverter(obj.time);
                    var timeNow = timeConverter(obj.timeNow);

                    //DrawChart
                    drawChartPie();

                    //Change h6 
                    $('#chart-pie-h6').text("Devices clicks [Month]");
                    $('#time-pie').html(timeNow + ' <b>to</b> ' + time);

                }else if(obj.date == 'year'){

                    //Datas
                    dataCharts = obj.pie;

                    //Destroy chart is exist 
                    if(typeof pieChart !== 'undefined') {

                        pieChart.destroy();

                    }

                    //Time
                    var time = timeConverter(obj.time);
                    var timeNow = timeConverter(obj.timeNow);

                    //DrawChart
                    drawChartPie();

                    //Change h6 
                    $('#chart-pie-h6').text("Devices clicks [Year]");
                    $('#time-pie').html(timeNow + ' <b>to</b> ' + time);


                }

        }
    });

}