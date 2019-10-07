<?php 

//Variables 
$code = $_POST['code'];
$date = $_POST['date'];
$time = time();
$time_hour = $time - 60*60;
$time_day = $time - 24*60*60;
$time_month = $time - 30*24*60*60;
$time_year = $time - 12*30*24*60*60;


//requête jour
if($date == 'day') {

    //

}

sleep(2);

//Crée un objet php
$myObj = new \stdClass();
$myObj->exist = true;



// //Requetes click count [month]
// $req_click_month = $bdd->prepare('SELECT COUNT(*) AS monthCount FROM clicks WHERE owner_username = ? AND ismonth = ?');
// $req_click_month->execute(array($username, 1));
// $data_click_month = $req_click_month->fetch();
// $click_month_count = $data_click_month['monthCount'];

// $heures = $stats_heures->fetchAll();
// $messages_heures = $heures[0];

//Envoyer l'objet  dans un fichier json 
$myJSON = json_encode($myObj);
echo $myJSON;

?>