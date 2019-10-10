//Requete par default
chartArea('all', 'day');

function drawChart() {

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Kanit', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsChart,
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
                data: dataChart,
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


function chartArea(code, date) {

    //Show loader
    $("#loader-area").show();

    //Hide canvas
    $('#myAreaChart').hide();

    //Ajax 
    $.ajax({
            type: "POST",
            url: "../functions/chart_area.php",
            data: {
                code: code,
                date: date

            },
            success: function(data) {

                //Recupérer le fichier json en objet
                var obj = JSON.parse(data);
                console.log(obj);

                if (obj.date == 'day') {

                    //Hours array
                    let hours = [];

                    for (let i = 0; i < 24; i++) {

                        function timeConverter(UNIX_timestamp) {

                            var a = new Date(UNIX_timestamp * 1000);
                            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            var year = a.getFullYear();
                            var month = months[a.getMonth()];
                            var date = a.getDate();
                            var hour = a.getHours();
                            var min = a.getMinutes() < 10 ? '0' + a.getMinutes() : a.getMinutes();
                            var sec = a.getSeconds() < 10 ? '0' + a.getSeconds() : a.getSeconds();
                            var time = hour + ':' + min;
                            return time;

                        }

                        //Push 
                        hours.push(timeConverter(obj.time[i]));

                    }

                    //Labels 
                    labelsChart = hours;

                    //Datas 
                    dataChart = obj.hours;

                    //Destroy chart is exist 
                    if(typeof myLineChart !== 'undefined') {

                        myLineChart.destroy();

                    }

                    //DrawChart
                    drawChart();

                    //Change h6 
                    $('#chart-area-h6').text("Overview clicks [Day]");

                } else if (obj.date == 'week') {

                    //Array Week 
                    let days = [];

                    for (let i = 0; i < 7; i++) {

                        function timeConverter(UNIX_timestamp) {
                            var a = new Date(UNIX_timestamp * 1000);
                            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            var year = a.getFullYear();
                            var month = months[a.getMonth()];
                            var date = a.getDate();
                            var hour = a.getHours();
                            var min = a.getMinutes();
                            var sec = a.getSeconds();
                            var time = date + ' ' + month + ' ' + year;
                            return time;
                        }

                        //Push
                        days.push(timeConverter(obj.time[i]));

                    }

                    //Labels 
                    labelsChart = days;

                    //Datas 
                    dataChart = obj.days;

                    if(typeof myLineChart !== 'undefined') {

                        myLineChart.destroy();

                    }

                    //DrawChart
                    drawChart();

                    //Change h6 
                    $('#chart-area-h6').text("Overview clicks [Week]");

                } else if (obj.date == 'month') {

                    //Array Week 
                    let days = [];

                    for (let i = 0; i < 30; i++) {

                        function timeConverter(UNIX_timestamp) {
                            var a = new Date(UNIX_timestamp * 1000);
                            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            var year = a.getFullYear();
                            var month = months[a.getMonth()];
                            var date = a.getDate();
                            var hour = a.getHours();
                            var min = a.getMinutes();
                            var sec = a.getSeconds();
                            var time = date + ' ' + month + ' ' + year;
                            return time;
                        }

                        //Push
                        days.push(timeConverter(obj.time[i]));

                    }

                    //Labels 
                    labelsChart = days;

                    //Datas 
                    dataChart = obj.month;

                    if(typeof myLineChart !== 'undefined') {

                        myLineChart.destroy();

                    }

                    //DrawChart
                    drawChart();

                    //Change h6 
                    $('#chart-area-h6').text("Overview clicks [Month]");

                }else if(obj.date == 'year'){

                    //Array Week 
                    let month = [];

                    for (let i = 0; i < 12; i++) {

                        function timeConverter(UNIX_timestamp) {
                            var a = new Date(UNIX_timestamp * 1000);
                            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            var year = a.getFullYear();
                            var month = months[a.getMonth()];
                            var date = a.getDate();
                            var hour = a.getHours();
                            var min = a.getMinutes();
                            var sec = a.getSeconds();
                            var time = date + ' ' + month + ' ' + year;
                            return time;
                        }

                        //Push
                        month.push(timeConverter(obj.time[i]));

                    }

                    //Labels 
                    labelsChart = month;

                    //Datas 
                    dataChart = obj.year;

                    if(typeof myLineChart !== 'undefined') {

                        myLineChart.destroy();

                    }

                    //DrawChart
                    drawChart();

                    //Change h6 
                    $('#chart-area-h6').text("Overview clicks [Year]");


                }

        }
    });

}
