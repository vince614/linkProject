<?php 

//Requête liens http
$req_http = $bdd->prepare('SELECT * FROM links_table WHERE owner_username = ? AND isHTTPS = ? ORDER BY id DESC');
$req_http->execute(array($username,0));
$req_http_count = $req_http->rowCount();

//Si il y as des résultats 
if ($req_http_count > 0) {

    //Afiicher touts les résultats 
    while($l = $req_http->fetch()) {

        //Variables
        $site = explode('//', $l['links_origin'])[1];
        $url = $l['links_origin'];
        $title = $l['title'];
    

?>

<a href="<?=$url ?>" target="_blank"><h4 class="small font-weight-bold"><?=$title ?> <span class="float-right">2 clicks</span></h4></a>
<div class="progress mb-4">
    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0"
        aria-valuemax="100"></div>
</div>

<?php } } ?>