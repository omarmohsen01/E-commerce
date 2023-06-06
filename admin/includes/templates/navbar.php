<?php include'header.php'; ?>

<nav class="navbar navbar-invers">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
        <li><a class="navbar-brand" href="dashboard.php">Home</a></li>
        <li ><a href="members.php">Members</a></li>
        <li ><a href="categories.php">Categories</a></li>
        <li ><a href="items.php">Items</a></li>
        <li ><a href="comments.php">Comments</a></li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Omar</a>
          <ul class="dropdown-menu" style="color:black;">
            <li><a href="../index.php">Visit Shop</a></li>
            <li><a href="members.php?do=edit&userid=<?php echo $_SESSION['ID'] ?> ">Edit profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="logout.php">LogOut</a></li>            
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>