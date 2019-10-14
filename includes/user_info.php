<?php 

if(isset($_SESSION['picture']) AND $_SESSION['picture'] != null) {

    $picture = $_SESSION['picture'];

}else {

    $picture = '../assets/img/undraw_profile_pic_ic5t.svg';

}

?>

<div class="left-content-box  content-box-dark">
    <img src="<?=$picture ?>" alt="" class="uk-border-circle profile-img">
    <h4 class="uk-text-center uk-margin-remove-vertical text-light"><?=$username ?></h4>

    <div class="uk-position-relative uk-text-center uk-display-block">
        <a href="#" class="uk-text-small uk-text-muted uk-display-block uk-text-center"
            data-uk-icon="icon: triangle-down; ratio: 0.7">Admin</a>
        <!-- user dropdown -->
        <div class="uk-dropdown user-drop"
            data-uk-dropdown="mode: click; pos: bottom-center; animation: uk-animation-slide-bottom-small; duration: 150">
            <ul class="uk-nav uk-dropdown-nav uk-text-left">
                <li><a href="#"><span data-uk-icon="icon: info"></span> Summary</a></li>
                <li><a href="#"><span data-uk-icon="icon: refresh"></span> Edit</a></li>
                <li><a href="#"><span data-uk-icon="icon: settings"></span> Configuration</a></li>
                <li class="uk-nav-divider"></li>
                <li><a href="#"><span data-uk-icon="icon: image"></span> Your Data</a></li>
                <li class="uk-nav-divider"></li>
                <li><a href="#"><span data-uk-icon="icon: sign-out"></span> Sign Out</a></li>
            </ul>
        </div>
        <!-- /user dropdown -->
    </div>
</div>