<?php 

//includes 
include '../includes/config.php';

//
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if(isset($_POST['email'])) {
  if(!empty($_POST['email'])){

    //Variable
    $email = $_POST['email'];
    $time = time();
    $time_end = $time + 60*30; 
    $verif = 1;

    //Check mail 
    $req_email = $bdd->prepare('SELECT * FROM account WHERE mail = ?');
    $req_email->execute(array($email));
    $email_count = $req_email->rowCount();

    //Info sur l'utilisateur 
    if($a = $req_email->fetch()) {

      $name = $a['username'];

    }

    //If exist 
    if ($email_count > 0) {

      //Verif code 
      $req_code_expire = $bdd->prepare('SELECT * FROM forgot_pass WHERE time_end > ? AND mail = ?');
      $req_code_expire->execute(array($time, $email));
      $req_code_expire_count = $req_code_expire->rowCount();

      //Si le code est disponible 
      if ($req_code_expire_count == 0) {

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
        $ins->execute(array($email, $code_aleatoire, $time_end));

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'mail.gandi.net';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'bot@clypy.me';                     // SMTP username
        $mail->Password   = 'victoryclan95';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('bot@clypy.me', 'clypy.me');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Reset your password | Clypy.me';
        $mail->Body    = 'Hello '.$name.' ! <br/> You have made a request to reset your password <br/><br/> Reset your pasword <a href="https://clypy.me/reset-password/'.$code_aleatoire.'">here</a> <br/><br/> (the following link expire in 30 minutes) <br/><br/> Sincerely, <br/> Clypy.me';
        $mail->AltBody = 'Hello '.$name.' ! <br/> You have made a request to reset your password <br/><br/> Reset your pasword here : https://clypy.me/reset-password/'.$code_aleatoire.' <br/><br/> (the following link expire in 30 minutes) <br/><br/> Sincerely, <br/> Clypy.me';

        $mail->send();
            


        $succ = "Mail was send on The code sent expires in 30 minutes";

      }else {

        $err = "Your code is not expired ! Check your mailbox.";

      }

      

    }else {

      $err = "Mail not used in clypy.me";

    }
    

  }else {

    $err = "Please enter valid email address";

  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="../assets/img/clypy.png" type="image/x-icon">
  <meta name="description" content="Clypy.me shorten your links and follow them to see their evulotion with statistical data">
  <meta name="author" content="Vince">

  <title>Forgot Password - Clypy.me</title>

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
