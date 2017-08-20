<?php
/*
    This is the page that redirects the user to the link corresponding to the clicked banner.
    It also records the click on the system
*/
require_once('libs/db_lib.php');
// Who received the click
$received = $_GET['rec'];
// Who provided the click
$generator = $_GET['gen'];

$db_received = new DB;
if($db_received->read_token($received) === false) {
    header('location: index.php');
    exit();
}
else $db_received->set_clicks_r($db_received->clicks_r + 1); // The number of clicks received is equal to itself plus one

$db_generator = new DB;
if($db_generator->read_token($generator) === true)
    $db_generator->set_clicks_p($db_generator->clicks_p + 1); // The number of clicks provided is equal to itself plus one
    
//Finally redirecting to the link corresponding to the banner
header('location: '.$db_received->get_banner($db_received->id)['url']);
?>