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

if(isset($_SESSION['email'])) {
    if(!empty($_SESSION['email'])) {

        $email = $_SESSION['email'];

        //requête jour
        if($date == 'day') {

            //Array 
            $arrayPie = array();

            if($code == 'all') {

                //Is phone 
                $req_day_phone = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_phone->execute(array($email, 1, $time_day, $time));
                $req_day_phone_count = $req_day_phone->rowCount();
                array_push($arrayPie, $req_day_phone_count);

                //Is phone 
                $req_day_tablet = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_tablet->execute(array($email, 1, $time_day, $time));
                $req_day_tablet_count = $req_day_tablet->rowCount();
                array_push($arrayPie, $req_day_tablet_count);

                //Is phone 
                $req_day_desktop = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_desktop->execute(array($email, 1, $time_day, $time));
                $req_day_desktop_count = $req_day_desktop->rowCount();
                array_push($arrayPie, $req_day_desktop_count);

                //Req true
                $myObj->req = true;


            }else {

                //Is phone 
                $req_day_phone = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND code = ? clicks_time BETWEEN ? AND ?');
                $req_day_phone->execute(array($email, 1, $code, $time_day, $time));
                $req_day_phone_count = $req_day_phone->rowCount();
                array_push($arrayPie, $req_day_phone_count);

                //Is phone 
                $req_day_tablet = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_tablet->execute(array($email, 1, $code, $time_day, $time));
                $req_day_tablet_count = $req_day_tablet->rowCount();
                array_push($arrayPie, $req_day_tablet_count);

                //Is phone 
                $req_day_desktop = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_desktop->execute(array($email, 1, $code, $time_day, $time));
                $req_day_desktop_count = $req_day_desktop->rowCount();
                array_push($arrayPie, $req_day_desktop_count);

                //Req true
                $myObj->req = true;

            }

            //Exporte requete
            $myObj->pie = $arrayPie;
            $myObj->timeNow = $time;
            $myObj->time = $time_day;


        }else if($date == 'week') {

            //Array 
            $arrayPie = array();

            if($code == 'all') {

                //Is phone 
                $req_week_phone = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND clicks_time BETWEEN ? AND ?');
                $req_week_phone->execute(array($email, 1, $time_week, $time));
                $req_week_phone_count = $req_week_phone->rowCount();
                array_push($arrayPie, $req_week_phone_count);

                //Is phone 
                $req_week_tablet = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND clicks_time BETWEEN ? AND ?');
                $req_week_tablet->execute(array($email, 1, $time_week, $time));
                $req_week_tablet_count = $req_week_tablet->rowCount();
                array_push($arrayPie, $req_week_tablet_count);

                //Is phone 
                $req_week_desktop = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND clicks_time BETWEEN ? AND ?');
                $req_week_desktop->execute(array($email, 1, $time_week, $time));
                $req_week_desktop_count = $req_week_desktop->rowCount();
                array_push($arrayPie, $req_week_desktop_count);

                //Req true
                $myObj->req = true;


            }else {

                //Is phone 
                $req_week_phone = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND code = ? clicks_time BETWEEN ? AND ?');
                $req_week_phone->execute(array($email, 1, $code, $time_week, $time));
                $req_week_phone_count = $req_week_phone->rowCount();
                array_push($arrayPie, $req_week_phone_count);

                //Is phone 
                $req_week_tablet = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_week_tablet->execute(array($email, 1, $code, $time_week, $time));
                $req_week_tablet_count = $req_week_tablet->rowCount();
                array_push($arrayPie, $req_week_tablet_count);

                //Is phone 
                $req_week_desktop = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_week_desktop->execute(array($email, 1, $code, $time_week, $time));
                $req_week_desktop_count = $req_week_desktop->rowCount();
                array_push($arrayPie, $req_week_desktop_count);

                //Req true
                $myObj->req = true;

            }

            //Exporte requete
            $myObj->pie = $arrayPie;
            $myObj->timeNow = $time;
            $myObj->time = $time_week;


        }else if($date == 'month') {

            //Array 
            $arrayPie = array();

            if($code == 'all') {

                //Is phone 
                $req_month_phone = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND clicks_time BETWEEN ? AND ?');
                $req_month_phone->execute(array($email, 1, $time_month, $time));
                $req_month_phone_count = $req_month_phone->rowCount();
                array_push($arrayPie, $req_month_phone_count);

                //Is phone 
                $req_month_tablet = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND clicks_time BETWEEN ? AND ?');
                $req_month_tablet->execute(array($email, 1, $time_month, $time));
                $req_month_tablet_count = $req_month_tablet->rowCount();
                array_push($arrayPie, $req_month_tablet_count);

                //Is phone 
                $req_month_desktop = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND clicks_time BETWEEN ? AND ?');
                $req_month_desktop->execute(array($email, 1, $time_month, $time));
                $req_month_desktop_count = $req_month_desktop->rowCount();
                array_push($arrayPie, $req_month_desktop_count);

                //Req true
                $myObj->req = true;


            }else {

                //Is phone 
                $req_month_phone = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND code = ? clicks_time BETWEEN ? AND ?');
                $req_month_phone->execute(array($email, 1, $code, $time_month, $time));
                $req_month_phone_count = $req_month_phone->rowCount();
                array_push($arrayPie, $req_month_phone_count);

                //Is phone 
                $req_month_tablet = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_month_tablet->execute(array($email, 1, $code, $time_month, $time));
                $req_month_tablet_count = $req_month_tablet->rowCount();
                array_push($arrayPie, $req_month_tablet_count);

                //Is phone 
                $req_month_desktop = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_month_desktop->execute(array($email, 1, $code, $time_month, $time));
                $req_month_desktop_count = $req_month_desktop->rowCount();
                array_push($arrayPie, $req_month_desktop_count);

                //Req true
                $myObj->req = true;

            }

            //Exporte requete
            $myObj->pie = $arrayPie;
            $myObj->timeNow = $time;
            $myObj->time = $time_month;


        }else if($date == 'year') {

            //Array 
            $arrayPie = array();

            if($code == 'all') {

                //Is phone 
                $req_year_phone = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND clicks_time BETWEEN ? AND ?');
                $req_year_phone->execute(array($email, 1, $time_year, $time));
                $req_year_phone_count = $req_year_phone->rowCount();
                array_push($arrayPie, $req_year_phone_count);

                //Is phone 
                $req_year_tablet = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND clicks_time BETWEEN ? AND ?');
                $req_year_tablet->execute(array($email, 1, $time_year, $time));
                $req_year_tablet_count = $req_year_tablet->rowCount();
                array_push($arrayPie, $req_year_tablet_count);

                //Is phone 
                $req_year_desktop = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND clicks_time BETWEEN ? AND ?');
                $req_year_desktop->execute(array($email, 1, $time_year, $time));
                $req_year_desktop_count = $req_year_desktop->rowCount();
                array_push($arrayPie, $req_year_desktop_count);

                //Req true
                $myObj->req = true;


            }else {

                //Is phone 
                $req_year_phone = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND code = ? clicks_time BETWEEN ? AND ?');
                $req_year_phone->execute(array($email, 1, $code, $time_year, $time));
                $req_year_phone_count = $req_year_phone->rowCount();
                array_push($arrayPie, $req_year_phone_count);

                //Is phone 
                $req_year_tablet = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_year_tablet->execute(array($email, 1, $code, $time_year, $time));
                $req_year_tablet_count = $req_year_tablet->rowCount();
                array_push($arrayPie, $req_year_tablet_count);

                //Is phone 
                $req_year_desktop = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_year_desktop->execute(array($email, 1, $code, $time_year, $time));
                $req_year_desktop_count = $req_year_desktop->rowCount();
                array_push($arrayPie, $req_year_desktop_count);

                //Req true
                $myObj->req = true;

            }

            //Exporte requete
            $myObj->pie = $arrayPie;
            $myObj->timeNow = $time;
            $myObj->time = $time_year;


        }

        //Envoyer l'objet  dans un fichier json 
        $myJSON = json_encode($myObj);
        echo $myJSON;

    }

}

?>