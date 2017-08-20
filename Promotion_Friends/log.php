<?php
/*
 This algorithm is responsible for logging the user into your account or preventing it if there is an error
*/
include_once("libs/db_lib.php");

  if($_POST["email"] == '' || $_POST["pass"] == '') {header("location: index.php?erro=11");exit();}
  
  $bd_temp = new DB();

 //Test if the username exists
 if(!$bd_temp->email_exist($_POST['email'])) {header("location: index.php?erro=12");exit();}
 $id = $bd_temp->email_id($_POST['email']);
 unset($bd_temp);
 // Got the id now will check the password
 $db = new DB();
 $db->read($id);
  if(sha1($_POST["pass"]) == $db->password) { // Password confers
   if($db->type == -1) {header("location: index.php?erro=14");exit();} // Prevent blocked user
   session_start();
   $_SESSION["id"] = $id;
   $_SESSION["logged"] = 'Y';
   $db->accessed(); // Updates the last access
   header("location: home.php");
  }
 else {header("location: index.php?erro=13");}
?>