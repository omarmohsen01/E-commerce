<?php
/*
==============================================
=====       manage comments page               
=====you can edit|delete|approve comment from here
==============================================
*/
session_start();

if (isset($_SESSION['UserName'])) {

    $pagetitle = 'comments';
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    
    if ($do == 'manage') {
        //select all users from database
        $stmt = $con->prepare("SELECT 
                                comments.*,items.Name AS ItemName,users.UserName
                             FROM
                                comments
                             INNER JOIN
                                items
                             ON
                                items.ItemID=comments.Item_ID
                             INNER JOIN
                                users
                             ON
                                users.UserID=comments.User_ID
                             ORDER BY
                                ID
                                DESC");
        //excute the query
        $stmt->execute();
        //fetch data
        $rows = $stmt->fetchAll();
        //check if is empty
        if(!empty($rows)){
?>

        <h1 class="text-center">Manage commments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table table-border">
                    <tr>
                        <th>#ID</th>
                        <th>comments</th>
                        <th>About Item</th>
                        <th>User Name</th>
                        <th>Date</th>
                        <th>Control</th>
                    </tr>

                    <?php foreach ($rows as $row) {
                        echo '
                            <tr>
                                <td>' . $row['ID'] . '</td>
                                <td>' . $row['Comment'] . '</td>
                                <td>' . $row['ItemName'] . '</td>
                                <td>' . $row['UserName'] . '</td>
                                <td>' . $row['Date'].'</td>
                                <td>
                                    <a href="comments.php?do=edit&commentid=' . $row['ID'] . '" class="btn btn-success">edit</a>
                                    <a href="comments.php?do=delete&commentid=' . $row['ID'] . '" class="btn btn-danger confirm">delete</a>';
                                    if($row['Status']==0){
                                        echo'<a href="comments.php?do=approve&commentid=' . $row['ID'] . '" class="btn btn-info activate" style="margin-left:5px;">approve</a>';

                                    }
                                echo'</td>
                            </tr>
                        ';
                    } ?>
                </table>
            </div>
        </div>
    <?php
        }else{
            echo'<div class="container">';
                echo'<div class="alert alert-info">There is no such comments to show</div>';
            echo'</div>';
        }

    } elseif ($do == 'edit') {
        //edit page
        //check if Get request commentid is numeric & get the int value
        $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
        //check if the user exist in database
        $stmt = $con->prepare("SELECT * FROM comments WHERE ID= '$commentid'");
        //excute query
        $stmt->execute();
        //fetch data
        $row = $stmt->fetch();
        //row count
        $count = $stmt->rowCount();
        //if there is such ID show form
        if ($count > 0) {
        ?>

            <h1 class="text-center">Edit Comment</h1>

            <div class="container">
                <form class="form-horizontal" method="POST" action="?do=update">
                    <input type="hidden" name="id" value="<?php echo $commentid ?>" />
                    <!-- start username faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label ">Comment</label>
                        <textarea class="form-control" name="comment">
                            <?php echo $row['Comment']; ?>
                        </textarea>
                    </div>
                    <!-- end username faild-->
                    <!-- start btn faild-->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- end btn faild-->
                </form>
            </div>
<?php } else {
            echo '<div class="container">';
            $themsg = '<div class="alert alert-danger">there is no such ID</div>';
            redirctHome($themsg);
            echo '</div>';
            
        }
    } elseif ($do == 'update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";
            //get variable from the form
            $id     = $_POST['id'];
            $comment   = $_POST['comment'];
            
            $stmt = $con->prepare("UPDATE comments SET Comment='$comment' WHERE ID='$id'");
            //excute the query
            $stmt->execute();
            //echo success massage
            if($stmt){
                $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . 'record updated</div>';
                //redirect function
                redirctHome($themsg,'back');
            }else
                $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
        } else {
            $themsg='<div class="alert alert-danger">sorry you cant browse this page </div>';
            redirctHome($themsg);
        }
        echo '</div>';

    } elseif ($do == 'delete') {
        //delete member
        echo '<h1 class="text-center">delete comment</h1>
        <div class="container">';
        //check if get request userid is numirc & get the integer value of it
        $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
        //select all data depend on id
        $check=checkItem('ID','comments',$commentid);

        if ($check > 0) {

            $stmt = $con->prepare("DELETE FROM comments WHERE ID='$commentid'");

            $stmt->execute();
            if($stmt){
                $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . ' record deleted</div>';
                redirctHome($themsg,'back');
            }else
                $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
        }else{
            $themsg= '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirctHome($themsg);
        }
        echo '</div>';
    }elseif($do=='approve'){
        //active member
        echo '<h1 class="text-center">approve member</h1>
        <div class="container">';
        //check if get request commentid is numirc & get the integer value of it
        $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
        //select all data depend on id
        $check=checkItem('ID','comments',$commentid);
        //check if record is returned
        if ($check > 0) {
            //query to update the record in database
            $stmt = $con->prepare("UPDATE comments SET Status=1 WHERE ID='$commentid'");
            //execute the query
            $stmt->execute();
            //check if record updated in database
            if($stmt){
                $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . ' record approved</div>';
                redirctHome($themsg);
            }else
                $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
        }else{
            $themsg= '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirctHome($themsg);
        }
        echo '</div>';

    }
    include $tpl . 'footer.php';
} else {
    header('location:index.php');
    exit();
}
?>