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
        $country = array();

        if ($code == "all") {

            //Selection bdd 
            $req_country = $bdd->prepare('SELECT country_code, COUNT(DISTINCT id) AS nb FROM clicks WHERE owner_email = ? GROUP BY country_code');
            $req_country->execute($email);

            //Fetch 
            while ($c = $req_country->fetch()) {

                if ($c['country_code'] != null) {

                    //Push in array with key
                    $country[$c['country_code']] = $c['nb'];

                }

            }
            

        }else {

            //Selection bdd 
            $req_country = $bdd->prepare('SELECT country_code, COUNT(DISTINCT id) AS nb FROM clicks WHERE code = ? AND owner_email = ? GROUP BY country_code');
            $req_country->execute(array($code, $email));

            //Fetch 
            while ($c = $req_country->fetch()) {

                if ($c['country_code'] !== null) {

                    //Push in array with key
                    $country[$c['country_code']] = $c['nb'];

                }

            }

        }

        $myObj->country = $country;

        //Envoyer l'objet  dans un fichier json 
        $myJSON = json_encode($myObj);
        echo $myJSON;

    }

}

?>