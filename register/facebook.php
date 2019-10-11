<?php

//Require 
require ("../vendor/autoload.php");

//includes 
include '../includes/config.php';

session_start();

if(isset($_GET['state'])) {

    $_SESSION['FBRLH_state'] = $_GET['state'];

}

/*Step 1: Enter Credentials*/
$fb = new \Facebook\Facebook([

    'app_id' => '727282361017648',
    'app_secret' => '680db8577fe4cf94c9fd584fdf285a97',
    'default_graph_version' => 'v2.10',
    //'default_access_token' => '{access-token}', // optional

]);

/*Step 2 Create the url*/
if(empty($access_token)) {

    $permissions = ['email'];
    $url = $fb->getRedirectLoginHelper()->getLoginUrl("http://localhost/clypy.me/register/facebook.php", $permissions);

}

/*Step 3 : Get Access Token*/
$access_token = $fb->getRedirectLoginHelper()->getAccessToken();

/*Step 4: Get the graph user*/
if(isset($access_token)) {

    try {

        $response = $fb->get('/me?fields=first_name,email',$access_token);
        $fb_user = $response->getGraphUser();

        if(isset($fb_user['first_name'], $fb_user['email'])) {
            if(!empty($fb_user['first_name']) AND !empty($fb_user['email'])) {
    
                //Variable 
                $mail = $fb_user['email'];
                $name = $fb_user['first_name'];
                $picture = "http://graph.facebook.com/".$fb_user['id']."/picture?type=square";
                $auth = "facebook";
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

        //var_dump($fb_user);

    } catch (\Facebook\Exceptions\FacebookResponseException $e) {

        echo  'Graph returned an error: ' . $e->getMessage();

    } catch (\Facebook\Exceptions\FacebookSDKException $e) {

        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();

    }
}else {

    header('Location: '.$url);

}

?>