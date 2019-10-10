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
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Linky - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../assets/css/kanit-css.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- UIkit CSS -->
  <link rel="stylesheet" href="../assets/css/uikit.min.css" />

  <!-- UIkit JS -->
  <script src="../assets/js/uikit.min.js"></script>
  <script src="../assets/js/uikit-icons.min.js"></script>

  <!-- SweetAlert -->
  <script src="../assets/js/sweetalert2@8.js"></script>

  <!-- Custom styles for this template-->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

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

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input id="search-data" type="text" class="form-control bg-light border-0 small"
                placeholder="Search for link ..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>


          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                      aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <?php include '../includes/nav_user_info.php'; ?>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">DataCharts</h1>
            <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="#modal-full" uk-toggle><i
                class="fas fa-link fa-sm text-white-50"></i> Create new link</a>
          </div>


          <!-- Content Row -->

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <a onclick="hideAreaChart()">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 id="chart-area-h6" class="m-0 font-weight-bold text-primary"></h6>
                    <div class="dropdown no-arrow">
                      <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Sort by :</div>
                        <button class="dropdown-item" onclick="chartArea('all','day')">Day</button>
                        <button class="dropdown-item" onclick="chartArea('all','week')">Week</button>
                        <button class="dropdown-item" onclick="chartArea('all','month')">Month</button>
                        <button class="dropdown-item" onclick="chartArea('all','year')">Year</button>
                        <div class="dropdown-divider"></div>
                        <button id="hide-show-area" class="dropdown-item" onclick="hideAreaChart()">Hide</button>
                      </div>
                    </div>
                  </div>
                </a>
                <!-- Card Body -->
                <div id="card-area-chart" class="card-body">
                  <div class="chart-area">

                    <!-- Loading -->
                    <div id="loader-area" class="text-center" style="height: 100%">
                      <div class="spinner-border" style="position: relative; top: 40%" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </div>

                    <!-- Charts -->
                    <canvas id="myAreaChart"></canvas>

                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <a onclick="hidePieChart()">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 id="chart-pie-h6" class="m-0 font-weight-bold text-primary"></h6>
                    <div class="dropdown no-arrow">
                      <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Sort by :</div>
                        <button class="dropdown-item" onclick="chartPie('all','day')">Day</button>
                        <button class="dropdown-item" onclick="chartPie('all','week')">Week</button>
                        <button class="dropdown-item" onclick="chartPie('all','month')">Month</button>
                        <button class="dropdown-item" onclick="chartPie('all','year')">Year</button>
                        <div class="dropdown-divider"></div>
                        <button id="hide-show-pie" class="dropdown-item" onclick="hidePieChart()">Hide</button>
                      </div>
                    </div>
                  </div>
                </a>
                <!-- Card Body -->
                <div id="card-pie-chart" class="card-body">
                  <div class="chart-pie pt-4 pb-2">

                    <!-- Loading -->
                    <div id="loader-pie" class="text-center" style="height: 100%">
                      <div class="spinner-border" style="position: relative; top: 40%" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </div>

                    <canvas id="myPieChart"></canvas>


                  </div>
                  <div class="mt-4 text-center small">
                    <span id="time-pie" class="mr-2">

                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Links table [All]</h1>
          </div>

          <!-- Dropdown Card Example -->
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <a onclick="hideDataTable()">
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">DataTable</h6>
                <div class="dropdown no-arrow">
                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Action:</div>
                    <a class="dropdown-item" id="hide-show-datatable" onclick="hideDataTable()">Hide</a>

                  </div>
                </div>
              </div>
            </a>
            <!-- Card Body -->
            <div id="card-datatable" class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Url</th>
                      <th>Protocol</th>
                      <th>Linky</th>
                      <th>Owner</th>
                      <th>Start Date</th>
                      <th>Clicks</th>
                      <th>Manage</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Include links_table.php -->
                    <?php include '../includes/links_table.php'; ?>
                    <!-- Fin include -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>


        <!-- End of div  -->
        </div>

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Linky 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <?php include '../includes/logout_modal.php'; ?>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../assets/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/chart.js/Chart.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- require Charts -->
  <script src="../assets/js/chart-area.js"></script>
  <script src="../assets/js/chart-pie.js"></script>

  <!-- Page level custom scripts -->
  <script src="../assets/js/demo/datatables-demo.js"></script>
  <script src="../assets/js/functions.js"></script>


</body>

</html>