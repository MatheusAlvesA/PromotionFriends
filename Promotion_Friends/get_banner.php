<?php
/*
    This is the page that creates the banner to be downloaded in iframe
*/
require_once('libs/db_lib.php');
if($choose_one === false || $_GET['tk'] == '') exit(); // fail

$choose_one = get_banner();//Choosing a banner
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
       <a href="<?php echo $choose_one['url'];?>" target="_blank">
           <img src="banners/<?php echo $choose_one['banner'];?>.png" width="100%" height="100%" /> 
        </a>
    </body>
</html>
<?php
    $user = new DB;
    if($user->read_token($_GET['tk']) && $user->type >= 0) {
        $user->set_views_p($user->views_p + 1); // This user provided another preview
    }
    
function get_banner() {
    $db = new DB;
    $table = $db->ranking(); // Receiving the ranking of the best user
    if(count($table) == 0) 
        return false;
        
    $choose_one = $table[rand(0, count($table)-1)]; // Randomly choosing one of the best
    $returns['url'] = 'https://'.$_SERVER['SERVER_NAME'].'/click.php?gen='.urlencode($_GET['tk']).'&rec='.$choose_one['token'];
    $returns['banner'] = $db->get_banner($choose_one['id'])['id'];
    
    for($loop = 0;
        ($db->get_banner($choose_one['id']) === false // If the chosen one does not have a banner
        || $choose_one['user_type'] < 0) //If the chosen one is banned
        && $loop < 100; //If tried too many times
    $loop++) {
        $choose_one = $table[rand(0, count($table)-1)];
        $returns['url'] = 'https://'.$_SERVER['SERVER_NAME'].'/click.php?gen='.urlencode($_GET['tk']).'&rec='.$choose_one['token'];
        $returns['banner'] = $db->get_banner($choose_one['id'])['id'];
    }
    if($loop >= 100) return false;
    
    if($db->read($choose_one['id'])) {
        $db->set_views_r($db->views_r + 1);
    }
    
    return $returns;
}
?>