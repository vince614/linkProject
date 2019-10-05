<?php 

//Start session 
session_start();

//includes 
include './includes/config.php';

//Check POST form
if (isset($_POST['username'], $_POST['mail'], $_POST['password'], $_POST['confirmPassword'])) {
	if(!empty($_POST['username']) && !empty($_POST['mail']) && !empty($_POST['password']) && !empty($_POST['confirmPassword'])) {

		//Variables
		$username = $_POST['username'];
		$mail = $_POST['mail'];
		$pass = sha1($_POST['password']);
		$pass_confirm = sha1($_POST['confirmPassword']);

		//Si les mot de passe corresponde
		if ($pass === $pass_confirm) {

			//Check account 
			$check = $bdd->prepare('SELECT * FROM account WHERE username = ?');
			$check->execute(array($username));
			$checkCount = $check->rowCount();

			//Si l'username existe déjà 
			if ($checkCount > 0) {

				$err = 'Username is already use !';

			}else {

				//Check mail 
				$check_mail = $bdd->prepare('SELECT * FROM account WHERE mail = ?');
				$check_mail->execute(array($mail));
				$check_mailCount = $check_mail->rowCount();

				//Si le mail est déjà pris
				if ($check_mailCount == 0) {

					//Insertion
					$ins = $bdd->prepare('INSERT INTO account (username, pass, mail)  VALUES (?, ?, ?)');
					$ins->execute(array($username, $pass, $mail));

					//Return success
					$succ = "You are register ! ";

					header('Location: ./login.php');

				}else {

					$err = "Mail already used !";

				}
						
			}

		}else {

			$err = 'Les mots de passe sont différents';

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

  <title>Link - Register</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
              </div>
              <form class="user" action="" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control form-control-user" name="username" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-user" name="mail"
                    placeholder="Email Address">
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" name="password"
                      placeholder="Password">
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" name="confirmPassword"
                      placeholder="Repeat Password">
                  </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                <?php if(isset($err)) { echo '<center><p style="color:red">'.$err.'</p></center>'; } ?>
                <hr>
                <a href="index.php" class="btn btn-google btn-user btn-block">
                  <i class="fab fa-google fa-fw"></i> Register with Google
                </a>
                <a href="index.php" class="btn btn-facebook btn-user btn-block">
                  <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                </a>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="forgot-password.php">Forgot Password?</a>
              </div>
              <div class="text-center">
                <a class="small" href="login.php">Already have an account? Login!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>