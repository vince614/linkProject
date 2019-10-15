<?php 

//Variable 
$time = time();
$time_week = $time - 7*24*60*60;
$last_week = $time - 2*7*24*60*60;

//Req New Clicks 
$new_click = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND clicks_time BETWEEN ? AND ?');
$new_click->execute(array($email, $time_week, $time));
$new_click_count = $new_click->rowCount();

//Last month req
$last_click = $bdd->prepare('SELECT * FROM clicks WHERE owner_email = ? AND clicks_time BETWEEN ? AND ?');
$last_click->execute(array($email, $last_week, $time_week));
$last_click_count = $last_click->rowCount();

$test = $bdd->prepare('SELECT code, COUNT(DISTINCT id) AS nb FROM clicks WHERE owner_email = ? GROUP BY code');
$test->execute(array($email));

$array = array();

while ($t = $test->fetch()) {

    $array[$t['code']] = $t['nb'];

}
//Variable 
$max_value = max($array);
$sum_value = array_sum($array);

arsort($array);
$key_of_max = key($array);



//Pourcentage 
$diff = $new_click_count - $last_click_count;
$result = $diff * 100;
$pourc = round($result / ($last_click_count + 1));

$sum = $max_value * 100;
$result_value = round($sum / $sum_value);




?>
<div class="uk-grid uk-grid-divider uk-grid-medium uk-child-width-1-2 uk-child-width-1-4@l uk-child-width-1-2@xl"
    data-uk-grid>
    <div>
        <span class="uk-text-small"><span data-uk-icon="icon:world"
                class="uk-margin-small-right uk-text-primary"></span>New Clicks</span>
        <h1 class="uk-heading-primary uk-margin-remove  uk-text-primary"><?=number_format($new_click_count) ?></h1>
        <div class="uk-text-small">
            <?php if($pourc > 0) { ?>
            <span class="uk-text-success" data-uk-icon="icon: triangle-up"><?=$pourc ?>%</span> more than last week.
            <?php }else { ?>
            <span class="uk-text-danger" data-uk-icon="icon: triangle-down"><?=$pourc ?>%</span> less than last week.
            <?php } ?>
        </div>
    </div>
    <div>

        <span class="uk-text-small"><span data-uk-icon="icon:link"
                class="uk-margin-small-right uk-text-primary"></span>Best link</span>
        <h1 class="uk-heading-primary uk-margin-remove uk-text-primary"><?=$key_of_max ?></h1>
        <div class="uk-text-small">
            <span class="uk-text-success"><?=$result_value ?>%</span> Total clicks.
        </div>

    </div>
</div>