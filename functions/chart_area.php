<?php 

//Démarer la session 
session_start();

//Inclure le fichier config de la bdd
include '../includes/config.php';

//Variables 
$code = $_POST['code'];
$date = $_POST['date'];
$time = time();
$time_hour = $time - 60*60;
$time_day = $time - 24*60*60;
$time_week = $time - 7*24*60*60;
$time_month = $time - 30*24*60*60;
$time_year = $time - 12*30*24*60*60;

//Crée un objet php
$myObj = new \stdClass();
$myObj->date = $date;



//requête jour
if($date == 'day') {

    //Crée un tableau
    $arrayHours = array();
    $arrayTime = array();

    for($i = 23; $i >= 0; $i--){

        //Time before and after
        $time_hours_before = $time - 60*60 * ($i + 1);
        $time_hours_after = $time - 60*60*$i;

        //Si on veux tout les linky 
        if($code == 'all') {

            //Requête all day of week
            $req_day = $bdd->prepare('SELECT id FROM clicks WHERE clicks_time BETWEEN ? AND ?');
            $req_day->execute(array($time_hours_before, $time_hours_after));


        //Si on veux juste un linky
        }else {

            //Requête all day of week
            $req_day = $bdd->prepare('SELECT id FROM clicks WHERE code = ? AND clicks_time BETWEEN ? AND ?');
            $req_day->execute(array($code, $time_hours_before, $time_hours_after));

        }

        //Count 
        $req_day_count = $req_day->rowCount();

        //Mettre dans le tableau 
        array_push($arrayHours, $req_day_count);
        array_push($arrayTime, $time_hours_before);

    }

    //Valeurs per hours 
    $myObj->hours = $arrayHours;

    //Time 
    $myObj->time = $arrayTime;

    //Req true
    $myObj->req = true;
    $myObj->code = $code;
    
    

}else if($date == 'week') {

    //Crée un tableau
    $arrayDays = array();
    $arrayTime = array();

    for($i = 6; $i >= 0; $i--){

        //Time before and after
        $time_Days_before = $time - 24*60*60 * ($i + 1);
        $time_Days_after = $time - 24*60*60*$i;

        //Si on veux tout les linky 
        if($code == 'all') {

            //Requête all day of week
            $req_week = $bdd->prepare('SELECT id FROM clicks WHERE clicks_time BETWEEN ? AND ?');
            $req_week->execute(array($time_Days_before, $time_Days_after));


        //Si on veux juste un linky
        }else {

            //Requête all day of week
            $req_week = $bdd->prepare('SELECT id FROM clicks WHERE code = ? AND clicks_time BETWEEN ? AND ?');
            $req_week->execute(array($code, $time_Days_before, $time_Days_after));

        }

        //Count 
        $req_week_count = $req_week->rowCount();

        //Mettre dans le tableau 
        array_push($arrayDays, $req_week_count);
        array_push($arrayTime, $time_Days_before);

    }

    //Valeurs per hours 
    $myObj->days = $arrayDays;

    //Time 
    $myObj->time = $arrayTime;

    //Req true
    $myObj->req = true;
    $myObj->code = $code;

}else if ($date == 'month') {

    //Crée un tableau
    $arrayweek = array();
    $arrayTime = array();

    for($i = 29; $i >= 0; $i--){

        //Time before and after
        $time_week_before = $time - 24*60*60 * ($i + 1);
        $time_week_after = $time - 24*60*60*$i;

        //Si on veux tout les linky 
        if($code == 'all') {

            //Requête all day of week
            $req_week = $bdd->prepare('SELECT id FROM clicks WHERE clicks_time BETWEEN ? AND ?');
            $req_week->execute(array($time_week_before, $time_week_after));


        //Si on veux juste un linky
        }else {

            //Requête all day of week
            $req_week = $bdd->prepare('SELECT id FROM clicks WHERE code = ? AND clicks_time BETWEEN ? AND ?');
            $req_week->execute(array($code, $time_week_before, $time_week_after));

        }

        //Count 
        $req_week_count = $req_week->rowCount();

        //Mettre dans le tableau 
        array_push($arrayweek, $req_week_count);
        array_push($arrayTime, $time_week_before);

    }

    //Valeurs per hours 
    $myObj->month = $arrayweek;

    //Time 
    $myObj->time = $arrayTime;

    //Req true
    $myObj->req = true;
    $myObj->code = $code;

    //

}else if($date == 'year') {

    //
    //Crée un tableau
    $arraymonth = array();
    $arrayTime = array();

    for($i = 11; $i >= 0; $i--){

        //Time before and after
        $time_month_before = $time - 30*24*60*60 * ($i + 1);
        $time_month_after = $time - 30*24*60*60*$i;

        //Si on veux tout les linky 
        if($code == 'all') {

            //Requête all day of month
            $req_month = $bdd->prepare('SELECT id FROM clicks WHERE clicks_time BETWEEN ? AND ?');
            $req_month->execute(array($time_month_before, $time_month_after));


        //Si on veux juste un linky
        }else {

            //Requête all day of month
            $req_month = $bdd->prepare('SELECT id FROM clicks WHERE code = ? AND clicks_time BETWEEN ? AND ?');
            $req_month->execute(array($code, $time_month_before, $time_month_after));

        }

        //Count 
        $req_month_count = $req_month->rowCount();

        //Mettre dans le tableau 
        array_push($arraymonth, $req_month_count);
        array_push($arrayTime, $time_month_before);

    }

    //Valeurs per hours 
    $myObj->year = $arraymonth;

    //Time 
    $myObj->time = $arrayTime;

    //Req true
    $myObj->req = true;
    $myObj->code = $code;

}

//Envoyer l'objet  dans un fichier json 
$myJSON = json_encode($myObj);
echo $myJSON;

?>