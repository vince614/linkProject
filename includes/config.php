<?php 

//Var connect to db
$host = "localhost";
$user = "root";
$pass = "";
$stats = "link";

try {

	$bdd = new PDO("mysql:host=$host;dbname=$stats", $user,$pass);
    //echo "Connection ok !";
    
} catch(PDOExeption $e){
	
	//echo "Impossible de se connecter a la base de donnée ! " . $e->getMessage();
}

?>
