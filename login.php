<?php
ob_start();
session_start();
$nonavbar='';
$pagetitle='login';

if(isset($_SESSION['name'])){
    header('location: index.php');
}
include'init.php';

//check if user coming from HTTP post request
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['login'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $hashedpass=sha1($password);

        //check if the user exist in database
        $stmt=$con->prepare("SELECT 
                                UserID, UserName, Password,GroupID
                            FROM 
                                users  
                            WHERE 
                                UserName = '$username' 
                            AND 
                                Password = '$hashedpass'
                            ");
        $stmt->execute();
        $count=$stmt->rowCount();
        $user=$stmt->fetch();
        //if count greater than 0 this mean the database contain record about this username
        if($count>0){
            $_SESSION['name']=$username; //register session name
            $_SESSION['uid']=$user['UserID']; //register session id
            if($user['GroupID']==0){
                header('location: index.php'); //direct to home page
                exit();
            }else{
                header('location: admin/dashboard.php'); //direct to dashboard
                exit();
            }
        }
    }else{
        $formError=array();
        $UserName=$_POST['name'];
        $FullName=$_POST['fullname'];
        $Password=$_POST['pass'];
        $hashpass=sha1($password);
        $Email=$_POST['email'];

        if(isset($Password)){
            if(empty($Password))
                $formError[]='sorry password can not be empty';
        }
        if(isset($Email)){
            $filterdEmail= filter_var($Email,FILTER_VALIDATE_EMAIL);
            if(filter_var($filterdEmail,FILTER_VALIDATE_EMAIL) != true)
                $formError[]='this email is not valid';
        }
        
        if (empty($formError)) {
                //check if user exist in database or not
                $check = checkItem("UserName", "users", $UserName);
                if ($check == 1) 

                    $formError[]= 'Sorry This User Is Exist';

                else {
                    //insert userinfo in database
                    $stmt = $con->prepare("INSERT INTO 
                                                users(UserName,FullName,Password,Email,RegStatus,Date)
                                                VALUES('$UserName','$FullName','$hashpass','$Email',0,now())
                                                ");
                    $stmt->execute();
                    $successMsg= "<div class='alert alert-success'>congrate you registerd as user</div>";
                }
        }
    }
}
?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">LogIn</span>|<span data-class="signup">SignUp</span>
    </h1>
    <!-- start login form -->
    <form class="login" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter Your UserName" required/>
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your password"/>
        <input class="btn btn-primary btn-block" name="login" type="submit" value="LogIn" />
    </form>
    <!-- end login form -->
    <!-- start signup form -->
    <form class="signup" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
        <input class="form-control" type="text" name="name" autocomplete="off" placeholder="Enter Your UserName"/>
        <input class="form-control" type="text" name="fullname" autocomplete="off" placeholder="Full Name"/>
        <input class="form-control" type="password" name="pass" autocomplete="new-password" placeholder="Complex password"/>
        <input class="form-control" type="text" name="email" placeholder="Type a valid E-mail"/>
        <input class="btn btn-success btn-block" name="signup" type="submit" value="SignUp" />
    </form> 
    <!-- end signup form -->
    <div class="the-errors text-center">
        <?php
            if(!empty($formError)){
                foreach($formError as $error)
                    echo '<div class="alert alert-danger" style="margin-top: 11px;    margin: auto;
                        width: 310px;">'.$error.'</div>' ;
            }
            if(isset($successMsg)){
                echo '<div class="alert alert-success" style="margin-top: 11px;    margin: auto;
                width: 310px;">'.$successMsg.'</div>' ;
            }
        ?>
    </div>
</div>

<?php include $tpl.'footer.php';
ob_flush(); ?>