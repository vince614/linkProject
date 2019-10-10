<?php 

//Variable 
$isLogin = false;

//Start session 
session_start();

//includes 
include './includes/config.php';

//Si l'utilisateur est connecté 
if(isset($_SESSION['username'])) {
    if (!empty($_SESSION['username'])) {
      
      //Var connection 
      $username = $_SESSION['username'];
      $isLogin = true;
  
    }
}
  

//Requête compteur 

//Links count 
$req_link = $bdd->prepare('SELECT * FROM links_table');
$req_link->execute();
$req_link_count = $req_link->rowCount();

//User count 
$req_user = $bdd->prepare('SELECT * FROM account');
$req_user->execute();
$req_user_count = $req_user->rowCount();

//Clicks Count 
$req_clicks = $bdd->prepare('SELECT * FROM clicks');
$req_clicks->execute();
$req_clicks_count = $req_clicks->rowCount();



?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="vfiewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="assets/images/logo2.png" type="image/x-icon">
    <meta name="description" content="">

    <title>Linky</title>
    <link rel="stylesheet" href="assets/web/assets/mobirise-icons/mobirise-icons.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="assets/dropdown/css/style.css">
    <link rel="stylesheet" href="assets/tether/tether.min.css">
    <link rel="stylesheet" href="assets/socicon/css/styles.css">
    <link rel="stylesheet" href="assets/theme/css/style.css">
    <link rel="preload" as="style" href="assets/mobirise/css/mbr-additional.css">
    <link href="assets/fonts/kanit-css.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">



</head>

