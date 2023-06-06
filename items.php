<?php
ob_start();
session_start();
$pagetitle = 'Item';
include "init.php";

//check if Get request userid is numeric & get the int value
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
//check if the user exist in database
$stmt = $con->prepare("SELECT
                             items.*,categories.Name AS Cat_Name,users.UserName
                        FROM
                             items
                        INNER JOIN
                            categories
                        ON
                            categories.CategoryID=items.Cat_ID
                        INNER JOIN
                            users
                        ON
                            users.UserID=items.Member_ID
                        WHERE
                             ItemID= '$itemid'
                        AND 
                            Approve=1");
//excute query
$stmt->execute();
//fetch data
$item = $stmt->fetch();

$count=$stmt->rowCount();
if($count>0){

    //row count
    $count = $stmt->rowCount();
    //if there is such ID show form
    ?>
    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="img.jpg" alt=""/>   
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['Name'] ?></h2>
                <p><?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">
                    <li><span>Added Date</span><?php echo ':'.$item['Date'] ?></li>
                    <li><span>price</span><?php echo ':'.$item['Price'] ?></li>
                    <li><span>Made IN</span><?php echo ':'.$item['CountryMade'] ?></li>
                    <li><span>Category</span><a href="categories.php?pageid=<?php echo $item['Cat_ID']?>"><?php echo ':'.$item['Cat_Name'] ?></a></li>
                    <li><span>Added By</span><a href="#"><?php echo ':'.$item['UserName'] ?></a></li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <?php 
        if(isset($_SESSION['name'])){

        
        ?>
        <!-- start add comment -->
        <div class="row">
            <div class="col-md-offset-3">
                <div class="add-comment">
                    <h3>Add Your Comment</h3>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?itemid='.$item['ItemID'] ?>" >
                        <textarea name="comment" required></textarea>
                        <input class="btn btn-primary" type="submit" value="Add Comment">
                    </form>
                    <?php
                        if($_SERVER['REQUEST_METHOD']=='POST'){
                            $comment= $_POST['comment'];
                            $memberid= $_SESSION['uid'];
                            $itemid= $item['ItemID'];

                            if(!empty($comment)){
                                $stmt=$con->prepare("INSERT INTO comments(Comment,Status,Date,Item_ID,User_ID) VALUES('$comment',0,NOW(),'$itemid','$memberid')");
                                $stmt->execute();

                                if($stmt){
                                    echo '<div class="alert alert-success">Comment Is Posted</div>';
                                }else
                                    echo'<div class="alert alert-danger">something is wrong, please try again</div>';
                            }
                        }       
                    ?>
                </div>
            </div>
        </div>
        <?php }else{
            echo '<div class="text-center">See personalized recommendations</div>';
            echo '<div class="text-center login-btn"><a href="login.php" class="btn btn-success"><span>
            Log-In/Register</a></div>';
        }?>
        <!-- end add comment -->
        <hr class="custom-hr">
        <?php
            //select all users from database
            $stmt = $con->prepare("SELECT 
                                    comments.*,users.UserName
                                FROM
                                    comments
                                INNER JOIN
                                    users
                                ON
                                    users.UserID=comments.User_ID
                                WHERE
                                    Item_ID='$itemid'
                                AND
                                    Status=1
                                ORDER BY
                                    ID
                                    DESC");
            //excute the query
            $stmt->execute();
            //fetch data
            $comments = $stmt->fetchAll();

            foreach($comments as $comment){
                echo '<div class="comment-box">';
                    echo'<div class="row">';
                        echo '<div class="col-sm-2 text-center">';
                            echo'<img class="img-responsive img-thumbnail center-block img-circle" src="img.jpg" alt=""/>';
                            echo $comment['UserName'];
                        echo'</div>';

                        echo'<div class="col-sm-10"><p class="lead">'.$comment['Comment'].'</p></div>';
                    echo'</div>';
                echo'</div>';
                echo'<hr class="custom-hr">';
            }
    echo'</div>';

}else{
    echo'<div class="alert alert-danger">there\'s no such Item or item is under approvel</div>';
}

include_once $tpl . 'footer.php';
ob_end_flush(); ?>