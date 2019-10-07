<?php 

//Démarer la session 
session_start();

//Inclure le fichier config de la bdd
include '../includes/config.php';

//Crée un objet php
$myObj = new \stdClass();
$myObj->edit = false;

//On verifie les variables [POST]
if(isset($_POST['code'], $_POST['title'])) {
    if(!empty($_POST['code']) && !empty($_POST['title'])) {

        //Variables
        $code = $_POST['code'];
        $title = $_POST['title'];
        $myObj->code = true;

        //Vérif 
        $verif = $bdd->prepare('SELECT * FROM links_table WHERE code = ?');
        $verif->execute(array($code));
        $verifCount = $verif->rowCount();

        //Si le code existe bien 
        if($verifCount > 0) {

            $myObj->exist = true;

            //Delete 
            $delete = $bdd->prepare('UPDATE links_table SET title = ? WHERE code = ?');
            $delete->execute(array($title, $code));

            $myObj->edit = true;


        }else {

            $myObj->exist = false;

        }


    }else {

        $myObj->code = false;

    }
}else {

    $myObj->code = false;

}

//Envoyer l'objet  dans un fichier json 
$myJSON = json_encode($myObj);
echo $myJSON;


?>