

function drawPie() {

    // Pie Chart Example
    var ctx = document.getElementById("PieChart");
    myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: datasCharts,
                backgroundColor: ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6','#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D','#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A'],
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
            }
        },
    });

    //Hide loader 
    $("#loader-pie2").hide();

    //Show canvas 
    $("#PieChart").show();

}

function chartsPie(code) {

    //Show loader
    $("#loader-pie2").show();

    //Hide canvas
    $('#PieChart').hide();

    //Ajax 
    $.ajax({
            type: "POST",
            url: "../functions/chart_pie2.php",
            data: {
                code: code

            },
            success: function(data) {

                //Recupérer le fichier json en objet
                var obj = JSON.parse(data);
                console.log(obj);

                //Datas 
                datasCharts = Object.values(obj.country);

                //Labels 
                labels = Object.keys(obj.country);

                drawPie();

            }
    });

}