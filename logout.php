<?php 

session_start();

$_SESSION = array();

session_destroy();

//supprimer les cookie 

setcookie('login', '');
setcookie('pass_hache' , '');

header('Location: login.php');


?>