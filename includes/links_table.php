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

        //req click count
        $req_clicks = $bdd->prepare('SELECT COUNT(id) AS clicksCount FROM clicks WHERE code = ?');
        $req_clicks->execute(array($code));

        $data_clicks = $req_clicks->fetch();
        $clicksCount = $data_clicks['clicksCount']; 

        //Protocol
        if($l['isHTTPS'] == 0) {

            $protocol = 'HTTP';

        }else if($l['isHTTPS'] == 1){

            $protocol = 'HTTPS';

        }

        //Date 
        $date = date("Y/m/d", $l['date_link']);


?>

<tr id="<?=$code ?>">
    <td id="edit<?=$code ?>"><?=$title ?></td>
    <td><?=$url ?></td>
    <td><?=$protocol ?></td>
    <td><a href="http://localhost/linky/<?=$code ?>" target="_blank">http://localhost/linky/<?=$code ?></a></td>
    <td><?=$owner_username ?></td>
    <td><?=$date ?></td>
    <td><?=$clicksCount ?></td>
    <td>
        <center>
            <button onclick="edit('<?=$code  ?>','<?=$title ?>')"  class="btn btn-info btn-circle btn-sm" uk-tooltip="Edit"><i class="far fa-edit"></i></button>
            <button onclick="trash('<?=$code  ?>','<?=$title ?>')"  class="btn btn-danger btn-circle btn-sm" uk-tooltip="Delete"><i class="fas fa-trash"></i></button>
        </center>
    </td>
</tr>

<?php } } ?>