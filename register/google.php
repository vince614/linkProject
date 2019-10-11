<?php 

//Require
require ("../vendor/autoload.php");

//includes 
include '../includes/config.php';

//Start session
session_start();



//Scope 
$scopes = [
    'email',
    'profile'
];

//Step 1: Enter you google account credentials
$g_client = new Google_Client();
$g_client->setClientId("729851811799-nqrou1qcmitqo1jplclc9tkobp0omvn8.apps.googleusercontent.com");
$g_client->setClientSecret("9V06LyUF1uvvxcnyO4T9TPQz");
$g_client->setRedirectUri("http://localhost/clypy.me/register/google.php");
$g_client->setScopes($scopes);

//Step 2 : Create the url
$auth_url = $g_client->createAuthUrl();

//Step 3 : Get the authorization  code
$code = isset($_GET['code']) ? $_GET['code'] : NULL;

//Step 4: Get access token
if(isset($code)) {

    try {

        $token = $g_client->fetchAccessTokenWithAuthCode($code);
        $g_client->setAccessToken($token);

    }catch (Exception $e){

        echo $e->getMessage();

    }

    try {

        $pay_load = $g_client->verifyIdToken();

    }catch (Exception $e) {

        echo $e->getMessage();

    }

} else{

    $pay_load = null;

}

if(isset($pay_load)){

    

    if(isset($pay_load['email'], $pay_load['given_name'], $pay_load['picture'])) {
        if(!empty($pay_load['email']) AND !empty($pay_load['given_name']) AND !empty($pay_load['picture'])) {

            //Variable 
            $mail = $pay_load['email'];
            $name = $pay_load['given_name'];
            $picture = $pay_load['picture'];
            $auth = "google";
            $time = time();

            //If users already register 
            $check_mail = $bdd->prepare('SELECT * FROM account WHERE mail = ?');
            $check_mail->execute(array($mail));
            $check_mail_count = $check_mail->rowCount();

            //Verif
            if($check_mail_count == 0) {

                //Insert new account 
                $ins = $bdd->prepare('INSERT INTO account (username, pass, mail, picture, auth, date_time) VALUES (?, ? , ? , ? , ? , ?)');
                $ins->execute(array($name, 0, $mail, $picture, $auth, $time));
                header('Location: ../login');
                

            }else {

                header('Location: ../login');

            }


        }
    }
    
}else {

    header('Location: '.$auth_url);

}