<?php
include("libs/db_lib.php");

session_start();
if($_SESSION['logged'] !== 'Y') {
  header('location: index.php');
  exit();
}

$db = new DB();
$db->read($_SESSION["id"]);

$html_banner = html_banner($db);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="imgs/favicon.png">
    <title>Home - Promotion Friend</title>
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
            <li class="active"><a href="#">Home</a></li>
            <li><a href="change_pass.php">Change Password</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if($db->type == 1) echo '<li><a href="admin.php">Admin</a></li>';?>
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
      <h1><?php echo $db->name;?></h1><hr>
      
      <div class="panel panel-success">
        <div class="panel-heading" style="text-align: center;">
          <b>You Received</b>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Clicks Received</th>
                  <th>Views Received</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo $db->clicks_r;?></td>
                  <td><?php echo $db->views_r;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="panel-footer">
          <small>We advertise your banner on several sites according to your performance in the panel "You Provided"</small>
        </div>
      </div>
      
      <div class="panel panel-info">
        <div class="panel-heading" style="text-align: center;">
          <b>You Provided</b>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Clicks Provided</th>
                  <th>Views Provided</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo $db->clicks_p;?></td>
                  <td><?php echo $db->views_p;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="panel-footer">
          <small>Copy and paste this code into your site to start stitching points:</small>
          <br>
          <small>&lt;iframe src=&quot;<?php echo 'https://'.$_SERVER['SERVER_NAME'];?>/get_banner.php?tk=<?php echo $db->token;?>&quot; frameBorder=&quot;0&quot; width=&quot;200&quot; height=&quot;600&quot;&gt;&lt;p&gt;Your browser does not support iframes.&lt;/p&gt;&lt;/iframe&gt;</small>
        </div>
      </div>
      
      <div class="panel panel-primary">
        <div class="panel-heading" style="text-align: center;">
          <b>Your Banner</b>
        </div>
        <div class="panel-body">
          <?php echo $html_banner;?>
        </div>
      </div>
  </div>
    
</div>

    <script src="bootstrap/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
<?php

function html_banner($db) {
  $banner = $db->get_banner($db->id);
  if($banner !== false) {
    return '          <img src="banners/'.$banner['id'].'.png" width="100px" height="300px" style="float: left; margin-right: 5px;"/>
          <p class="lead">This is your banner. It is being shown on our partner sites in proportion to the number of clicks and views you generate.
          <br>The link that this banner points to is: '.$banner['url'].'
          <br><a href="set_banner.php?delete=1"><button class="btn btn-danger" style="float: right;">Delete</button></a>';
  }
  else {
    return erro_upload().'
          <form method="post" action="set_banner.php" enctype="multipart/form-data">
            <div class="form-group">
              <p>Submit an image 600px high and 200px wide in .png or .jpg format.</p>
              <input type="file" name="banner" />
            </div>
            <div class="form-group">
              <label class="control-label">Paste the URL you want to publish</label>
              <input type="text" class="form-control" placeholder="Paste your link" name="URL">
            </div>
            <button type="submit" class="btn btn-primary" style="float: right;">UPLOAD</button>
          </form>';
  }
}

function admin_tk() {
  $db_tmp = new DB;
  $db_tmp->read(1);
  return $db_tmp->token;
}
// This function detects the variable error and returns the reason
function erro_upload() {
  $returns = '';
  if($_GET["upload_erro"] == '') {return '';}
  
  if($_GET["upload_erro"] == 1) {
    $returns = "<div class=\"alert alert-danger\" role=\"alert\">Upload failed</div>";
  }
  
  if($_GET["upload_erro"] == 2) {
    $returns = "<div class=\"alert alert-danger\" role=\"alert\">File is invalid or corrupted</div>";
  }
  
  if($_GET["upload_erro"] == 3) {
    $returns = "<div class=\"alert alert-danger\" role=\"alert\">Invalid url.<br> <small>The format must contain http:// or https:// at startup</small></div>";
  }

  return $returns;
 }
 ?>