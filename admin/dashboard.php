<?php
ob_start("ob_gzhandler");
session_start();

if (isset($_SESSION['UserName'])) {

    $pagetitle = 'dashboard';
    include 'init.php';

    $NumUser=5;
    $thelatestuser=getAll("*","users","UserID","","","",$NumUser);

    $NumItem=5;
    $thelatestitem=getAll("*","items","ItemID","","","",$NumItem);
?>
    <div class="container home-stats">
        <h1 class="text-center">Dashboard</h1>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="stat st-member">
                    <a href="members.php">
                        Total Member
                        <span><?php echo countItem("UserID", "users"); ?></span>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <a href="members.php?page=pending">
                        pending Member
                        <span><?php echo checkItem("RegStatus", "users", 0); ?></span>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-item">
                    <a href="items.php">
                        Total Item
                        <span><?php echo countItem("ItemID", "items"); ?></span>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comment">
                    <a href="comments.php">
                        Total Comments
                        <span><?php echo countItem("ID", "comments"); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-defualt">
                        <div class="panel-headind">
                            <h4><b> Latest <?php echo $NumUser ?> Registerd Users</b></h4>
                        </div>
                        <div class="panel-body">
                            <?php 
                            if(!empty($thelatestuser)){
                                foreach($thelatestuser as $user)
                                
                                    echo $user['UserName'] . '<br>';
                            }else{
                                echo'<div class="container">';
                                    echo'<div class="alert alert-info">There is no such member to show</div>';
                                echo'</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="panel panel-defualt">
                        <div class="panel-headind">
                            <h4><b> Latest <?php echo $NumItem ?> Inserted Items</b></h4> 
                        </div>
                        <div class="panel-body">
                            <?php 
                            if(!empty($thelatestitem)){
                                foreach($thelatestitem as $item)
                                    echo $item['Name'] . '<br>';
                            }else{
                                echo'<div class="container">';
                                    echo'<div class="alert alert-info">There is no such item to show</div>';
                                echo'</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    include $tpl . 'footer.php';
} else {
    header('location:index.php');
    exit();
}
ob_end_flush();
?>