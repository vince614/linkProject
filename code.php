<?php

//Start sessions
session_start();

//includes
include './includes/config.php';
require_once './vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';

//Variables 
$detect = new Mobile_Detect;
$time = time();
$isMobile = 0;
$isTablet = 0;
$isDesktop = 0;

// Exclude tablets.
if( $detect->isMobile() && !$detect->isTablet() ){
    $isMobile = 1;
}

// Any tablet device.
if( $detect->isTablet() && !$detect->isMobile()){
    $isTablet = 1;
}

//Desktop
if($isTablet == 0 && $isTablet == 0){
    $isDesktop = 1;
}

//Code [GET]
if(isset($_GET['code'])){
    if(!empty($_GET['code'])) {

        //Variables
        $code = $_GET['code'];
        $lenghtMax = 5;

        //On vérifie que le code sois composé de 5 charactères
        if(strlen($code) === $lenghtMax) {
            
            //Requête [links_table]
            $req_code = $bdd->prepare('SELECT * FROM links_table WHERE code = ?');
            $req_code->execute(array($code));

            //On vérifie que le code existe 
            $req_code_count = $req_code->rowCount();

            //Si il existe 
            if ($req_code_count > 0) {

                if ($c = $req_code->fetch()) {

                    //Variables
                    $owner_username = $c['owner_username'];
                    $redirect = $c['links_origin'];
                    $title = $c['title'];
                        
                    //Insert
                    $ins = $bdd->prepare('INSERT INTO clicks (code, owner_username, isPhone, isTablet, isDesktop, clicks_time) VALUES(?, ?, ?, ?, ?, ?)');
                    $ins->execute(array($code, $owner_username, $isMobile, $isTablet, $isDesktop, $time));

                    //Redirect
                    header('Location: '.$redirect);

                }

            }

        }

    }
}

//  http://mobiledetect.net/
 
// Any mobile device (phones or tablets).
if ( $detect->isMobile() ) {
 
}
 
// Any tablet device.
if( $detect->isTablet() ){
 
}
 
// Exclude tablets.
if( $detect->isMobile() && !$detect->isTablet() ){
 
}
 
// Check for a specific platform with the help of the magic methods:
if( $detect->isiOS() ){
 
}
 
if( $detect->isAndroidOS() ){
 
}



?>