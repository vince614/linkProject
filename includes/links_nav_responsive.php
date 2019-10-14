<?php 

//Requête liens
$req_links = $bdd->prepare('SELECT * FROM links_table WHERE owner_email = ? ORDER BY id DESC');
$req_links->execute(array($email));
$req_links_count = $req_links->rowCount();

if($req_links_count > 0) {

    while ($l = $req_links->fetch()) {


?>
<li class="uk-parent">
    <a><span data-uk-icon="icon: link" class="uk-margin-small-right"></span><?=$l['title'] ?></a>
    <ul class="uk-flex uk-flex-center uk-child-width-1-4 uk-margin-small-top uk-margin-small-bottom uk-nav-sub">
        <li>
            <a href="./<?=$l['code'] ?>" class="uk-icon-link" data-uk-icon="icon: forward" title="View" data-uk-tooltip></a>
        </li>
        <li>
            <a onclick="copy('<?=$l['code']  ?>')" class="uk-icon-link" data-uk-icon="icon: copy" title="Copy" data-uk-tooltip></a>
        </li>
        <li>
            <a onclick="edit('<?=$l['code']  ?>','<?=$l['title'] ?>')" class="uk-icon-link" data-uk-icon="icon: pencil" title="Edit" data-uk-tooltip></a>
        </li>
        <li>
            <a onclick="trash('<?=$l['code']  ?>','<?=$l['title'] ?>')" class="uk-icon-link" data-uk-icon="icon: trash" title="Delete" data-uk-tooltip></a>
        </li>
    </ul>
</li>

<?php } } ?>