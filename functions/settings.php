<?php 

//Démarer la session 
session_start();

//Variable 
$email = $_SESSION['email'];

//Inclure le fichier config de la bdd
include '../includes/config.php';

//Crée un objet php
$myObj = new \stdClass();

//On verifie les variables [POST]
if(isset($_POST['username'])) {
    if(!empty($_POST['username'])) {

        //Variables
        $usernameForm = $_POST['username'];

        $update = $bdd->prepare('UPDATE account SET username = ? WHERE mail = ?');
        $update->execute(array($usernameForm, $email));

        $myObj->username = $usernameForm;
        $_SESSION['username'] = $usernameForm;


    }else {

        $myObj->err = "Please enter valid username";

    }
}

if(isset($_POST['oldPassword'], $_POST['newPassword'], $_POST['newPasswordVerif'])) {
    if(!empty($_POST['oldPassword']) AND !empty($_POST['newPassword']) AND !empty($_POST['newPasswordVerif'])) {

        $oldPassword = sha1($_POST['oldPassword']);
        $newPassword = sha1($_POST['newPassword']);
        $newPasswordVerif = sha1($_POST['newPasswordVerif']);

        $verif = $bdd->prepare('SELECT * FROM account WHERE mail = ?');
        $verif->execute(array($email));

        //Fetch password 
        if($m = $verif->fetch()) {
            $mdp = $m['pass'];
        }

        if($oldPassword == $mdp) {

            if($newPassword == $newPasswordVerif) {

                $update = $bdd->prepare('UPDATE account SET pass = ? WHERE mail = ?');
                $update->execute(array($newPassword, $email));


            }else {

                $myObj->err = "Password not match !";

            }

        }else {

            $myObj->err = "Password is incorrect !";

        }

    }
}

//On verifie les variables [POST]
if(isset($_POST['delete'])) {
    if(!empty($_POST['delete'])) {

        //Variables
        $deleteAccount = $_POST['delete'];

        if($deleteAccount == 1){

            //Delete account 
            $delete = $bdd->prepare('DELETE FROM account WHERE mail = ?');
            $delete->execute(array($email));

            //Delete datas 
            $deleteData = $bdd->prepare('DELETE FROM clicks WHERE owner_email = ?');
            $deleteData->execute(array($email));

            //Delete links 
            $deleteLinks = $bdd->prepare('DELETE FROM links_table WHERE owner_email = ?');
            $deleteLinks->execute(array($email));

            //Delete sessions
            $_SESSION = array();

            session_destroy();

            //supprimer les cookie 

            setcookie('login', '');
            setcookie('pass_hache' , '');

            $myObj->delete = true;


        }


    }
}

//Envoyer l'objet  dans un fichier json 
$myJSON = json_encode($myObj);
echo $myJSON;


?>