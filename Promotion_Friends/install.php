<?php
//If user inputs the data to registration this function is activated
if(isset($_POST['name'])) {
  install($_POST['name'], $_POST['pass'], $_POST['passr'], $_POST['email'], $_POST['db_name'], $_POST['db_pass'], $_POST['public_key'], $_POST['private_key']);
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="favicon.ico">

    <title>Installing...</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body style="padding-top: 50px;">

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Promotion Friend</a>
        </div>
      </div>
    </nav>

    <div class="row">
        
        <div role="main" class="col-md-8 col-md-push-2">
            <div style="text-align: center;">
                <h1>Let's start...</h1>
            </div>
            <hr>
            <p class="lead">First let's create an admin user.</p>
            <div class="row">
                <div role="main" class="col-md-8">
                    <form action="install.php" method="post" role="form">
                        <?php
                          if($_GET['erro'] == ERRO::NAME_INVALID) echo '<div class="alert alert-danger" role="alert">Invalid username</div>';
                          if($_GET['erro'] == ERRO::PASS_SMALL) echo '<div class="alert alert-danger" role="alert">Your password must contain six or more caracters</div>';
                          if($_GET['erro'] == ERRO::PASS_NOT_EQUAL) echo '<div class="alert alert-danger" role="alert">Passwords do not match</div>';
                          if($_GET['erro'] == ERRO::EMAIL_IVALID) echo '<div class="alert alert-danger" role="alert">Invalid E-Mail</div>';
                        ?>
                      	<div class="form-group">
                    	    <label class="control-label">User</label>
                        	<input class="form-control" placeholder="Enter your username..." type="text" name="name" value="<?php echo $_GET['name'];?>">
                      	</div>
                      
                      	<div class="form-group">
                    	    <label for="inputPassword" class="control-label">Pass</label>
                        	<input type="password" class="form-control" id="inputPassword" placeholder="Enter your password..." name="pass">
                      	</div>
                      	
                      	<div class="form-group">
                    	    <label for="inputPassword" class="control-label">Repeat</label>
                        	<input type="password" class="form-control" id="inputPassword" placeholder="Enter your password again..." name="passr">
                      	</div>
                    
                      	<div class="form-group">
                    	    <label class="control-label">Email</label>
                        	<input class="form-control" placeholder="Enter your Email..." type="text" name="email" value="<?php echo $_GET['email'];?>">
                      	</div>
                    
                        <hr>
                        
                        <p class="lead">Now I need to know how to access your database</p>
                         <?php
                            if($_GET['erro'] == ERRO::DATABASE_CONNECTION) echo '<div class="alert alert-danger" role="alert">Failed to connect to database</div>';
                          ?>
                        <div class="form-group">
                    	    <label class="control-label">User of database</label>
                        	<input class="form-control" placeholder="Username..." type="text" name="db_name" value="<?php echo $_GET['db_name'];?>">
                      	</div>
                      
                      	<div class="form-group">
                    	    <label for="inputPassword" class="control-label">Password of database</label>
                        	<input type="password" class="form-control" id="inputPassword" placeholder="Password..." name="db_pass">
                      	</div>
                      	
                        <hr>
                      	
                        <p class="lead">Finally we will configure your access to the reCAPTCHA</p>
                        <?php
                          if($_GET['erro'] == ERRO::KEY_INVALID) echo '<div class="alert alert-danger" role="alert">Invalid reCAPTCHA key</div>';
                        ?>
                        <div class="form-group">
                    	    <label class="control-label">Public key</label>
                        	<input class="form-control" placeholder="Public key..." type="text" name="public_key">
                      	</div>
                      
                        <div class="form-group">
                    	    <label class="control-label">Private key</label>
                        	<input class="form-control" placeholder="Private key..." type="text" name="private_key">
                      	</div>
                      	
                        <button type="submit" class="btn-lg btn-primary" style="float: right;">Finish</button>
                	</form>
                </div>
            </div>
        </div>
        
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
/*****************
* FUNCTIONS ZONE *
******************/

/*
  This function checks the data using regular expression
  return 0 if ok
  return a "ERRO" if found some problem
*/
function validate($username, $password, $password_r, $email, $db_username, $db_password, $public_key, $private_key) {
  if(!preg_match('/^[a-z\d_]{4,30}$/i', $username)) return ERRO::NAME_INVALID;
  if(strlen($password) < 6) return ERRO::PASS_SMALL;
  if($password !== $password_r) return ERRO::PASS_NOT_EQUAL;
  if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email) || strlen($email) > 200) return ERRO::EMAIL_IVALID;
  try {
    @$conn = new PDO('mysql:host=localhost;', $db_username, $db_password);
  }
  catch(PDOException $e) { // Some erro
      return ERRO::DATABASE_CONNECTION;
  }
  if(strlen($public_key) <= 0 || strlen($private_key) <= 0) return ERRO::KEY_INVALID;
  return 0; // no errors :)
}

