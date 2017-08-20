<?php
/*
 This class is the only one responsible for accessing and modifying the database
*/
require_once 'config.php';
class DB {
 var $bd; // connection with the database
 var $id; // id of user
 var $name; // name of user
 var $password; // password(sha1)of user 
 var $email; // email
 var $token; // token used to identify the user
 var $type; // banned, normal or admin user
 var $clicks_r; // clicks received by that user
 var $clicks_p; // clicks provided
 var $views_r; // views received
 var $views_p; // views provided
 var $registraion; // date of registration of that user
 var $access; // last access to user account

/*
 The class constructor connects to the database with the name and password of the config.php file
*/
 function __construct() {
   global $G_SQL_USER;
   global $G_SQL_PASS;
  try {
   @$this->bd = new PDO('mysql:host=localhost;dbname=PROM_Friend', $G_SQL_USER, $G_SQL_PASS);
  }
  catch(PDOException $e) {
      header('location: dberro.php');
      exit();
  }
 }
/*
 This function receives a user id and reads all your data
*/
function read($user_id) {
 if(!$user_id) {return false;}
 $consult = $this->bd->prepare('SELECT * FROM Users WHERE id = :id'); // prepare to read
 $consult->execute(array('id' => (int)$user_id)); // reading the data
 $rs = $consult->fetchAll();
 $rs = $rs[0]; // transforming into array
 if(!$rs) {return false;}
 
  $this->id = $rs['id'];
  $this->name = $rs['name'];
  $this->password = $rs['password'];
  $this->email = $rs['email'];
  $this->type = $rs['user_type'];
  $this->token = $rs['token'];
  $this->registration = $this->data($rs['registration']);
  $this->access = $this->data($rs['last_access']);
  $this->views_r = $rs['views_received'];
  $this->views_p = $rs['views_provided'];
  $this->clicks_r = $rs['clicks_received'];
  $this->clicks_p = $rs['clicks_provided'];
 
 return true;
 }
 /*
 This function receives a user id and reads all your data
*/
function read_token($user_tk) {
 if(!$user_tk) {return false;}
 $consult = $this->bd->prepare('SELECT * FROM Users WHERE token = :id'); // prepare to read
 $consult->execute(array('id' => $user_tk)); // reading the data
 $rs = $consult->fetchAll();
 $rs = $rs[0]; // transforming into array
 if(!$rs) {return false;}
 
  $this->id = $rs['id'];
  $this->name = $rs['name'];
  $this->password = $rs['password'];
  $this->email = $rs['email'];
  $this->type = $rs['user_type'];
  $this->token = $rs['token'];
  $this->registration = $this->data($rs['registration']);
  $this->access = $this->data($rs['last_access']);
  $this->views_r = $rs['views_received'];
  $this->views_p = $rs['views_provided'];
  $this->clicks_r = $rs['clicks_received'];
  $this->clicks_p = $rs['clicks_provided'];
 
 return true;
 }
/*
 This function registers a new user
 */
function register($name, $password, $email) {
  if($name == '' || $email == '' || $password == '') return false; //checking
  if($this->user_exist($name)) return false; // if user aredy exists
  
  try {
   $regist = $this->bd->prepare("INSERT INTO Users(name, email, password, registration, last_access, token, user_type) VALUES (:name, :email, :pass, '".date('Y').'-'.date('m').'-'.date('d')."', '".date('Y').'-'.date('m').'-'.date('d')."', '".md5($name.uniqid())."', 0)");
   $regist->execute(array('name' => $name, 'pass' => $password, 'email' => $email)); // done

   $consult = $this->bd->prepare("SELECT id FROM Users WHERE name = :name"); // select id generated
   $consult->execute(array('name' => $name));
   $rs = $consult->fetchAll();
   $this->read($rs[0]['id']); // reading all data from user
   
   return true;
  } 
  catch(PDOException $e) {header('location: dberro.php'); exit();} // some error occured
}

function data($data) {
 $array = explode('-', $data);
 for($x = 0; $x < 3; $x++) {$array[$x] = substr($array[$x], 0, -1);}
 $retorno['y'] = $array[0];
 $retorno['m'] = $array[1];
 $retorno['d'] = $array[2];
 return $retorno;
}
/*
 This function returns if a user exists based on their name
*/
function user_exist($name) {
 try {
  $consult = $this->bd->prepare("SELECT * FROM Users WHERE name = :name");
  $consult->execute(array('name' => $name));
  $array_temp = $consult->fetchAll();
  $array_temp = $array_temp[0];
  if($array_temp['name'] == '') {return false;}
  else {return true;}
 }
 catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 This function returns if a user exists based on their email
*/
function email_exist($email) {
 try {
  $consult = $this->bd->prepare("SELECT * FROM Users WHERE email = :email");
  $consult->execute(array('email' => $email));
  $array_temp = $consult->fetchAll();
  $array_temp = $array_temp[0];
  if($array_temp['name'] == '') {return false;}
  else {return true;}
 }
 catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 This function returns the user id based on your email or returns false if it does not find
*/
function email_id($email) {
 try {
  $consulta = $this->bd->prepare("SELECT id FROM Users WHERE email = :email");
  $consulta->execute(array('email' => $email));
  $array_temp = $consulta->fetchAll();
  $array_temp = $array_temp[0];
  if(!isset($array_temp['id'])) {return false;}
  else {return $array_temp['id'];}
 }
 catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 This function registers a user access
*/
function accessed() {
 try {
  if($this->id == '') {return false;}
  $consulta = $this->bd->prepare("UPDATE Users SET last_access = '".date('Y').'-'.date('m').'-'.date('d')."' WHERE id = :id");
  $consulta->execute(array('id' => (int)$this->id));
  return true;
 }
 catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 This function modifies the password of the user whose id was read in "read"
*/
function set_password($pass) {
 if($this->id == '' || $pass == '') {return false;}
 try{
  $consult = $this->bd->prepare("UPDATE Users SET password=:pass WHERE id = :id");
  $consult->execute(array('id' => $this->id, 'pass' => sha1($pass)));
  return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 Update the User number of views provided
*/
function set_views_p($n) {
 $n = (int)$n;
 if($this->id == '' || $n < 0) {return false;}
 try{
  $consult = $this->bd->prepare("UPDATE Users SET views_provided=:n WHERE id = :id");
  $consult->execute(array('id' => $this->id, 'n' => $n));
  return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 Update the User number of views received
*/
function set_views_r($n) {
 $n = (int)$n;
 if($this->id == '' || $n < 0) {return false;}
 try{
  $consult = $this->bd->prepare("UPDATE Users SET views_received=:n WHERE id = :id");
  $consult->execute(array('id' => $this->id, 'n' => $n));
  return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 Update the User number of clicks provided
*/
function set_clicks_p($n) {
 $n = (int)$n;
 if($this->id == '' || $n < 0) {return false;}
 try{
  $consult = $this->bd->prepare("UPDATE Users SET clicks_provided=:n WHERE id = :id");
  $consult->execute(array('id' => $this->id, 'n' => $n));
  return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 Update the User number of clicks received
*/
function set_clicks_r($n) {
 $n = (int)$n;
 if($this->id == '' || $n < 0) {return false;}
 try{
  $consult = $this->bd->prepare("UPDATE Users SET clicks_received=:n WHERE id = :id");
  $consult->execute(array('id' => $this->id, 'n' => $n));
  return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 Creates a new banner and associates it with the current user
*/
function create_banner($token, $url) {
 if($this->id == '' || $token == '' || $url == '') {return false;}
 try{
  // first lets delete old banner
   $banner = $this->get_banner($this->id);
   if($banner !== false) {
     $delete = $this->bd->prepare("DELETE FROM Banners WHERE owner = :id");
     $delete->execute(array('id' => $this->id)); // done
     @unlink('banners/'.$banner['id'].'.png');
   }
   $regist = $this->bd->prepare("INSERT INTO Banners(id, url, owner) VALUES (:token, :url, :owner)");
   $regist->execute(array('token' => $token, 'url' => $url, 'owner' => $this->id)); // done
   
   return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 deleting banner
*/
function delete_banner() {
 if($this->id == '') {return false;}
 try{
  // first lets delete old banner
   $banner = $this->get_banner($this->id);
   if($banner !== false) {
     $delete = $this->bd->prepare("DELETE FROM Banners WHERE owner = :id");
     $delete->execute(array('id' => $this->id)); // done
     @unlink('banners/'.$banner['id'].'.png');
   }
   return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 This function returns the banner of the current user
 returns false if error or returns a array:
 ['id'], ['url'], ['owner']
*/
function get_banner($id) {
 if($id == '') {return false;}
 try{
  $consulta = $this->bd->prepare("SELECT * FROM Banners WHERE owner = :id");
  $consulta->execute(array('id' => $id));
  
  $array_temp = $consulta->fetchAll();
  $array_temp = $array_temp[0];
  
  if(!isset($array_temp['id'])) {return false;}
  else {return $array_temp;}
 }
 catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 This function returns the entire user table
*/
function ranking() {
  $N = 100; // max return size
  try {
   $consult = $this->bd->prepare("SELECT * FROM Users ORDER BY clicks_provided");
   $consult->execute();
   $matriz = $consult->fetchAll();
   
   usort($matriz, "compare");

   $tamanho = count($matriz);
   for($x = 0;$x < $N && $x < $tamanho; $x++)
    $retorns[$x] = $matriz[$tamanho-1 - $x];
   
   return $retorns;
  } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 Ban the current user
*/
function ban() {
 if($this->id == '') return false;
 try{
  $consulta = $this->bd->prepare("UPDATE Users SET user_type=-1 WHERE id = :id");
  $consulta->execute(array('id' => (int)$this->id));
  
  $this->set_clicks_p(0);
  $this->set_clicks_r(0);
  $this->set_views_p(0);
  $this->set_views_r(0);
 
  return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}
/*
 Restore the current user account
*/
function unban() {
 if($this->id == '') return false;
 try{
  $consulta = $this->bd->prepare("UPDATE Users SET user_type=0 WHERE id = :id");
  $consulta->execute(array('id' => (int)$this->id));
  return true;
 } catch(PDOException $e) {header('location: dberro.php'); return false;}
}

}

function compare($a, $b) {
  if(($a['clicks_provided'] - $a['clicks_received'])*500 + ($a['views_provided'] - $a['views_received']) == ($b['clicks_provided'] - $b['clicks_received'])*500 + ($b['views_provided'] - $b['views_received'])) return 0;
  if(($a['clicks_provided'] - $a['clicks_received'])*500 + ($a['views_provided'] - $a['views_received']) < ($b['clicks_provided'] - $b['clicks_received'])*500 + ($b['views_provided'] - $b['views_received'])) return (-1);
  if(($a['clicks_provided'] - $a['clicks_received'])*500 + ($a['views_provided'] - $a['views_received']) > ($b['clicks_provided'] - $b['clicks_received'])*500 + ($b['views_provided'] - $b['views_received'])) return 1;
}

?>