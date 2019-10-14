<?php

//Variable 
$isLogin = false;
$insertion = false;
$isHTTPS = 0;

//Start sessions
session_start();

//includes
include '../includes/config.php';

//Si l'utilisateur est connecté 
if(isset($_SESSION['username'])) {
  if (!empty($_SESSION['username'])) {
    
    //Var connection 
    $username = $_SESSION['username'];
    $isLogin = true;

  }
}

//Redirection si il n'es pas connecté 
if ($isLogin == false) {
  header('Location: ../login');
}

//Create new link 
if (isset($_POST['url_origin'], $_POST['title'])) {
  if (!empty($_POST['url_origin']) && !empty($_POST['title'])){

    //Variables 
    $origin_link = $_POST['url_origin'];
    $title = $_POST['title'];
    $verif = 1;
    $time = time();

    //Si l'url est valide 
    if (filter_var($origin_link, FILTER_VALIDATE_URL) !== FALSE) {

      //Http
      $HTTPS =  explode(':', $origin_link);
      
      //Connaitre la nature du lien
      if ($HTTPS[0] == 'https') {
        $isHTTPS = 1;
      }
      
      //Caractères du code 
      $characts = 'abcdefghijklmnopqrstuvwxyz';
      $characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $characts .= '1234567890';
      $code_aleatoire = '';

      //Nombre de caractère
      $charactsCount = 5;

      //Si il le code existe déjà recommencé
      while($verif !== 0) {

        //Géneration du caractère
        for($i=0; $i < $charactsCount; $i++) {
          $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1);
        }

        //Les codes dans la bdd
        $codes = $bdd->prepare('SELECT * FROM links_table WHERE code = ?');
        $codes->execute(array($code_aleatoire));
        $verif = $codes->rowCount();

      }

      //Infos account

      //Insertion à la bdd 
      $ins = $bdd->prepare('INSERT INTO links_table (links_origin, owner_username, title, isHTTPS, code, date_link) VALUES (?, ?, ?, ?, ?, ?)');
      $ins->execute(array($origin_link, $username, $title, $isHTTPS, $code_aleatoire, $time));

      //Redirection
      header('Location: ./');
      
    } 
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dashboard - UIkit 3 KickOff</title>
	<!-- CSS FILES -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/css/uikit.min.css">
	<link rel="stylesheet" type="text/css" href="css/dashboard.css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>

<body>

	<!--HEADER-->
	<header id="top-head" class="uk-position-fixed">
		<div class="uk-container uk-container-expand uk-background-primary">
			<nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
				<div class="uk-navbar-left">
					<div class="uk-navbar-item uk-hidden@m">
						<a class="uk-logo" href="#"><img class="custom-logo" src="img/dashboard-logo-white.svg"
								alt=""></a>
					</div>
					<ul class="uk-navbar-nav uk-visible@m">
						<li><a href="#">Accounts</a></li>
						<li>
							<a href="#">Settings <span data-uk-icon="icon: triangle-down"></span></a>
							<div class="uk-navbar-dropdown">
								<ul class="uk-nav uk-navbar-dropdown-nav">
									<li class="uk-nav-header">YOUR ACCOUNT</li>
									<li><a href="#"><span data-uk-icon="icon: info"></span> Summary</a></li>
									<li><a href="#"><span data-uk-icon="icon: refresh"></span> Edit</a></li>
									<li><a href="#"><span data-uk-icon="icon: settings"></span> Configuration</a></li>
									<li class="uk-nav-divider"></li>
									<li><a href="#"><span data-uk-icon="icon: image"></span> Your Data</a></li>
									<li class="uk-nav-divider"></li>
									<li><a href="../logout.php"><span data-uk-icon="icon: sign-out"></span> Logout</a></li>
								</ul>
							</div>
						</li>
					</ul>
					<div class="uk-navbar-item uk-visible@s">
						<form action="dashboard.html" class="uk-search uk-search-default">
							<span data-uk-search-icon></span>
							<input class="uk-search-input search-field" type="search" placeholder="Search">
						</form>
					</div>
				</div>
				<div class="uk-navbar-right">
					<ul class="uk-navbar-nav">
						<li><a href="#" data-uk-icon="icon:user" title="Your profile" data-uk-tooltip></a></li>
						<li><a href="#" data-uk-icon="icon: settings" title="Settings" data-uk-tooltip></a></li>
						<li><a href="../logout.php" data-uk-icon="icon:  sign-out" title="Sign Out" data-uk-tooltip></a></li>
						<li><a class="uk-navbar-toggle" data-uk-toggle data-uk-navbar-toggle-icon href="#offcanvas-nav"
								title="Offcanvas" data-uk-tooltip></a></li>
					</ul>
				</div>
			</nav>
		</div>
	</header>
	<!--/HEADER-->
	<!-- LEFT BAR -->
	<aside id="left-col" class="uk-light uk-visible@m">
		<div class="left-logo uk-flex uk-flex-middle">
			<img class="custom-logo" src="img/dashboard-logo.svg" alt="">
		</div>
		
		<!-- Left Content Box Dark -->
		<?php include '../includes/user_info.php' ?>

		<div class="left-nav-wrap">
			<ul class="uk-nav uk-nav-default uk-nav-parent-icon" data-uk-nav>
				<li class="uk-nav-header">ACTIONS</li>
				<li><a href="#modal-full" uk-toggle><span data-uk-icon="icon: link" class="uk-margin-small-right"></span>Create new link</a>
			</ul>
			<div class="left-content-box uk-margin-top">

				<h5>Daily Reports</h5>
				<div>
					<span class="uk-text-small">Traffic <small>(+50)</small></span>
					<progress class="uk-progress" value="50" max="100"></progress>
				</div>
				<div>
					<span class="uk-text-small">Income <small>(+78)</small></span>
					<progress class="uk-progress success" value="78" max="100"></progress>
				</div>
				<div>
					<span class="uk-text-small">Feedback <small>(-12)</small></span>
					<progress class="uk-progress warning" value="12" max="100"></progress>
				</div>

			</div>

		</div>
		<div class="bar-bottom">
			<ul class="uk-subnav uk-flex uk-flex-center uk-child-width-1-5" data-uk-grid>
				<li>
					<a href="#" class="uk-icon-link" data-uk-icon="icon: home" title="Home" data-uk-tooltip></a>
				</li>
				<li>
					<a href="#" class="uk-icon-link" data-uk-icon="icon: settings" title="Settings" data-uk-tooltip></a>
				</li>
				<li>
					<a href="#" class="uk-icon-link" data-uk-icon="icon: social" title="Social" data-uk-tooltip></a>
				</li>

				<li>
					<a href="../logout.php" class="uk-icon-link" data-uk-tooltip="Sign out" data-uk-icon="icon: sign-out"></a>
				</li>
			</ul>
		</div>
	</aside>
	<!-- /LEFT BAR -->
	<!-- CONTENT -->
	<div id="content" data-uk-height-viewport="expand: true">
		<div class="uk-container uk-container-expand">
			<div class="uk-grid uk-grid-divider uk-grid-medium uk-child-width-1-2 uk-child-width-1-4@l uk-child-width-1-5@xl"
				data-uk-grid>
				<div>
					<span class="uk-text-small"><span data-uk-icon="icon:users"
							class="uk-margin-small-right uk-text-primary"></span>New Users</span>
					<h1 class="uk-heading-primary uk-margin-remove  uk-text-primary">2.134</h1>
					<div class="uk-text-small">
						<span class="uk-text-success" data-uk-icon="icon: triangle-up">15%</span> more than last week.
					</div>
				</div>
				<div>

					<span class="uk-text-small"><span data-uk-icon="icon:social"
							class="uk-margin-small-right uk-text-primary"></span>Social Media</span>
					<h1 class="uk-heading-primary uk-margin-remove uk-text-primary">8.490</h1>
					<div class="uk-text-small">
						<span class="uk-text-warning" data-uk-icon="icon: triangle-down">-15%</span> less than last
						week.
					</div>

				</div>
				<div>

					<span class="uk-text-small"><span data-uk-icon="icon:clock"
							class="uk-margin-small-right uk-text-primary"></span>Traffic hours</span>
					<h1 class="uk-heading-primary uk-margin-remove uk-text-primary">12.00<small
							class="uk-text-small">PM</small></h1>
					<div class="uk-text-small">
						<span class="uk-text-success" data-uk-icon="icon: triangle-up"> 19%</span> more than last week.
					</div>

				</div>
				<div>

					<span class="uk-text-small"><span data-uk-icon="icon:search"
							class="uk-margin-small-right uk-text-primary"></span>Week Search</span>
					<h1 class="uk-heading-primary uk-margin-remove uk-text-primary">9.543</h1>
					<div class="uk-text-small">
						<span class="uk-text-danger" data-uk-icon="icon: triangle-down"> -23%</span> less than last
						week.
					</div>

				</div>
				<div class="uk-visible@xl">
					<span class="uk-text-small"><span data-uk-icon="icon:users"
							class="uk-margin-small-right uk-text-primary"></span>Lorem ipsum</span>
					<h1 class="uk-heading-primary uk-margin-remove uk-text-primary">5.284</h1>
					<div class="uk-text-small">
						<span class="uk-text-success" data-uk-icon="icon: triangle-up"> 7%</span> more than last week.
					</div>
				</div>
			</div>
			<hr>
			<div class="uk-grid uk-grid-medium" data-uk-grid>

				<!-- panel -->
				<div class="uk-width-2-3@l">
					<div class="uk-card uk-card-default uk-card-small uk-card-hover">
						<div class="uk-card-header">
							<div class="uk-grid uk-grid-small">
								<div class="uk-width-auto">
									<h4>Overview clicks <span id="chart-area-badge" class="uk-label">day</span></h4>
								</div>
								<div class="uk-width-expand uk-text-right panel-icons">
									<a href="#" class="uk-icon-link" title="Move" data-uk-tooltip
										data-uk-icon="icon: move"></a>
									<a href="#offcanvas-slide-area" class="uk-icon-link" title="Configuration" data-uk-tooltip 
									data-uk-icon="icon: cog" uk-toggle></a>
									<a id="hide-show-area" class="uk-icon-link" title="Close" onclick="hideAreaChart()" data-uk-tooltip
										data-uk-icon="icon: close"></a>
								</div>
							</div>
						</div>
						<div id="card-area-chart" class="uk-card-body">
							<div class="chart-container">
								<div id="loader-area" style="height: 100%">
									<div class="uk-position-center" role="status">
										<div uk-spinner></div>
									</div>
								</div>
								<canvas id="myAreaChart"></canvas>
							</div>
						</div>
					</div>
				</div>
				<!-- /panel -->
				<!-- panel -->
				<div class="uk-width-1-3@l">
					<div class="uk-card uk-card-default uk-card-small uk-card-hover">
						<div class="uk-card-header">
							<div class="uk-grid uk-grid-small">
								<div class="uk-width-auto">
									<h4>Devices clicks <span id="chart-pie-badge" class="uk-label">day</span></h4>
								</div>
								<div class="uk-width-expand uk-text-right panel-icons">
									<a href="#" class="uk-icon-link" title="Move" data-uk-tooltip
										data-uk-icon="icon: move"></a>
									<a href="#offcanvas-slide-pie" class="uk-icon-link" title="Configuration" data-uk-tooltip
										data-uk-icon="icon: cog" uk-toggle></a>
									<a id="hide-show-pie" class="uk-icon-link" title="Close" onclick="hidePieChart()" data-uk-tooltip
										data-uk-icon="icon: close"></a>
								</div>
							</div>
						</div>
						<div id="card-pie-chart" class="uk-card-body">
							<div class="chart-container">
								<div id="loader-pie" style="height: 100%">
									<div class="uk-position-center" role="status">
										<div uk-spinner></div>
									</div>
								</div>
								<canvas id="myPieChart"></canvas>
							</div>
						</div>
					</div>
				</div>
				<!-- /panel -->
				<!-- panel -->
				<div class="uk-width-1-1 uk-width-1-3@l uk-width-1-3@xl">
					<div class="uk-card uk-card-default uk-card-small uk-card-hover">
						<div class="uk-card-header">
							<div class="uk-grid uk-grid-small">
								<div class="uk-width-auto">
									<h4>Location clicks <span class="uk-label">Total</span></h4>
								</div>
								<div class="uk-width-expand uk-text-right panel-icons">
									<a href="#" class="uk-icon-link" title="Move" data-uk-tooltip
										data-uk-icon="icon: move"></a>
									<a id="hide-pie" class="uk-icon-link" title="Close" data-uk-tooltip onclick="hidePie()"
										data-uk-icon="icon: close"></a>
								</div>
							</div>
						</div>
						<div id="card-pie" class="uk-card-body">
							<div class="chart-container">
								<div id="loader-pie2" style="height: 100%">
									<div class="uk-position-center" role="status">
										<div uk-spinner></div>
									</div>
								</div>
								<canvas id="PieChart"></canvas>
							</div>
						</div>
					</div>
				</div>
				<!-- /panel -->
				<!-- panel -->
				<div class="uk-width-1-2@s uk-width-1-3@l uk-width-2-3@xl">
					<div class="uk-card uk-card-default uk-card-small uk-card-hover">
						<div class="uk-card-header">
							<div class="uk-grid uk-grid-small">
								<div class="uk-width-auto">
									<h4>Browser clicks <span class="uk-label">Total</span></h4>
								</div>
								<div class="uk-width-expand uk-text-right panel-icons">
									<a href="#" class="uk-icon-link" title="Move" data-uk-tooltip
										data-uk-icon="icon: move"></a>
									<a id="hide-bar" class="uk-icon-link" title="Close" data-uk-tooltip onclick="hideBar()"
										data-uk-icon="icon: close"></a>
								</div>
							</div>
						</div>
						<div id="card-bar" class="uk-card-body">
							<div class="chart-container">
								<div id="loader-bar" style="height: 100%">
									<div class="uk-position-center" role="status">
										<div uk-spinner></div>
									</div>
								</div>
								<canvas id="myBarChart"></canvas>
							</div>
						</div>
					</div>
				</div>
				<!-- /panel -->
			</div>
			<footer class="uk-section uk-section-small uk-text-center">
				<hr>
				<p class="uk-text-small uk-text-center">Copyright 2019 - <a
						href="https://github.com/zzseba78/Kick-Off">Created by KickOff</a> | Built with <a
						href="http://getuikit.com" title="Visit UIkit 3 site" target="_blank" data-uk-tooltip><span
							data-uk-icon="uikit"></span></a> </p>
			</footer>
		</div>
	</div>
	<!-- /CONTENT -->
	<!-- OFFCANVAS -->
	<div id="offcanvas-nav" data-uk-offcanvas="flip: true; overlay: true">
		<div class="uk-offcanvas-bar uk-offcanvas-bar-animation uk-offcanvas-slide">
			<button class="uk-offcanvas-close uk-close uk-icon" type="button" data-uk-close></button>
			<ul class="uk-nav uk-nav-default">
				<li class="uk-active"><a href="#">Active</a></li>
				<li class="uk-parent">
					<a href="#">Parent</a>
					<ul class="uk-nav-sub">
						<li><a href="#">Sub item</a></li>
						<li><a href="#">Sub item</a></li>
					</ul>
				</li>
				<li class="uk-nav-header">Header</li>
				<li><a href="#js-options"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: table"></span>
						Item</a></li>
				<li><a href="#"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: thumbnails"></span>
						Item</a></li>
				<li class="uk-nav-divider"></li>
				<li><a href="#"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: trash"></span> Item</a>
				</li>
			</ul>
			<h3>Title</h3>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
				dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
				ex ea commodo consequat.</p>
		</div>
	</div>

	<div id="offcanvas-slide-area" uk-offcanvas>
		<div class="uk-offcanvas-bar">

			<ul class="uk-nav uk-nav-default">
				<li class="uk-nav-header">Overview clicks sort by:</li>
				<li><a class="dropdown-item" onclick="chartArea('all','day')">Day</a></li>
                <li><a class="dropdown-item" onclick="chartArea('all','week')">Week</a></li>
                <li><a class="dropdown-item" onclick="chartArea('all','month')">Month</a></li>
                <li><a class="dropdown-item" onclick="chartArea('all','year')">Year</a></li>
			</ul>

		</div>
	</div>

	<div id="offcanvas-slide-pie" uk-offcanvas>
		<div class="uk-offcanvas-bar">

			<ul class="uk-nav uk-nav-default">
				<li class="uk-nav-header">Devices clicks sort by:</li>
				<li><a class="dropdown-item" onclick="chartPie('all','day')">Day</a></li>
                <li><a class="dropdown-item" onclick="chartPie('all','week')">Week</a></li>
                <li><a class="dropdown-item" onclick="chartPie('all','month')">Month</a></li>
                <li><a class="dropdown-item" onclick="chartPie('all','year')">Year</a></li>
			</ul>

		</div>
	</div>

	<div id="modal-full" class="uk-modal-full uk-modal" uk-modal>
    <div class="uk-modal-dialog uk-flex uk-flex-center uk-flex-middle" uk-height-viewport>
      <button class="uk-modal-close-full" type="button" uk-close></button>
      <form class="uk-search uk-search-large" action="" method="POST">
        <p class="uk-text-center">Create your link shorted</p>
        <input class="uk-search-input uk-text-center" name="title" type="text" placeholder="Title" required>
        <hr>
        <input class="uk-search-input uk-text-center" name="url_origin" type="url" placeholder="Paste long url"
          required>
        <button style="display:none" type="submit"></button>
      </form>
    </div>
  </div>
	<!-- /OFFCANVAS -->

	<!-- JS FILES -->
	<script src="../assets/js/uikit.min.js"></script>
  	<script src="../assets/js/uikit-icons.min.js"></script>
	<script src="../vendor/jquery/jquery.min.js"></script>
	<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="../vendor/chart.js/Chart.min.js"></script>
	<script src="js/chart-area.js"></script>
	<script src="js/chart-dog.js"></script>
	<script src="js/chart-pie.js"></script>
	<script src="js/chart-bar.js"></script>
	<script src="js/functions.js"></script>
</body>

</html>