/*
  This function instal the system
*/
function install($username, $password, $password_r, $email, $db_username, $db_password, $public_key, $private_key) {
  $aws = validate($username, $password, $password_r, $email, $db_username, $db_password, $public_key, $private_key);
  if($aws !== 0) { // a erro ocurred
    header('location: install.php?erro='.$aws.'&name='.$username.'&email='.$email.'&db_name='.$db_username);
    return false;
  }
  //Now executing installation
  $conn = make_database($db_username, $db_password);
  first_registration($conn, $username, $password, $email);
  create_config_file($db_username, $db_password, $private_key, $public_key);
  @unlink('install.php');
  header('location: index.php');
  return true;
}
/*
  This is a function that creates the database
  In addition, it also creates user and banner tables
*/
function make_database($user, $pass) {
  try {
    @$conn = new PDO('mysql:host=localhost;', $user, $pass);
  }
  catch(PDOException $e) {
     header('location: install.php?erro='.ERRO::DATABASE_CONNECTION);
     exit();
  }
  $conn->exec(
    "CREATE DATABASE IF NOT EXISTS PROM_Friend;
    GRANT ALL ON PROM_Friend.* TO $user@localhost;
    FLUSH PRIVILEGES;

    use PROM_Friend;
    
    CREATE TABLE IF NOT EXISTS Users (
        id BIGINT NOT NULL AUTO_INCREMENT,
        name VARCHAR(31) NOT NULL,
        email VARCHAR(201) NOT NULL,
        password VARCHAR(100) NOT NULL,
        token VARCHAR(33) NOT NULL,
        registration DATE NOT NULL,
        last_access DATE NOT NULL,
        clicks_received BIGINT NOT NULL DEFAULT 0,
        clicks_provided BIGINT NOT NULL DEFAULT 0,
        views_received BIGINT NOT NULL DEFAULT 0,
        views_provided BIGINT NOT NULL DEFAULT 0,
        user_type INT NOT NULL DEFAULT 0,
        PRIMARY KEY (id)
    )ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS Banners (
      id VARCHAR(100),
      url VARCHAR(500),
      owner BIGINT,
      PRIMARY KEY(id),
      CONSTRAINT fk_owner FOREIGN KEY(owner) REFERENCES Users (id)
    )ENGINE=InnoDB;
    "
  );
  return $conn;
}

function first_registration($conn, $name, $pass, $email) {
    $conn->exec("INSERT INTO Users(name, email, password, registration, last_access, token, user_type) VALUES ('$name', '$email', '".sha1($pass)."', '".date('Y').'-'.date('m').'-'.date('d')."', '".date('Y').'-'.date('m').'-'.date('d')."', '".md5($name.uniqid())."', 1);");
}

function create_config_file($db_username, $db_password, $private_key, $public_key) {
file_put_contents('config.php', '<?php
$G_SQL_USER = "'.$db_username.'";
$G_SQL_PASS = "'.$db_password.'";
$G_RECAPTCHA_PRIVATE = "'.$private_key.'";
$G_RECAPTCHA_PUBLIC = "'.$public_key.'";
?>');
}

class ERRO {
  const NAME_INVALID = 1;
  const PASS_SMALL = 2;
  const PASS_NOT_EQUAL = 3;
  const EMAIL_IVALID = 4;
  const DATABASE_CONNECTION = 5;
  const KEY_INVALID = 6;
}
?>