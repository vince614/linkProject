<?php

//Require
require ("../vendor/autoload.php");

//includes 
include '../includes/config.php';

//Start session
session_start();

$provider = new \Wohali\OAuth2\Client\Provider\Discord([
    'clientId' => '636565800379219989',
    'clientSecret' => 'YLIevJgW5z5ggqhQAIcHlbRN2PQF0WZC',
    'redirectUri' => 'http://localhost/clypy.me/register/discord.php'
]);

if (!isset($_GET['code'])) {

  $options = [
      'scope' => ['identify', 'email'] // array or string
  ];


    // Step 1. Get authorization code
    $authUrl = $provider->getAuthorizationUrl($options);
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Step 2. Get an access token using the provided authorization code
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Step 3. (Optional) Look up the user's profile with the provided token
    try {

        $user = $provider->getResourceOwner($token);

        
        $username = $user->getUsername(); 
        $user_id = $user->getId();
        $user_email = $user->getEmail();
        $avatar = $user->getAvatarHash();
        $auth = "Discord";
        $time = time();

        $avatar_url = "https://cdn.discordapp.com/avatars/".$user_id."/".$avatar;

        //If users already register 
        $check_mail = $bdd->prepare('SELECT * FROM account WHERE mail = ?');
        $check_mail->execute(array($user_email));
        $check_mail_count = $check_mail->rowCount();

        //Verif
        if($check_mail_count == 0) {

            //Insert new account 
            $ins = $bdd->prepare('INSERT INTO account (username, pass, mail, picture, auth, date_time) VALUES (?, ? , ? , ? , ? , ?)');
            $ins->execute(array($username, 0, $user_email, $avatar_url, $auth, $time));
            header('Location: ../login');
            

        }else {

            header('Location: ../login');

        }

        



    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');

    }
}