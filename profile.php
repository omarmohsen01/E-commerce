<?php
ob_start();
session_start();
$pagetitle = 'Profile';
include "init.php";
if(isset($_SESSION['name'])){
    $stmt=$con->prepare("SELECT * FROM users WHERE UserName='$sessionUser'");
    $stmt->execute();
    $user=$stmt->fetch();
?>
<h1 class="text-center"><?php echo $sessionUser . '\'s Profile' ?></h1>


<!-- information about the user -->
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li><span>name</span><?php echo ':'.$user['UserName'] ?> </li>
                    <li><span>FullName</span><?php echo ':'.$user['FullName'] ?> </li>
                    <li><span>E-Mail</span><?php echo ':'.$user['Email'] ?> </li>
                    <li><span>Register Date</span><?php echo ':'.$user['Date'] ?> </li>
                    <li><span>Favourite Category</span>:</li>
                </ul>
                <a href="#" class="btn btn-default">Edit Information</a>
            </div>
        </div>
    </div>
</div>


<!-- show the item that the user is put it -->
<div id="ads" class="block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Items</div>
            <div class="panel-body">
            <?php
                $myItem=getAll("*","items","ItemID","","WHERE Member_ID={$user['UserID']}");
                if(!empty($myItem)){
                    foreach($myItem as $item){
                        echo'<div class="col-sm-6 col-md-3">';
                            echo'<div class="thumbnail item-box"><a href="items.php?itemid='.$item['ItemID'].'">';
                                if($item['Approve']==0){
                                    echo'<span class="approve-status">Not Approved</span>';
                                }
                                echo'<span class="price-tag">$'.$item['Price'].'</span>';
                                echo'<img class="img-responsive" src="img.jpg" alt=""/>';
                                echo'<div class="caption">';
                                    echo'<h3>'.$item['Name'].'</h3>';
                                    echo'<p>'.$item['Description'].'</p>';
                                    echo'<div class="date">'.$item['Date'].'</div>';
                                echo'</div>';
                            echo'</a></div>';
                        echo'</div>';
                    }
                }else{
                    echo'there is no ADS to show, Create <a href="newad.php">new AD</a>';
                }
            ?>
            </div>
        </div>
    </div>
</div>


<!-- show the comments that the user posted it  -->
<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My comments</div>
            <div class="panel-body">
                <?php
                    $stmt=$con->prepare("SELECT 
                                            comments.*,items.Name 
                                        FROM 
                                            comments
                                        INNER JOIN
                                            items
                                        ON
                                            items.ItemID=comments.Item_ID
                                        WHERE
                                            User_ID= ? ");
                    $stmt->execute(array($user['UserID']));
                    $comments=$stmt->fetchAll();
                    if(!empty($comments)){
                        foreach($comments as $comment){
                            echo $comment['Comment'].'</br>';
                            echo $comment['Date'].'</br>';
                            echo '('.$comment['Name'].')';
                            echo '</br>';
                            echo '</br>';

                        }
                    }else{
                        echo'there is no comments to show';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php }else{
    header("location: login.php");
}
include_once $tpl . 'footer.php';
ob_end_flush(); ?>