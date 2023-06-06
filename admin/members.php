<?php
/*
==============================================
=====       manage member page               
=====you can add|edit|delete member from here
==============================================
*/
session_start();

if (isset($_SESSION['UserName'])) {

    $pagetitle = 'members';
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    
    if ($do == 'manage') {
        //this query to show the users not activated
        $query='';
        if(isset($_GET['page']) && $_GET['page']=='pending'){
            $query='AND RegStatus=0';
        }
        //select all users from database
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        //excute the query
        $stmt->execute();
        //fetch data
        $members = $stmt->fetchAll();
        //check if is empty
        if(!empty($members)){

?>

        <h1 class="text-center">Manage Member</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members table table-border">
                    <tr>
                        <th>#ID</th>
                        <th>Avater</th>
                        <th>UserName</th>
                        <th>Email</th>
                        <th>FullName</th>
                        <th>Registered Date</th>
                        <th>Control</th>
                    </tr>

                    <?php foreach ($members as $member) {
                        echo '
                            <tr>
                                <td>' . $member['UserID'] . '</td>
                                <td>';
                                if(empty($member['Avatar'])){
                                    echo'No Image';
                                }else{
                                    echo'<img src="uploads/avatars/' . $member['Avatar'] . '" alt=""';
                                }
                                echo'
                                </td>
                                <td>' . $member['UserName'] . '</td>
                                <td>' . $member['Email'] . '</td>
                                <td>' . $member['FullName'] . '</td>
                                <td>' . $member['Date'].'</td>
                                <td>
                                    <a href="members.php?do=edit&userid=' . $member['UserID'] . '" class="btn btn-success">edit</a>
                                    <a href="members.php?do=delete&userid=' . $member['UserID'] . '" class="btn btn-danger confirm">delete</a>';
                                    if($member['RegStatus']==0){
                                        echo'<a href="members.php?do=active&userid=' . $member['UserID'] . '" class="btn btn-info activate" style="margin-left:5px;">active</a>';

                                    }
                                echo'</td>
                            </tr>
                        ';
                    } ?>
                </table>
            </div>
            <a href="members.php?do=add" class="btn btn-primary">ADD NEW MEMBERS</a>
        </div>
    <?php
        }else{
            echo'<div class="container">';
                echo'<div class="alert alert-info">There is no such member to show</div>';
                echo'<a href="members.php?do=add" class="btn btn-primary">ADD NEW MEMBERS</a>';
            echo'</div>';
        }

         } elseif ($do == 'add') { ?>
        <!-- add member page -->
        <h1 class="text-center">add Member</h1>
        <div class="container">
            <form class="form-horizontal" method="POST" action="?do=insert" enctype="multipart/form-data">
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">UserName</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class="form-control" required="required" autocomplete="off" placeholder="Username To Login Into Shop" />
                    </div>
                </div>
                <!-- end username faild-->
                <!-- start password faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" class="form-control" required="required" autocomplete="password" placeholder="Password Must Be Hard And Complex" />
                    </div>
                </div>
                <!-- end password faild-->
                <!-- start email faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">E-mail</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" required="required" class="form-control" placeholder="Email Must Be Valid" />
                    </div>
                </div>
                <!-- end email faild-->
                <!-- start full name faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="full" required="required" class="form-control" placeholder="Full Name Appear In Your Profile Page" />
                    </div>
                </div>
                <!-- end full name faild-->
                <!-- start full name faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Avatar</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" name="avatar" required="required" class="form-control"/>
                    </div>
                </div>
                <!-- end full name faild-->
                <!-- start btn faild-->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- end btn faild-->
            </form>
        </div>
        <?php
    } elseif ($do == 'insert') {
        //insert page

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Member</h1>";
            echo "<div class='container'>";

            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp  = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            $avatarAllowedExtention = array("jpeg","jpg","png","gif");

            $tmp=explode('.',$avatarName);
            $avatarExtention = strtolower(end($tmp));

            //get variable from the form
            $user   = $_POST['username'];
            $pass   = $_POST['password'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];

            $hashpass = sha1($_POST['password']);
            //validate the form
            $formError = array();
            if (empty($user)) {
                $formError[] = 'UserName cant be <strong>empty</strong>';
            }
            if (empty($pass)) {
                $formError[] = 'password cant be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formError[] = 'Email cant be <strong>empty</strong>';
            }
            if (empty($name)) {
                $formError[] = 'FullName cant be <strong>empty</strong>';
            }
            if (strlen($user) < 4) {
                $formError[] = 'UserName cant be less than <strong>4 character</strong>';
            }
            if (strlen($user) > 25) {
                $formError[] = 'UserName cant be greater than <strong>25 character</strong>';
            }
            if (strlen($name) > 50) {
                $formError[] = 'FullName cant be greater than <strong>25 character</strong>';
            }
            if(!empty($avatarName) && ! in_array($avatarExtention,$avatarAllowedExtention)){
                $formError[] = 'this extention is not<strong>Allowed</strong>';
            }
            if(empty($avatarName) ){
                $formError[] = 'Avatar is <strong>requird</strong>';
            }
            if($avatarSize>4195304){
                $formError[] = 'Avatar cant be larger than <strong>4MB</strong>';
            }
            //loop into error array and echo it if errors exist
            foreach ($formError as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            //update the database  with this info

            //check if no error
            if (empty($formError)) {

                $avatar=rand(0,1000000) . '_' . $avatarName;

                move_uploaded_file($avatarTmp,"uploads\avatars\\".$avatar);

                //check if user exist in database or not
                $check = checkItem("UserName", "users", $user);
                if ($check == 1) {
                    $themsg= '<div class="alert alert-danger">Sorry This User Is Exist</div>';
                    redirctHome($themsg,'back');
                } else {
                    //insert userinfo in database
                    $stmt = $con->prepare("INSERT INTO 
                                                users(UserName,Password,Email,FullName,RegStatus,Date,Avatar)
                                                VALUES('$user','$hashpass','$email','$name',1,now(),'$avatar')
                                                ");
                    $stmt->execute();
                    if($stmt){
                        $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . 'record inserted</div>';
                        redirctHome($themsg,'back');
                    }else
                        $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
                }
            }
        } else {
            echo '<div class="container">';
            $themsg = '<div class="alert alert-danger">sorry you cant browse this page</div>';
            redirctHome($themsg);
            echo '</div>';
        }
        echo '</div>';
    } elseif ($do == 'edit') {
        //edit page
        //check if Get request userid is numeric & get the int value
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        //check if the user exist in database
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID= '$userid'");
        //excute query
        $stmt->execute();
        //fetch data
        $member = $stmt->fetch();
        //row count
        $count = $stmt->rowCount();
        //if there is such ID show form
        if ($count > 0) {
        ?>

            <h1 class="text-center">Edit Member</h1>

            <div class="container">
                <form class="form-horizontal" method="POST" action="?do=update">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                    <!-- start username faild -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label ">UserName</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" value="<?php echo $member['UserName']; ?>" class="form-control" required="required" autocomplete="off" />
                        </div>
                    </div>
                    <!-- end username faild-->
                    <!-- start password faild-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $member['Password'] ?>" />
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" />
                        </div>
                    </div>
                    <!-- end password faild-->
                    <!-- start email faild-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">E-mail</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" required="required" value="<?php echo $member['Email'] ?>" class="form-control" />
                        </div>
                    </div>
                    <!-- end email faild-->
                    <!-- start full name faild-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" required="required" value="<?php echo $member['FullName'] ?>" class="form-control" />
                        </div>
                    </div>
                    <!-- end full name faild-->
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
            $id     = $_POST['userid'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];

            //password trick
            $pass = empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] : $pass = sha1($_POST['newpassword']);

            //validate the form
            $formError = array();
            if (empty($user)) {
                $formError[] = 'UserName cant be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formError[] = 'Email cant be <strong>empty</strong></div>';
            }
            if (empty($name)) {
                $formError[] = 'FullName cant be <strong>empty</strong>';
            }
            if (strlen($user) < 4) {
                $formError[] = 'UserName cant be less than <strong>4 character</strong>';
            }
            if (strlen($user) > 25) {
                $formError[] = 'UserName cant be greater than <strong>25 character</strong>';
            }
            if (strlen($name) > 50) {
                $formError[] = 'FullName cant be greater than <strong>25 character</strong>';
            }
            //loop into error array and echo it if errors exist
            foreach ($formError as $error) {
                echo '<div class="alert alert-danger">$error</div>';
            }
            //update the database  with this info
            //check if no error
            if (empty($formError)) {
                $stmt2=$con->prepare("SELECT * FROM users WHERE UserName=$name AND UserID!=$id");
                $stmt2->execute();
                $count=$stmt2->fetch();
                if($count==1){
                    $themsg= '<div class="alert alert-success">This User Is Exist</div>';
                
                    redirctHome($themsg,'back');
                }else{

                    $stmt = $con->prepare("UPDATE 
                                                users 
                                           SET 
                                                UserName='$user',
                                                Email='$email',
                                                FullName='$name',
                                                Password='$pass'
                                            WHERE 
                                                UserID='$id'");
                    //excute the query
                    $stmt->execute();
                    //echo success massage
                    if($stmt){
                        $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . 'record updated</div>';   
                        redirctHome($themsg,'back');
                    }else{
                        $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
                    }
                }

            }
        } else {
            $themsg='<div class="alert alert-danger">sorry you cant browse this page </div>';
            redirctHome($themsg);
        }
        echo '</div>';
    } elseif ($do == 'delete') {
        //delete member
        echo '<h1 class="text-center">delete member</h1>
        <div class="container">';
        //check if get request userid is numirc & get the integer value of it
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        //select all data depend on id
        $check=checkItem('userid','users',$userid);

        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM users WHERE UserID='$userid'");
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
    }elseif($do=='active'){
        //active member
        echo '<h1 class="text-center">activate member</h1>
        <div class="container">';
        //check if get request userid is numirc & get the integer value of it
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        //select all data depend on id
        $check=checkItem('userid','users',$userid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE users SET RegStatus=1 WHERE UserID='$userid'");
            $stmt->execute();
            if($stmt){
                $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . ' record activated</div>';
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