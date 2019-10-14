<?php 

//Démarer la session 
session_start();

//Inclure le fichier config de la bdd
include '../includes/config.php';

//Variables 
$code = $_POST['code'];

//Crée un objet php
$myObj = new \stdClass();
$myObj->code = $code;

if(isset($_SESSION['email'])) {
    if(!empty($_SESSION['email'])) {

        $email = $_SESSION['email'];

        //Array 
        $browser = array();

        if ($code == "all") {

            //Selection bdd 
            $req_browser = $bdd->prepare('SELECT browser, COUNT(DISTINCT id) AS nb FROM clicks GROUP BY browser');
            $req_browser->execute();

            //Fetch 
            while ($c = $req_browser->fetch()) {

                if ($c['browser'] !== null) {

                    //Push in array with key
                    $browser[$c['browser']] = $c['nb'];

                }

            }
            

        }else {

            //Selection bdd 
            $req_browser = $bdd->prepare('SELECT browser, COUNT(DISTINCT id) AS nb FROM clicks WHERE code ? GROUP BY browser');
            $req_browser->execute(array($code));

            //Fetch 
            while ($c = $req_browser->fetch()) {

                if ($c['browser'] !== null) {

                    //Push in array with key
                    $browser[$c['browser']] = $c['nb'];

                }

            }

        }

        $myObj->browser = $browser;

        //Envoyer l'objet  dans un fichier json 
        $myJSON = json_encode($myObj);
        echo $myJSON;

    }

}

?>