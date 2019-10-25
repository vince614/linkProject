<?php 

//Start sessions
session_start();

//includes
include '../includes/config.php';

//Variable 
$isLogin = false;

//Verif si la personne est connecté 
if(isset($_SESSION['username'])) {

  header('Location: ../dashboard/');

}


if(isset($_POST['login'], $_POST['pass'])) {
	if(!empty($_POST['login']) && !empty($_POST['pass'])) {

		//Variables 
		$login = strtolower($_POST['login']);
		$password = sha1($_POST['pass']);

		//Check login
		$check_login = $bdd->prepare('SELECT * FROM account WHERE username = ? OR mail = ?');
		$check_login->execute(array($login, $login));
		$check_login_count = $check_login->rowCount();

		//Si il existe un compte 
		if ($check_login_count > 0){

			if($mdp = $check_login->fetch()) {

				//Variable
				$password_bdd = $mdp['pass'];
        $username = $mdp['username'];
        $mail = $mdp['mail'];
        $picture = $mdp['picture'];

				if ($password_bdd == $password) {

					//Cree une variable session
          $_SESSION['username'] = $username;
          $_SESSION['email'] = $mail;
          $_SESSION['picture'] = $picture;

					//Redirect
					header('Location: ../dashboard');

				}else {

					$err = "Password don't match";

				}


			}

		}else {

			$err = "Username or email don't exist !";

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
  <meta name="description" content="Clypy.me shorten your links and follow them to see their evulotion with statistical data">
  <meta name="author" content="Vince">

  <title>Login - Clypy.me</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <form class="user" action="" method="POST">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="login" aria-describedby="emailHelp" placeholder="Enter Email Address or Username" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="pass" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <!-- <label class="custom-control-label" for="customCheck">Remember Me</label> -->
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                    <?php if(isset($err)) { echo '<center><p style="color:red">'.$err.'</p></center>'; } ?>
                    <hr>
                    <a href="google.php" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="facebook.php" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="../forgot-password">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="../register">Create an Account!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../assets/js/sb-admin-2.min.js"></script>

  <div id="conveythis-wrapper-main"><a href="https://www.translation-services-usa.com/"
            class="conveythis-no-translate" title="translation services">translation services</a></div>
    <script src="//cdn.conveythis.com/javascriptPlugin/38/conveythis.js"></script>
    <script src="//cdn.conveythis.com/javascriptPlugin/38/translate.js"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function (e) {
            conveythis.init({
                icon: "rect",
                text: "full-text",
                positionTop: null,
                positionBottom: 0,
                positionLeft: null,
                positionRight: 0,
                change: {},
                languages: [{
                    "id": 727,
                    "active": false
                }, {
                    "id": 703,
                    "active": true
                }],
                api_key: "pub_ecfef164f9a6a8efa8e49a5769f00942",
                source_language_id: 703,
                auto_translate: 0,
                hide_conveythis_logo: 0,

            });
        });
    </script>

</body>

</html>
