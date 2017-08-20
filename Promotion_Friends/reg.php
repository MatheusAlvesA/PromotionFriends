<?php
/*
 This algorithm is responsible for register the user
*/
include("libs/db_lib.php");

 test();
 $db = new DB();
 $db->register($_POST['name'], sha1($_POST['pass']), $_POST['email']);
// logando o usuario
 session_start();
 $_SESSION["logged"] = 'Y';
 $_SESSION["id"] = $db->id;

 header("location: home.php");

 function test() {
  $bd_temp = new DB();

  if($_POST["name"] == '') {header("location: index.php?erro=1&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  if($_POST["pass"] == '' || $_POST["pass_r"] == '') {header("location: index.php?erro=1&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  if($_POST["email"] == '') {header("location: index.php?erro=1&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}

  if(strlen($_POST["name"]) < 4 || strlen($_POST["name"]) > 30) {header("location: index.php?erro=2&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  if(strlen($_POST["pass"]) < 6) {header("location: index.php?erro=3&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  
  if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email"]) || strlen($_POST["email"]) > 200) {header("location: index.php?erro=7&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  if($bd_temp->email_exist($_POST['email'])) {header("location: index.php?erro=8&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}

  if(!preg_match('/^[a-z\d_]{4,30}$/i', $_POST['name'])) {header("location: index.php?erro=4&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  if($_POST["pass"] != $_POST["pass_r"]) {header("location: index.php?erro=6&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  if($bd_temp->user_exist($_POST['name'])) {header("location: index.php?erro=5&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  
  if($_POST["terms"] != 'Y') {header("location: index.php?erro=9&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  
  $conection = curl_init("https://www.google.com/recaptcha/api/siteverify");
  global $G_RECAPTCHA_PRIVATE;
        curl_setopt($conection, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($conection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conection, CURLOPT_POST, true);
        curl_setopt($conection, CURLOPT_POSTFIELDS, array('secret' => $G_RECAPTCHA_PRIVATE, 'response' => $_POST['g-recaptcha-response'], 'remoteip' => $_SERVER['REMOTE_ADDR']));
        $r = json_decode(curl_exec($conection), true);
 if($r['success'] === false ) {header("location: index.php?erro=10&name=".$_POST["name"].'&email='.$_POST["email"]);exit();}
  unset($bd_temp);
 }
?>