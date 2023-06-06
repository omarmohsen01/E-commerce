<?php 
session_start();
$nonavbar='';
$pagetitle='login';

if(isset($_SESSION['UserName'])){
    header('location: dashboard.php');
}
include"init.php";

//check if user coming from HTTP post request
if($_SERVER['REQUEST_METHOD']=='POST'){
    $username=$_POST['name'];
    $password=$_POST['password'];
    $hashedpass=sha1($password);

    //check if the user exist in database
    $stmt=$con->prepare("SELECT 
                            UserID, UserName, Password
                        FROM 
                            users  
                        WHERE 
                            UserName = '$username' 
                        AND 
                            Password = '$hashedpass'
                        AND
                            GroupID=1
                        LIMIT 1");
    $stmt->execute();
    $row=$stmt->fetch();
    $count=$stmt->rowCount();
    //if count greater than 0 this mean the database contain record about this username
    if($count>0){
        $_SESSION['UserName']=$username; //register session name
        $_SESSION['ID']=$row['UserID'];  //register session id
        header('location: dashboard.php'); //direct to dashboard
        exit();
    }
}

?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control input-lg" type="text" name="name" placeholder="UserName" autocomplete="off"/>
    <input class="form-control input-lg" type="password" name="password" placeholder="Password" autocomplete="new-password"/>
    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Login"/>
</form>

<?php include_once $tpl.'footer.php';?>