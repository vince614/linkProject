<?php 

//Requête liens
$req_links = $bdd->prepare('SELECT * FROM links_table WHERE owner_username = ? ORDER BY id DESC');
$req_links->execute(array($username));
$req_links_count = $req_links->rowCount();

//Si il y as des résultats 
if ($req_links_count > 0) {

    //Afiicher touts les résultats 
    while($l = $req_links->fetch()) {

        //Variables
        $site = explode('//', $l['links_origin'])[1];
        $url = $l['links_origin'];
        $title = $l['title'];
        $protocol = null;
        $code = $l['code'];
        $owner_username = $l['owner_username'];

        //Protocol
        if($l['isHTTPS'] == 0) {

            $protocol = 'HTTP';

        }else if($l['isHTTPS'] == 1){

            $protocol = 'HTTPS';

        }

        //Date 
        $date = date("Y/m/d", $l['date_link']);


?>

<tr>
    <td><?=$title ?></td>
    <td><?=$url ?></td>
    <td><?=$protocol ?></td>
    <td><a href="#">http://linky/<?=$code ?></a></td>
    <td><?=$owner_username ?></td>
    <td><?=$date ?></td>
    <td>52</td>
</tr>

<?php } } ?>