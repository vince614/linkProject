<?php 

//includes 
include '../includes/config.php';

//Variables 
$time = time();

if(isset($_GET['code'])) {
    if(!empty($_GET['code'])) {

        $code = $_GET['code'];

        $req_code = $bdd->prepare('SELECT * FROM forgot_pass WHERE code = ? AND time_end > ?');
        $req_code->execute(array($code, $time));
        $req_code_count = $req_code->rowCount();

        if($c = $req_code->fetch()) {

            $email = $c['mail'];

        }

        if($req_code_count > 0) {

            //Verif POST []
            if(isset($_POST['password'], $_POST['confirmPassword'])) {
                if(!empty($_POST['password']) AND !empty($_POST['confirmPassword'])) {

                    $password = sha1($_POST['password']);
                    $confirmPassword = sha1($_POST['confirmPassword']);

                    if ($password === $confirmPassword) {

                        $update = $bdd->prepare('UPDATE account SET pass = ? WHERE mail = ?');
                        $update->execute(array($password, $email));

                        $succ = "Your password has been changed !";

                    }else {

                        $err = "Password not match !";

                    }

                }
            }

        }else {

            $err = "Code is expired ! ";

        }

    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="../assets/img/clypy.png" type="image/x-icon">
    <meta name="description"
        content="Clypy.me shorten your links and follow them to see their evulotion with statistical data">
    <meta name="author" content="Vince">

    <title>Reset Password - Clypy.me</title>

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
                            <div class="col-lg-6 d-none d-lg-block bg-reset-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Reset your password !</h1>
                                        <p class="mb-4">Enter your new beautiful password here !</p>
                                    </div>
                                    <form action="" method="POST" class="user">
                                        <div class="form-group row">
                                            <input type="password" class="form-control form-control-user"
                                                name="password" placeholder="New Password" required>
                                        </div>
                                        <div class="form-group row">
                                            <input type="password" class="form-control form-control-user"
                                                name="confirmPassword" placeholder="Repeat New Password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Reset
                                            Password</button>
                                        <?php if(isset($err)) { echo '<center><span style="color: red">'.$err.'</span></center>'; } ?>
                                        <?php if(isset($succ)) { echo '<center><span style="color: green">'.$succ.'</span></center>'; } ?>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="../login">Login </a>
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

</body>

</html>