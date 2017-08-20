<?php
include("libs/db_lib.php");

session_start();
if($_SESSION['logged'] !== 'Y') {
  header('location: index.php');
  exit();
}

$db = new DB();
$db->read($_SESSION["id"]);
if($db->type != 1) {
  header('location: home.php');
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
    <title>Admin - Promotion Friend</title>
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
            <li><a href="change_pass.php">Change Password</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="#">Admin</a></li> 
            <li><a href="logout.php">Exit</a></li>
          </ul>
        </div>
      </div>
    </nav>

<div class="row">

<div id="example" class="col-md-3"> 
  <iframe class="visible-md-block visible-lg-block" src="<?php echo 'https://'.$_SERVER['SERVER_NAME'];?>/get_banner.php?tk=<?php echo admin_tk();?>" frameBorder="0" width="200" height="600"><p>Your browser does not support iframes.</p></iframe>
</div>
  <div role="main" class="col-md-6">
    <div id="top" class="row"> 
      <div class="col-md-9 col-md-push-3">
      <form action="admin.php" method="get">
      	<h2>Ban by email</h2>
      	<?php echo ban($_GET['ban']);?>
        <div class="input-group h2">
            <input name="ban" class="form-control" id="search" type="text" placeholder="Email...">
            <span class="input-group-btn">
                <button class="btn btn-danger" type="submit">
                    <span class="glyphicon glyphicon-heart"></span>
                </button>
            </span>
        </div>
      </form>
      </div>
    </div>
    
    <div id="list" class="row">
        <div class="table-responsive col-md-12">
            <table class="table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Name</th>
                        <th>Action</th>
                     </tr>
                </thead>
                <tbody>
     
                    <?php echo list_users();?>
     
                </tbody>
             </table>
        </div>
    </div>
  </div>
    
</div>

    <script src="bootstrap/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
function admin_tk() {
  $db_tmp = new DB;
  $db_tmp->read(1);
  return $db_tmp->token;
}

function list_users() {
 $db = new DB();
 $ranking = $db->ranking();
 if(count($ranking) == 0) {return '<tr><td colspan="2"><b>There are no posts to display</b></td></tr>';}
 for($x = 0;$x < count($ranking);$x++) {
    if($ranking[$x]['user_type'] >= 0) $button = '<a href="admin.php?ban='.$ranking[$x]['email'].'"><button class="btn btn-danger">BAN</button></a>';
    else $button = '<a href="admin.php?ban='.$ranking[$x]['email'].'"><button class="btn btn-success">UNBAN</button></a>';
   $retorns .= '<tr><td align="center"><b>'.($x+1).'°</b></td><td align="right"><b>'.$ranking[$x]['name'].'</b></td><td>'.$button.'</td></tr>'."\n";
 }
 
 return $retorns;
}

function ban($email) {
  if($email == '') {return '';}
  $db = new DB();
  if($db->read($db->email_id($email)) == false) return '<div class="alert alert-danger" role="alert">User not Found</div>';
  
  if($db->type >= 0) {
      $db->ban();
      return '<div class="alert alert-success" role="alert">'.$db->name.' is now banned</div>';
  }
  else {
    $db->unban();
    return '<div class="alert alert-success" role="alert">'.$db->name.' released</div>';
  }
}
?>