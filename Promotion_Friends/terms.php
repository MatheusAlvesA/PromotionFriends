<?php
if(!file_exists('config.php')) {
  header('location: install.php');
  exit();
}

require_once 'libs/db_lib.php';
// Check if the user is already logged in
session_start();
if($_SESSION["logged"] == 'Y') {header("location: home.php");exit();}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sign up and receive visitors from various sites for free">
    <meta name="robots" content="index">
	  <meta name="keywords" content="disclosure"/>
    <link rel="icon" href="imgs/favicon.png">

    <title>Terms</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand oficial-font" href="#">Promotion Friend</a>
        </div>
      </div>
    </nav>

    <div class="row">
     <div class="container">
         <h1>You terms of use</h1><hr>
         <p>Enter your terms of use here</p>
         </div>
    </div>

    <script src="bootstrap/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

  </body>
</html>