<?php

//Start sessions
session_start();

//includes
include './includes/config.php';
require_once './vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';

//Variables 
$detect = new Mobile_Detect;
$time = time();
$isMobile = 0;
$isTablet = 0;
$isDesktop = 0;

// Exclude tablets.
if( $detect->isMobile() && !$detect->isTablet() ){
    $isMobile = 1;
}

// Any tablet device.
if( $detect->isTablet() && !$detect->isMobile()){
    $isTablet = 1;
}

//Desktop
if($isTablet == 0 && $isTablet == 0){
    $isDesktop = 1;
}

//Detecte browser 
$arr_browsers = ["Opera", "Edge", "Chrome", "Safari", "Firefox", "MSIE", "Trident"];  
$agent = $_SERVER['HTTP_USER_AGENT'];
  
$user_browser = '';
foreach ($arr_browsers as $browser) {
    if (strpos($agent, $browser) !== false) {
        $user_browser = $browser;
        break;
    }   
}
  
switch ($user_browser) {
    case 'MSIE':
        $user_browser = 'Internet Explorer';
        break;
  
    case 'Trident':
        $user_browser = 'Internet Explorer';
        break;
  
    case 'Edge':
        $user_browser = 'Microsoft Edge';
        break;
}

//Get info with IP 
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

//Ip Infos 
$country =  ip_info("Visitor", "Country"); // France
$country_code =  ip_info("Visitor", "Country Code"); // FR
$state =  ip_info("Visitor", "State"); // Ile de france 
$city =  ip_info("Visitor", "City"); // Paris

//Code [GET]
if(isset($_GET['code'])){
    if(!empty($_GET['code'])) {

        //Variables
        $code = $_GET['code'];
        $lenghtMax = 5;

        //On vérifie que le code sois composé de 5 charactères
        if(strlen($code) === $lenghtMax) {
            
            //Requête [links_table]
            $req_code = $bdd->prepare('SELECT * FROM links_table WHERE code = ?');
            $req_code->execute(array($code));

            //On vérifie que le code existe 
            $req_code_count = $req_code->rowCount();

            //Si il existe 
            if ($req_code_count > 0) {

                if ($c = $req_code->fetch()) {

                    //Variables
                    $owner_username = $c['owner_username'];
                    $owner_email = $c['owner_email'];
                    $redirect = $c['links_origin'];
                    $title = $c['title'];
                        
                    //Insert
                    $ins = $bdd->prepare('INSERT INTO clicks (code, owner_username, owner_email, isPhone, isTablet, isDesktop, browser, country, country_code, states, city, clicks_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                    $ins->execute(array($code, $owner_username, $owner_email, $isMobile, $isTablet, $isDesktop, $user_browser, $country, $country_code, $state, $city, $time));

                    //Redirect
                    header('Location: '.$redirect);

                }

            }else {

                header('Location: ./404');
            
            }

        }else {

            header('Location: ./404');
        
        }

    }else {

        header('Location: ./404');
    
    }

}else {

    header('Location: ./404');

}


?>