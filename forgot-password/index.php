<?php 

//includes 
include '../includes/config.php';

if(isset($_POST['email'])) {
  if(!empty($_POST['email'])){

    //Variable
    $mail = $_POST['email'];
    $time = time();
    $time_end = $time + 60*30; 
    $verif = 1;

    //Check mail 
    $req_mail = $bdd->prepare('SELECT * FROM account WHERE mail = ?');
    $req_mail->execute(array($mail));
    $mail_count = $req_mail->rowCount();

    //If exist 
    if ($mail_count > 0) {

      //Verif code 
      $req_code_expire = $bdd->prepare('SELECT * FROM forgot_pass WHERE time_end < ? AND mail = ?');
      $req_code_expire->execute(array($time, $mail));
      $req_code_expire_count = $req_code_expire->rowCount();

      //Si le code est disponible 
      if ($req_code_expire_count > 0) {

        //Caractères du code 
        $characts = 'abcdefghijklmnopqrstuvwxyz';
        $characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts .= '1234567890';
        $code_aleatoire = '';

        //Nombre de caractère
        $charactsCount = 30;

        //Si il le code existe déjà recommencé
        while($verif !== 0) {

          //Géneration du caractère
          for($i=0; $i < $charactsCount; $i++) {
            $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1);
          }

          //Les codes dans la bdd
          $codes = $bdd->prepare('SELECT * FROM forgot_pass WHERE code = ?');
          $codes->execute(array($code_aleatoire));
          $verif = $codes->rowCount();


        }

        //Insertion à la bdd 
        $ins = $bdd->prepare('INSERT INTO forgot_pass (mail, code, time_end) VALUES (?, ?, ?)');
        $ins->execute(array($mail, $code_aleatoire, $time_end));


        $succ = "Mail was send on ".$mail." The code sent expires in 30 minutes";

      }else {

        $err = "Your code is not expired ! Check your mailbox.";

      }

      

    }else {

      $err = "Mail not used in clypy.me";

    }
    

  }else {

    $err = "Please enter valid email address";

  }
}else {

  $err = "Please enter valid email address";

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

  <title>Clypy - Forgot Password</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../assets/css/kanit-css.css" rel="stylesheet">

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
              <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                    <p class="mb-4">We get it, stuff happens. Just enter your email address below and we'll send you a link to reset your password!</p>
                  </div>
                  <form action="" method="POST" class="user">
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" name="email" aria-describedby="emailHelp" placeholder="Enter Email Address..." required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Reset Password</button>
                    <?php if(isset($err)) { echo '<center><span style="color: red">'.$err.'</span></center>'; } ?>
                    <?php if(isset($succ)) { echo '<center><span style="color: green">'.$succ.'</span></center>'; } ?>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="../register">Create an Account!</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="../login">Already have an account? Login!</a>
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

</body>

</html>
