<?php
if(!file_exists('config.php')) {
  header('location: install.php');
  exit();
}

require_once 'libs/db_lib.php';
require_once 'config.php';

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

    <title>Promotion Friend</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    
    <script src='https://www.google.com/recaptcha/api.js'></script>
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

<div id="login" role="complementary" class="col-md-4 col-md-push-4">
	<form action="log.php" method="post" role="form">
		<h2 style="text-align: center;">Sing in</h2><hr>
  	<div class="form-group">
  	<?php echo errologin();?>
	    <label for="textNome" class="control-label">E-Mail</label>
    	<input class="form-control" placeholder="Type your e-mail..." type="text" name="email">
  	</div>
  
  	<div class="form-group">
	    <label for="inputPassword" class="control-label">Password</label>
    	<input type="password" class="form-control" id="inputPassword" placeholder="Type your password..." name="pass">
  	</div>
  	<button type="submit" class="btn btn-primary" style="float: right;">Login</button>
    <a href="#" style="float: left;" onclick="change()">Or sing up</a>
	</form>

</div>
    
<div id="regist" class="col-md-4 col-md-push-4" style="background-color: white; display: none;">

<form action="reg.php" method="post">
	<h2 style="text-align: center;">Sign up to get started</h2><hr>
  <div class="form-group">
  <?php echo erro();?>
    <label class="control-label">Login</label>
    <input class="form-control" placeholder="Choose a user name" value="<?php echo $_GET['name'];?>" type="text" name="name">
  </div>
  
  <div class="form-group">
    <label class="control-label">E-Mail</label>
    <input class="form-control" placeholder="Type your e-mail" value="<?php echo $_GET['email'];?>" type="email" name="email">
  </div>
  
  <div class="form-group">
    <label class="control-label">Password</label>
    <input type="password" class="form-control" placeholder="Type your password" name="pass">
  </div>
  
  <div class="form-group">
    <label class="control-label">Confirm the password</label>
    <input type="password" class="form-control" placeholder="Type your password again" name="pass_r">
  </div>

  <div class="checkbox">
    <label>
      <p>I read the <a href="terms.php" target="_blank">terms of use</a>: <input type="radio" name="terms" value="Y"/></p>
    </label>
  </div>
  
  <div class="g-recaptcha" data-sitekey="<?php echo $G_RECAPTCHA_PUBLIC;?>"></div>
  <button type="submit" class="btn btn-primary" style="float: right;">Sign up</button>
  <a href="#" style="float: left;" onclick="change()">Back to login</a>
</form>

</div>
      
</div>

    <script src="bootstrap/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      var control = 1;
<?php
  if((int)$_GET['erro'] > 0 && (int)$_GET['erro'] <= 10)
    echo "       change();\n";
?>
      function change() {
        if(control == 1) {
          $("#login").css('display', 'none');
          $("#regist").css('display', 'block');
          control = 0;
        }
        else {
          $("#login").css('display', 'block');
          $("#regist").css('display', 'none');
          control = 1;
        }
      }
    </script>

  </body>
</html>
<?php
 function erro() {
  $returns;
  if($_GET["erro"] == '') {return '';}
  if($_GET["erro"] == 1) {
  	$returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> All fields must be filled
	</div>";
  }
  if($_GET["erro"] == 2) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> The username must be at least 4 characters and a maximum of 30 characters
	</div>";
  }
  if($_GET["erro"] == 3) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> Password must be at least 6 characters
	</div>";
  }
  if($_GET["erro"] == 4) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> Username can not have special characters
	</div>";
  }
 if($_GET["erro"] == 5) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> This username already exists
	</div>";
  }
 if($_GET["erro"] == 6) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> The passwords are different
	</div>";
  }
 if($_GET["erro"] == 7) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> Enter a valid email address
	</div>";
  }
 if($_GET["erro"] == 8) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> This E-mail is already being used
	</div>";
  }
 if($_GET["erro"] == 9) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> The terms of use must be accepted
	</div>";
  }
  if($_GET["erro"] == 10) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> reCAPTCHA is wrong
	</div>";
  }
  return $returns;
 }

function errologin() {
  $returns = '';
  if($_GET["erro"] == '') {return '';}
  if($_GET["erro"] == 11) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> All fields must be filled
	</div>";
  }
  if($_GET["erro"] == 12) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> Invalid email
	</div>";
  }
  if($_GET["erro"] == 13) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Houston,</strong> Invalid password
	</div>";
  }
  if($_GET["erro"] == 14) {
  $returns = "  <div class=\"alert alert-danger\" role=\"alert\">
      <strong>Houston,</strong> Your account is banned
  </div>";
  }
  return $returns;
 }

?>