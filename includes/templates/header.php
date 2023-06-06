<?php
include"init.php";
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <title><?php gettitle(); ?></title>
        <link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo $css;?>front.css" />
        <link rel="stylesheet" href="<?php echo $css;?>fontawesome.min.css" />
    </head>
    <body>
      <div class="upper-bar">
        <div class="container text-right">
          <?php 
            
            if(isset($_SESSION['name'])){
              ?>
              <img class="my-image img-thumbnail img-circle" src="img.jpg" />
              <div class="btn-group my-info">
                <span class=" dropdown-toggle" data-toggle="dropdown">
                  <?php echo $sessionUser ?>
                  <span class="caret"></span>
                </span>
                <ul class="dropdown-menu">
                  <li><a href="profile.php">My Profile</a></li>
                  <li><a href="#">Edit Profile</a></li>
                  <li><a href="newad.php">New Item</a></li>
                  <li><a href="profile.php#ads">My Items</a></li>
                  <li><a href="logout.php">LogOut</a></li>
                </ul>
              </div>
              <?php
            }else{
          ?>
           <a href="login.php">
            <span class="pull-right">Login|Signup</span>
           </a>
           <?php }?>
        </div>
      </div>
    <nav class="navbar navbar-invers"id="app-nav">
  <div class="container" >
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Home Page</a>
    </div>

    <div class="collapse navbar-collapse" >
      <ul class="nav navbar-nav navbar-right">
        <?php
            foreach(getAll('*','categories','CategoryID','','WHERE Parent=0') as $cat){
                echo'<li><a href="categories.php?pageid='.$cat['CategoryID'].'">'.$cat['Name'].'</a></li>';
            }
        ?>
      </ul>
    </div>
  </div>
</nav>