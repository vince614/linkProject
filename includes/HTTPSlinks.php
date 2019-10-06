<?php 

//Requête liens http
$req_https = $bdd->prepare('SELECT * FROM links_table WHERE owner_username = ? AND isHTTPS = ? ORDER BY id DESC');
$req_https->execute(array($username,1));
$req_https_count = $req_https->rowCount();

//Si il y as des résultats 
if ($req_https_count > 0) {

    //Afiicher touts les résultats 
    while($l = $req_https->fetch()) {

        //Variables
        $site = explode('//', $l['links_origin'])[1];
        $url = $l['links_origin'];
        $title = $l['title'];
    

?>

<a href="<?=$url ?>" target="_blank"><h4 class="small font-weight-bold"><?=$title ?><span class="float-right">50 clicks</span></h4></a>
<div class="progress mb-4">
    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0"
        aria-valuemax="100"></div>
</div>

<?php } } ?>