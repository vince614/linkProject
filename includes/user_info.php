<?php 

if(isset($_SESSION['picture']) AND $_SESSION['picture'] != null) {

    $picture = $_SESSION['picture'];

}else {

    $picture = '../assets/img/undraw_profile_pic_ic5t.svg';

}

?>

<div class="left-content-box  content-box-dark">
    <img src="<?=$picture ?>" alt="" class="uk-border-pill profile-img">
    <h4 class="uk-text-center uk-margin-remove-vertical text-light"><?=$username ?></h4>

    
</div>