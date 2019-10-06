<?php 

//Requetes click count [desktop]
$req_click_desktop = $bdd->prepare('SELECT COUNT(*) AS desktopCount FROM clicks WHERE owner_username = ? AND isDesktop = ?');
$req_click_desktop->execute(array($username, 1));
$data_click_desktop = $req_click_desktop->fetch();
$click_desktop_count = $data_click_desktop['desktopCount'];

//Requetes click count [mobile]
$req_click_mobile = $bdd->prepare('SELECT COUNT(*) AS mobileCount FROM clicks WHERE owner_username = ? AND isPhone = ?');
$req_click_mobile->execute(array($username, 1));
$data_click_mobile = $req_click_mobile->fetch();
$click_mobile_count = $data_click_mobile['mobileCount'];

//Requetes click count [tablet]
$req_click_tablet = $bdd->prepare('SELECT COUNT(*) AS tabletCount FROM clicks WHERE owner_username = ? AND isTablet = ?');
$req_click_tablet->execute(array($username, 1));
$data_click_tablet = $req_click_tablet->fetch();
$click_tablet_count = $data_click_tablet['tabletCount'];



?>

<script>

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Desktop", "Phone", "Tablet"],
    datasets: [{
      data: [<?=$click_desktop_count ?>, <?=$click_mobile_count ?>, <?=$click_tablet_count ?>],
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

</script>
