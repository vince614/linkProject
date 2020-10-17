<?php

//Variable 
$isLogin = false;
$insertion = false;
$isHTTPS = 0;
$view = "all";

//Start sessions
session_start();

//includes
include '../includes/config.php';

//Si l'utilisateur est connecté 
if(isset($_SESSION['user'])) {
  if (!empty($_SESSION['user'])) {
    
    //Var connection 
	$username = $_SESSION['username'];
	$email = $_SESSION['email'];
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
      $ins = $bdd->prepare('INSERT INTO links_table (links_origin, owner_username, owner_email, title, isHTTPS, code, date_link) VALUES (?, ?, ?, ?, ?, ?, ?)');
      $ins->execute(array($origin_link, $username, $email, $title, $isHTTPS, $code_aleatoire, $time));

      //Redirection
      header('Location: ./');
      
    } 
  }
}

//Get code for charts 
if(isset($_GET['code'])) {
	if(!empty($_GET['code'])) {

		//Variables
		$code = $_GET['code'];

		//Verif 
		$verif = $bdd->prepare('SELECT * FROM links_table WHERE code = ?');
		$verif->execute(array($code));
		$verif_count = $verif->rowCount();

		if ($verif_count > 0){

			//Change view 
			$view = $_GET['code'];

		}

	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="shortcut icon" href="../assets/img/clypy.png" type="image/x-icon">
	<meta name="description"
		content="Clypy.me shorten your links and follow them to see their evulotion with statistical data">
	<meta name="author" content="Vince">
	<title>Dashboard - Clypy.me</title>
	<!-- CSS FILES -->
	<link rel="stylesheet" type="text/css" href="../assets/css/uikit.min.css">
	<link rel="stylesheet" type="text/css" href="css/dashboard.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">
</head>

<body>

	<!-- LOADER -->
	<div class="loader">
		<div class="uk-position-center">
			<h1 style="color: #fff" class="uk-text-large uk-text-center uk-text-bolder">Clypy.me</h1>
		</div>
		<div class="lds-ellipsis">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>
	</div>

	<!--HEADER-->
	<header id="top-head" class="uk-position-fixed">
		<div class="uk-container uk-container-expand uk-background-primary">
			<nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
				<div class="uk-navbar-left">
					<div class="uk-navbar-item uk-hidden@m">
						<a class="uk-logo" href="#"><img class="custom-logo" src="img/logo.png" alt=""></a>
					</div>
				</div>
				<div class="uk-navbar-right">
					<ul class="uk-navbar-nav">
						<li><a uk-toggle="target: #modal-toggle" data-uk-icon="icon:settings" title="Settings"
								data-uk-tooltip></a></li>
						<li><a href="../" data-uk-icon="icon: home" title="Home" data-uk-tooltip></a></li>
						<li><a href="../logout.php" data-uk-icon="icon:  sign-out" title="Sign Out" data-uk-tooltip></a>
						</li>
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
			<img class="custom-logo" src="img/logo.png" alt="">
		</div>

		<!-- Left Content Box Dark -->
		<?php include '../includes/user_info.php' ?>
		<hr class="uk-divider-icon">
		<div class="left-nav-wrap">
			<ul class="uk-nav uk-nav-default uk-nav-parent-icon" data-uk-nav>
				<li class="uk-nav-header">ACTIONS</li>
				<li><a href="#modal-full" uk-toggle><span data-uk-icon="icon:  plus-circle"
							class="uk-margin-small-right"></span>Create new link</a>
				<li class="uk-nav-header">LINKS</li>

				<!-- Include links nav -->
				<?php include '../includes/links_nav.php'; ?>

			</ul>
		</div>
		<div class="bar-bottom">
			<ul class="uk-subnav uk-flex uk-flex-center uk-child-width-1-4" data-uk-grid>
				<li>
					<a href="../" class="uk-icon-link" data-uk-icon="icon: home" title="Home" data-uk-tooltip></a>
				</li>
				<li>
					<a class="uk-icon-link" data-uk-icon="icon: settings" title="Settings"
						uk-toggle="target: #modal-toggle" data-uk-tooltip></a>
				</li>
				<li>
					<a href="../logout.php" class="uk-icon-link" data-uk-tooltip="Sign out"
						data-uk-icon="icon: sign-out"></a>
				</li>
			</ul>
		</div>
	</aside>
	<!-- /LEFT BAR -->
	<!-- CONTENT -->
	<div id="content" data-uk-height-viewport="expand: true">
		<div class="uk-container uk-container-expand">
			<!-- Header card -->
			<?php include '../includes/header.php' ?>

			<hr class="uk-divider-icon">
			<div>
				<h1 class="uk-text-large uk-text-uppercase uk-text-center uk-text-bold"><span
						uk-icon="chevron-down"></span> View of <?=$view ?> <span uk-icon="chevron-down"></span></h1>
			</div>
			<hr class="uk-divider-icon">
			<div>
				<h1 class="uk-text-large uk-text-uppercase uk-text-bold">Links Charts</h1>
			</div>
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
									<a href="#offcanvas-slide-area" class="uk-icon-link" title="Configuration"
										data-uk-tooltip data-uk-icon="icon: cog" uk-toggle></a>
									<a id="hide-show-area" class="uk-icon-link" title="Close" onclick="hideAreaChart()"
										data-uk-tooltip data-uk-icon="icon: close"></a>
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
									<a href="#offcanvas-slide-pie" class="uk-icon-link" title="Configuration"
										data-uk-tooltip data-uk-icon="icon: cog" uk-toggle></a>
									<a id="hide-show-pie" class="uk-icon-link" title="Close" onclick="hidePieChart()"
										data-uk-tooltip data-uk-icon="icon: close"></a>
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
									<a id="hide-pie" class="uk-icon-link" title="Close" data-uk-tooltip
										onclick="hidePie()" data-uk-icon="icon: close"></a>
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
					<div class="uk-card uk-card-default uk-card-small uk-card-hover uk-panel">
						<div class="uk-card-header">
							<div class="uk-grid uk-grid-small">
								<div class="uk-width-auto">
									<h4>Browser clicks <span class="uk-label">Total</span></h4>
								</div>
								<div class="uk-width-expand uk-text-right panel-icons">
									<a id="hide-bar" class="uk-icon-link" title="Close" data-uk-tooltip
										onclick="hideBar()" data-uk-icon="icon: close"></a>
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
			<hr class="uk-divider-icon">
			<div>
				<h1 class="uk-text-large uk-text-uppercase uk-text-bold">Links Table</h1>
			</div>
			<div class="uk-overflow-auto">
				<table class="uk-table uk-table-divider">
					<thead>
						<tr>
							<th>Title</th>
							<th>URL</th>
							<th>Protocol</th>
							<th>Link</th>
							<th>Owner</th>
							<th>Date</th>
							<th>Clicks</th>
						</tr>
					</thead>
					<tbody>
						<?php include '../includes/links_table.php' ?>
					</tbody>
				</table>
			</div>

			<footer class="uk-section uk-section-small uk-text-center">
				<hr class="uk-divider-icon">
				<p class="uk-text-small uk-text-center">Copyright &copy; Clypy.me 2019</p>
			</footer>
		</div>
	</div>
	<!-- /CONTENT -->
	<!-- OFFCANVAS -->
	<div id="offcanvas-nav" data-uk-offcanvas="flip: true; overlay: true">
		<div class="uk-offcanvas-bar uk-offcanvas-bar-animation uk-offcanvas-slide">
			<button class="uk-offcanvas-close uk-close uk-icon" type="button" data-uk-close></button>
			<?php include '../includes/user_info.php' ?>
			<hr class="uk-divider-icon">
			<ul class="uk-nav uk-nav-default uk-nav-parent-icon " data-uk-nav>
				<li class="uk-nav-header">ACTIONS</li>
				<li><a href="#modal-full" uk-toggle><span data-uk-icon="icon:  plus-circle"
							class="uk-margin-small-right"></span>Create new link</a>
				<li class="uk-nav-header">LINKS</li>

				<!-- Include links nav -->
				<?php include '../includes/links_nav_responsive.php'; ?>

			</ul>
		</div>
	</div>

	<div id="offcanvas-slide-area" uk-offcanvas>
		<div class="uk-offcanvas-bar">

			<ul class="uk-nav uk-nav-default uk-position-center">
				<li class="uk-nav-header">Overview clicks sort by:</li>
				<li><a class="uk-button uk-button-default uk-button-small uk-margin-small-top"
						onclick="chartArea('<?=$view ?>','day')">Day</a></li>
				<li><a class="uk-button uk-button-default uk-button-small uk-margin-small-top"
						onclick="chartArea('<?=$view ?>','week')">Week</a></li>
				<li><a class="uk-button uk-button-default uk-button-small uk-margin-small-top"
						onclick="chartArea('<?=$view ?>','month')">Month</a></li>
				<li><a class="uk-button uk-button-default uk-button-small uk-margin-small-top"
						onclick="chartArea('<?=$view ?>','year')">Year</a></li>
			</ul>

		</div>
	</div>

	<div id="offcanvas-slide-pie" uk-offcanvas>
		<div class="uk-offcanvas-bar">

			<ul class="uk-nav uk-nav-default uk-position-center">
				<li class="uk-nav-header">Devices clicks sort by:</li>
				<li><a class="uk-button uk-button-default uk-button-small uk-margin-small-top"
						onclick="chartPie('<?=$view ?>','day')">Day</a></li>
				<li><a class="uk-button uk-button-default uk-button-small uk-margin-small-top"
						onclick="chartPie('<?=$view ?>','week')">Week</a></li>
				<li><a class="uk-button uk-button-default uk-button-small uk-margin-small-top"
						onclick="chartPie('<?=$view ?>','month')">Month</a></li>
				<li><a class="uk-button uk-button-default uk-button-small uk-margin-small-top"
						onclick="chartPie('<?=$view ?>','year')">Year</a></li>
			</ul>

		</div>
	</div>

	<div id="modal-full" class="uk-modal-full uk-modal" uk-modal>
		<div class="uk-modal-dialog uk-flex uk-flex-center uk-flex-middle" uk-height-viewport>
			<button class="uk-modal-close-full" type="button" uk-close></button>
			<form class="uk-search uk-search-large" action="" method="POST">
				<p class="uk-text-center">Create your link shorted</p>
				<input class="uk-search-input uk-text-center" name="title" type="text" placeholder="Title" required>
				<hr class="uk-divider-icon">
				<input class="uk-search-input uk-text-center" name="url_origin" type="url" placeholder="Paste long url"
					required>
				<br />
				<br />
				<br />
				<button class="uk-button uk-button-default uk-button-small uk-align-center" type="submit">Create
					link</button>
			</form>
		</div>
	</div>

	<?php include '../includes/settings.php' ?>
	<!-- /OFFCANVAS -->

	<!-- JS FILES -->
	<script src="../assets/js/uikit.min.js"></script>
	<script src="../assets/js/uikit-icons.min.js"></script>
	<script src="../vendor/jquery/jquery.min.js"></script>
	<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="../vendor/chart.js/Chart.min.js"></script>
	<script src="../assets/js/sweetalert2@8.js"></script>
	<script src="js/chart-area.js"></script>
	<script src="js/chart-dog.js"></script>
	<script src="js/chart-pie.js"></script>
	<script src="js/chart-bar.js"></script>
	<script src="js/functions.js"></script>

	<!-- JS FONCTION INIT -->
	<script>
		//Loader
		window.addEventListener("load", function () {
			const loader = document.querySelector(".loader");
			loader.className += " hidden"; // class "loader hidden"
		});


		//Default req 
		chartArea('<?=$view ?>', 'day');
		chartBar('<?=$view ?>', 'day');
		chartsPie("<?=$view ?>");
		chartPie('<?=$view ?>', 'day');
	</script>
</body>

</html>