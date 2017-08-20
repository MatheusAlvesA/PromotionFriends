<?php
include("libs/db_lib.php");

session_start();
if($_SESSION['logged'] !== 'Y') {
  header('location: index.php');
  exit();
}

$db = new DB();
$db->read($_SESSION["id"]);

if(isset($_POST['o_pass'])) {
    change($_POST['o_pass'], $_POST['n_pass'], $_POST['rn_pass']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="imgs/favicon.png">
    
    <title>Change Password - Promotion Friend</title>
    
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand oficial-font" href="#">Promotion Friend</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="#">Change Password</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Exit</a></li>
          </ul>
        </div>
      </div>
    </nav>

<div class="container-fluid">
    <div class="row">
      <div role="main" class="col-md-6 col-md-push-3">
          <div class="panel panel-primary" style="position: relative; top: 10px;">
            <div class="panel-heading" style="text-align: center;">
              <b>Change your password</b>
            </div>
            <div class="panel-body">
                <?php echo callback();?>
              	<form action="change_pass.php" method="post" role="form">
                  	<div class="form-group">
                	    <label class="control-label">Old Password</label>
                    	<input type="password" class="form-control" placeholder="old password" name="o_pass">
                  	</div>
                  	<div class="form-group">
                	    <label class="control-label">New Password</label>
                    	<input type="password" class="form-control" placeholder="New password" name="n_pass">
                  	</div>
                 	<div class="form-group">
                	    <label class="control-label">Repeat New Password</label>
                    	<input type="password" class="form-control" placeholder="New password again" name="rn_pass">
                  	</div>
                  	<button type="submit" class="btn btn-danger" style="float: right;">Change</button>
            	</form>
            </div>
          </div>
      </div>
    </div>
</div>
    <script src="bootstrap/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
function change($old, $new, $repeat) {
  global $db;
  if($old == '' || $new == '') {header("location: change_pass.php");exit();}

  if(sha1($old) === $db->password) { // Password confers
   if($new !== $repeat) {header('location: change_pass.php?erro=2');exit();}
   if(strlen($new) < 6) {header('location: change_pass.php?erro=3');exit();}
   
   $db->set_password($new);
   header("location: change_pass.php?success=1");
  }
 else {header("location: change_pass.php?erro=1");}
}

function callback() {
  $returns = '';
  if($_GET["erro"] == 1) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Ops,</strong> Old password does not match
	</div>";
  }
  if($_GET["erro"] == 2) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Ops,</strong> Passwords do not match
	</div>";
  }
  if($_GET["erro"] == 3) {
  $returns = "	<div class=\"alert alert-danger\" role=\"alert\">
  		<strong>Ops,</strong> Password is too short
	</div>";
  }
  if($_GET["success"] == 1) {
  $returns = "  <div class=\"alert alert-success\" role=\"alert\">
      <strong>Success,</strong> Your password has been changed
  </div>";
  }
  return $returns;
 }
?>