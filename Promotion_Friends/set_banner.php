<?php
include("libs/db_lib.php");

session_start();
if($_SESSION['logged'] !== 'Y') {
  header('location: index.php');
  exit();
}

$db = new DB();
$db->read($_SESSION["id"]);

if($_GET['delete'] == 1) {
	$db->delete_banner();
	header('location: home.php');
}
elseif(isset($_POST['URL'])) upload();

function upload() {
	global $db;

	if($_FILES['banner']['error']) {header("location: home.php?upload_erro=1");exit();}
    if(end(explode('.', $_FILES['banner']['name'])) !== 'png' && end(explode('.', $_FILES['banner']['name'])) !== 'jpg') {header("location: home.php?upload_erro=2");exit();}
    if(!preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i', $_POST['URL'])) {header("location: home.php?upload_erro=3");exit();}
    
    $token = md5($db->name.uniqid());
	$name = $token.'.png';
 	if(move_uploaded_file($_FILES['banner']['tmp_name'], 'banners/'.$name)) {
	 	if(check('banners/'.$name)) {
	 	    $db->create_banner($token, $_POST['URL']);
	 	    convert($name);
	 	    header("location: home.php");
	 	}
		else {
		    @unlink('banners/'.$name);
		    header("location: home.php?upload_erro=2");
		}
	}
	else {header("location: home.php?upload_erro=1");}
}

function convert($directory) {
	return imagepng(imagecreatefromstring(file_get_contents('banners/'.$directory)), 'banners/'.$directory);
}

function check($directory) {
	// Geting Width and Height
	list($W, $H) = getimagesize($directory);
	//cheking
	if($W != 200 || $H != 600) return false;
	else return true;
 }
?>