<body>
    <section class="menu cid-rEuzbR33ku" once="menu" id="menu2-4">



        <nav
            class="navbar navbar-expand beta-menu navbar-dropdown align-items-center navbar-fixed-top navbar-toggleable-sm bg-color transparent">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="menu-logo">
                <div class="navbar-brand">
                    <span class="navbar-logo">
                        <a href="./">
                            <img src="assets/images/logo2.png" alt="Linky" style="height: 3.8rem;">
                        </a>
                    </span>
                    <span class="navbar-caption-wrap">
                        <a class="navbar-caption text-black display-4" href="./">
                            LINKY
                        </a>
                    </span>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="navbar-buttons mbr-section-btn">
                    <a class="btn btn-sm btn-primary display-4" href="login/">
                        <span class="btn-icon mbri-mobile mbr-iconfont mbr-iconfont-btn">
                        </span>
                        SIGN IN / SIGN UP 
                    </a>
                </div>
            </div>
        </nav>
    </section>

    <section class="header6 cid-rEuzOBLVQD mbr-fullscreen" >



        <div class="mbr-overlay" style="opacity: 0.5; background-color: rgb(35, 35, 35);">
        </div>

        <div class="container">
            <div class="row justify-content-md-center">
                <div class="mbr-white col-md-10">
                    <h1 class="mbr-section-title align-center mbr-bold pb-3 mbr-fonts-style display-1">
                        URL SHORTENER
                    </h1>
                    <p class="mbr-text align-center pb-3 mbr-fonts-style display-5">
                    Reduce your url with linky, and get advanced stats on clicks.
                    </p>
                    <div class="mbr-section-btn align-center">
                        <a class="btn btn-md btn-primary display-4" href="login/">START NOW</a>
                        <a class="btn btn-md btn-white-outline display-4" href="dashboard/">DASHBOARD</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mbr-arrow hidden-sm-down" aria-hidden="true">
            <a href="#next">
                <i class="mbri-down mbr-iconfont"></i>
            </a>
        </div>
    </section>

    <section class="counters1 counters cid-rEuzQTm6bi" id="counters1-6">





        <div class="container">
            <h3 class="mbr-section-subtitle mbr-fonts-style display-5">
                Best url shortener ⚡ ! 
            </h3>

            <div class="container pt-4 mt-2">
                <div class="media-container-row">
                    <div class="card p-3 align-center col-12 col-md-6 col-lg-4">
                        <div class="panel-item p-3">
                            <div class="card-img pb-3">
                                <span class="mbri-link mbr-iconfont"></span>
                            </div>

                            <div class="card-text">
                                <h3 class="count pt-3 pb-3 mbr-fonts-style display-2">
                                    <?=$req_link_count ?>
                                </h3>
                                <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">
                                    Shortcut links
                                </h4>
                                <p class="mbr-content-text mbr-fonts-style display-7">
                                <strong>Linky</strong> gives you the possibility to shorten your URLs for easy access to the desired address. 
                                The handy solution for an influencer to keep in view the links you share with your community !
                                </p>
                            </div>
                        </div>
                    </div>


                    <div class="card p-3 align-center col-12 col-md-6 col-lg-4">
                        <div class="panel-item p-3">
                            <div class="card-img pb-3">
                                <span class="mbri-user mbr-iconfont"></span>
                            </div>
                            <div class="card-text">
                                <h3 class="count pt-3 pb-3 mbr-fonts-style display-2">
                                    <?=$req_user_count ?>
                                </h3>
                                <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">
                                    Users
                                </h4>
                                <p class="mbr-content-text mbr-fonts-style display-7">
                                <a href="register/">Sign up</a> on <strong>linky</strong> to start the adventure! If you already have an account, 
                                we thank you for your trust and we invite you to <a href="login/">log in</a>!
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-3 align-center col-12 col-md-6 col-lg-4">
                        <div class="panel-item p-3">
                            <div class="card-img pb-3">
                                <span class="mbri-cursor-click mbr-iconfont"></span>
                            </div>
                            <div class="card-text">
                                <h3 class="count pt-3 pb-3 mbr-fonts-style display-2">
                                    <?=$req_clicks_count ?>
                                </h3>
                                <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">
                                    Clicks
                                </h4>
                                <p class="mbr-content-text mbr-fonts-style display-7">
                                    When a person is directed to our URLs, he is instantly redirected to the links you have. 
                                    This <strong>quick redirect</strong> allows us to collect information and thus traced beautiful curves in <a href="dashboard/">dashboard</a>!
                                </p>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>

    <section class="mbr-section article content1 cid-rEuA2UrJk1" id="content1-8">



        <div class="container">
            <div class="media-container-row">
                <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                    <p>
                        <strong>Create your links in just a few clicks and benefit all our advantages!</strong>
                        Linky is <strong>free</strong> for everyone and has been developed with ❤ by <a href="#">vince</a>.
                        Start now the new experience that linky offers.
                </div>
            </div>
        </div>
    </section>

    <section class="countdown1 cid-rEuAaedhyA" id="countdown1-9">



        <div class="container ">
            <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2">
                Start in:
            </h2>

        </div>
        <div class="container countdown-cont align-center">
            <div class="daysCountdown" title="Days"></div>
            <div class="hoursCountdown" title="Hours"></div>
            <div class="minutesCountdown" title="Minutes"></div>
            <div class="secondsCountdown" title="Seconds"></div>
            <div class="countdown pt-5 mt-2" data-due-date="2019/11/01">
            </div>
        </div>
    </section>

    <section class="cid-rEuAiGBD0o" id="social-buttons3-a">





        <div class="container">
            <div class="media-container-row">
                <div class="col-md-8 align-center">
                    <h2 class="pb-3 mbr-section-title mbr-fonts-style display-2">
                        SHARE LINKY!
                    </h2>
                    <div>
                        <div class="mbr-social-likes">
                            <span class="btn btn-social socicon-bg-facebook facebook mx-2"
                                title="Share link on Facebook">
                                <i class="socicon socicon-facebook"></i>
                            </span>
                            <span class="btn btn-social twitter socicon-bg-twitter mx-2" title="Share link on Twitter">
                                <i class="socicon socicon-twitter"></i>
                            </span>
                            <span class="btn btn-social vkontakte socicon-bg-vkontakte mx-2"
                                title="Share link on VKontakte">
                                <i class="socicon socicon-vkontakte"></i>
                            </span>
                            <span class="btn btn-social odnoklassniki socicon-bg-odnoklassniki mx-2"
                                title="Share link on Odnoklassniki">
                                <i class="socicon socicon-odnoklassniki"></i>
                            </span>
                            <span class="btn btn-social pinterest socicon-bg-pinterest mx-2"
                                title="Share link on Pinterest">
                                <i class="socicon socicon-pinterest"></i>
                            </span>
                            <span class="btn btn-social mailru socicon-bg-mail mx-2" title="Share link on Mailru">
                                <i class="socicon socicon-mail"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="assets/web/assets/jquery/jquery.min.js"></script>
    <script src="assets/popper/popper.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vimeoplayer/jquery.mb.vimeo_player.js"></script>
    <script src="assets/smoothscroll/smooth-scroll.js"></script>
    <script src="assets/dropdown/js/nav-dropdown.js"></script>
    <script src="assets/dropdown/js/navbar-dropdown.js"></script>
    <script src="assets/tether/tether.min.js"></script>
    <script src="assets/ytplayer/jquery.mb.ytplayer.min.js"></script>
    <script src="assets/viewportchecker/jquery.viewportchecker.js"></script>
    <script src="assets/countdown/jquery.countdown.min.js"></script>
    <script src="assets/sociallikes/social-likes.js"></script>
    <script src="assets/touchswipe/jquery.touch-swipe.min.js"></script>
    <script src="assets/theme/js/script.js"></script>


</body>

</html>