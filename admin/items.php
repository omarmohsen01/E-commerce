<?php
/*
==============================================
=====       manage Items page               
=====you can add|edit|delete|approve item from here
==============================================
*/
ob_start();
session_start();
$pagetitle='Items';
if(isset($_SESSION['UserName'])){
    include'init.php';

    $do=isset($_GET['do'])?$_GET['do']:'manage';


    if($do=='manage'){
        //select all users from database
        $stmt = $con->prepare("SELECT 
                                items.*,
                                categories.Name 
                             AS 
                                Category_Name,
                                users.UserName
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
                             ORDER BY
                                ItemID
                                DESC");
        //excute the query
        $stmt->execute();
        //fetch data
        $items = $stmt->fetchAll();
         //check if is empty
         if(!empty($items)){
        ?>

        <h1 class="text-center">Manage Item</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table table-border">
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>price</th>
                        <th> Date</th>
                        <th>Category</th>
                        <th>Member</th>
                        <th>Control</th>
                    </tr>

                    <?php foreach ($items as $item) {
                        echo '
                            <tr>
                                <td>' . $item['ItemID'] . '</td>
                                <td>' . $item['Name'] . '</td>
                                <td>' . $item['Description'] . '</td>
                                <td>' . $item['Price'] . '</td>
                                <td>' . $item['Date'].'</td>
                                <td>' . $item['Category_Name'].'</td>
                                <td>' . $item['UserName'].'</td>
                                <td>
                                    <a href="items.php?do=edit&itemid=' . $item['ItemID'] . '" class="btn btn-success">edit</a>
                                    <a href="items.php?do=delete&itemid=' . $item['ItemID'] . '" class="btn btn-danger confirm">delete</a>';
                                    
                                    if($item['Approve']==0){
                                        echo'<a href="items.php?do=approve&itemid=' . $item['ItemID'] . '" 
                                        class="btn btn-info activate" style="margin-left:5px;">Approve</a>';
                                    }
                                echo'</td>
                            </tr>
                        ';
                    } ?>
                </table>
            </div>
            <a href="items.php?do=add" class="btn btn-primary">ADD NEW ITEM</a>
        </div>

    <?php 
            }else{
                echo'<div class="container">';
                    echo'<div class="alert alert-info">There is no such item to show</div>';
                    echo'<a href="items.php?do=add" class="btn btn-primary">ADD NEW ITEM</a>';
                echo'</div>';
            }


    }elseif($do=='add'){?>
        <!-- add item page -->
        <h1 class="text-center">add Item</h1>
        <div class="container">
            <form class="form-horizontal" method="POST" action="?do=insert">
                <!-- start Name faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                        name="name" 
                        class="form-control" 
                        required="required" 
                        placeholder="Name of item" />
                    </div>
                </div>
                <!-- end Name faild-->
                <!-- start description faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                        name="description" 
                        class="form-control" 
                        required="required"  
                        placeholder="description of item" />
                    </div>
                </div>
                <!-- end description faild-->
                <!-- start price faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                        name="price" 
                        class="form-control" 
                        required="required"  
                        placeholder="price of item" />
                    </div>
                </div>
                <!-- end price faild-->
                <!-- start country made faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                        name="country" 
                        class="form-control" 
                        required="required"  
                        placeholder="country made this item" />
                    </div>
                </div>
                <!-- end price faild-->
                <!-- start status made faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- end price faild-->
                <!-- start members faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Members</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="member">
                            <option value="0">...</option>
                            <?php
                                $stmt=$con->prepare("SELECT UserID,UserName FROM users");
                                $stmt->execute();
                                $members=$stmt->fetchAll();
                                foreach($members as $member){
                                    echo "<option value='".$member['UserID']."'>".$member['UserName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end member faild-->
                <!-- start category faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="category">
                            <option value="0">...</option>
                            <?php
                                $stmt=$con->prepare("SELECT CategoryID,Name FROM categories");
                                $stmt->execute();
                                $cats=$stmt->fetchAll();
                                foreach($cats as $cat){
                                    echo "<option value='".$cat['CategoryID']."'>".$cat['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end category faild-->
                <!-- start btn faild-->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Item" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- end btn faild-->
            </form>
        </div>
        <?php
    }elseif($do=='insert'){
        //insert page

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Item</h1>";
            echo "<div class='container'>";

            //get variable from the form
            $name    = $_POST['name'];
            $desc    = $_POST['description'];
            $price   = $_POST['price'];
            $country = $_POST['country'];
            $status  = $_POST['status'];
            $member  = $_POST['member'];
            $category= $_POST['category'];
            //validate the form
            $formError = array();
            if (empty($name)) {
                $formError[] = 'Name of item can\'t be <strong>empty</strong>';
            }
            if (empty($desc)) {
                $formError[] = 'description of items can\'t be <strong>empty</strong>';
            }
            if (empty($price)) {
                $formError[] = 'price can\'t be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formError[] = 'you must fill country of made,it can\'t be <strong>empty</strong>';
            }
            if($status == 0){
                $formError[]='you must choose the <strong>Status</strong>';
            }
            if($member == 0){
                $formError[]='you must choose the <strong>Member</strong>';
            }
            if($category == 0){
                $formError[]='you must choose the <strong>Category</strong>';
            }
            if (strlen($name) > 50) {
                $formError[] = 'Name can\'t be greater than <strong>25 character</strong>';
            }
            //loop into error array and echo it if errors exist
            foreach ($formError as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            //update the database  with this info

            //check if no error
            if (empty($formError)) {
                //insert userinfo in database
                $stmt = $con->prepare("INSERT INTO 
                                            items(Name,Description,Price,CountryMade,Status,Date,Cat_ID,Member_ID)
                                            VALUES('$name','$desc','$price','$country','$status',now(),'$category','$member')
                                            ");
                $stmt->execute();
                if($stmt){
                    $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . 'record inserted</div>';
                    redirctHome($themsg,'back');
                }else
                    $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
            }
        } else {
            echo '<div class="container">';
            $themsg = '<div class="alert alert-danger">sorry you cant browse this page</div>';
            redirctHome($themsg);
            echo '</div>';
        }
        echo '</div>';
    }elseif($do=='edit'){
        //edit page
        //check if Get request userid is numeric & get the int value
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        //check if the user exist in database
        $stmt = $con->prepare("SELECT * FROM items WHERE ItemID= '$itemid'");
        //excute query
        $stmt->execute();
        //fetch data
        $item = $stmt->fetch();
        //row count
        $count = $stmt->rowCount();
        //if there is such ID show form
        if ($count > 0) {
        ?>
         <!-- add item page -->
         <h1 class="text-center">Edit Item</h1>
        <div class="container">
            <form class="form-horizontal" method="POST" action="?do=update">
                <input type="hidden" name="id" value="<?php echo $itemid; ?>"/>
                <!-- start Name faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                        name="name" 
                        class="form-control" 
                        required="required" 
                        placeholder="Name of item"
                        value="<?php echo $item['Name'] ?>" />
                    </div>
                </div>
                <!-- end Name faild-->
                <!-- start description faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                        name="description" 
                        class="form-control" 
                        required="required"  
                        placeholder="description of item" 
                        value="<?php echo $item['Description'] ?>"/>
                    </div>
                </div>
                <!-- end description faild-->
                <!-- start price faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                        name="price" 
                        class="form-control" 
                        required="required"  
                        placeholder="price of item" 
                        value="<?php echo $item['Price'] ?>"/>
                    </div>
                </div>
                <!-- end price faild-->
                <!-- start country made faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                        name="country" 
                        class="form-control" 
                        required="required"  
                        placeholder="country made this item" 
                        value="<?php echo $item['CountryMade'] ?>"/>
                    </div>
                </div>
                <!-- end price faild-->
                <!-- start status made faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="status"/>
                            <option value="0" <?php if($item['Status']==0) echo 'selected'; ?>>...</option>
                            <option value="1" <?php if($item['Status']==1) echo 'selected'; ?>>New</option>
                            <option value="2" <?php if($item['Status']==2) echo 'selected'; ?>>Like New</option>
                            <option value="3" <?php if($item['Status']==3) echo 'selected'; ?>>Used</option>
                            <option value="4" <?php if($item['Status']==4) echo 'selected'; ?>>Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- end price faild-->
                <!-- start members faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Members</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="member"/>
                            <option value="0">...</option>
                            <?php
                                $stmt=$con->prepare("SELECT UserID,UserName FROM users");
                                $stmt->execute();
                                $members=$stmt->fetchAll();
                                foreach($members as $member){
                                    echo "<option value='".$member['UserID']."'"; 
                                    if($item['Member_ID']==$member['UserID']){echo 'selected';}
                                    echo ">".$member['UserName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end member faild-->
                <!-- start category faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="category" value="<?php echo $row['Cat_ID'] ?>">
                            <option value="0">...</option>
                            <?php
                                $stmt=$con->prepare("SELECT CategoryID,Name FROM categories");
                                $stmt->execute();
                                $cats=$stmt->fetchAll();
                                foreach($cats as $cat){
                                    echo "<option value='".$cat['CategoryID']."'";
                                    if($item['Cat_ID']==$cat['CategoryID']){echo 'selected';}
                                    echo ">".$cat['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end category faild-->
                <!-- start btn faild-->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Update Item" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- end btn faild-->
            </form>
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
                                    Item_ID=$itemid");
            //excute the query
            $stmt->execute();
            //fetch data
            $rows = $stmt->fetchAll();
            if(!empty($rows)){
    ?>

            <h1 class="text-center">Manage <?php echo $item['Name']?> commments</h1>
                <div class="table-responsive">
                    <table class="main-table table table-border">
                        <tr>
                            <th>comments</th>
                            <th>User Name</th>
                            <th>Date</th>
                            <th>Control</th>
                        </tr>

                        <?php foreach ($rows as $row) {
                            echo '
                                <tr>
                                    <td>' . $row['Comment'] . '</td>
                                    <td>' . $row['UserName'] . '</td>
                                    <td>' . $row['Date'].'</td>
                                    <td>
                                        <a href="comments.php?do=edit&commentid=' . $row['ID'] . '" class="btn btn-success">edit</a>
                                        <a href="comments.php?do=delete&commmentid=' . $row['ID'] . '" class="btn btn-danger confirm">delete</a>';
                                        if($row['Status']==0){
                                            echo'<a href="comments.php?do=approve&commentid=' . $row['ID'] . '" class="btn btn-info activate" style="margin-left:5px;">approve</a>';

                                        }
                                    echo'</td>
                                </tr>
                            ';
                        } ?>
                    </table>
                </div>
                <?php } ?>
            </div>
<?php } else {
            echo '<div class="container">';
            $themsg = '<div class="alert alert-danger">there is no such Item</div>';
            redirctHome($themsg);
            echo '</div>';
            
        }
    }elseif($do=='update'){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";
            //get variable from the form
            $id          = $_POST['id'];
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $price       = $_POST['price'];
            $country     = $_POST['country'];
            $status      = $_POST['status'];
            $member      = $_POST['member'];
            $category    = $_POST['category'];

            //validate the form
            $formError = array();
            if (empty($name)) {
                $formError[] = 'Name of item can\'t be <strong>empty</strong>';
            }
            if (empty($description)) {
                $formError[] = 'description of items can\'t be <strong>empty</strong>';
            }
            if (empty($price)) {
                $formError[] = 'price can\'t be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formError[] = 'you must fill country of made,it can\'t be <strong>empty</strong>';
            }
            if($status == 0){
                $formError[]='you must choose the <strong>Status</strong>';
            }
            if($member == 0){
                $formError[]='you must choose the <strong>Member</strong>';
            }
            if($category == 0){
                $formError[]='you must choose the <strong>Category</strong>';
            }
            if (strlen($name) > 50) {
                $formError[] = 'Name can\'t be greater than <strong>25 character</strong>';
            }
            //loop into error array and echo it if errors exist
            foreach ($formError as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            //update the database  with this info
            //check if no error
            if (empty($formError)) {

                $stmt = $con->prepare("UPDATE items SET 
                                            Name='$name',Description='$description',Price='$price',
                                            CountryMade='$country',Status='$status',Cat_ID='$category',Member_id='$member'
                                             WHERE ItemID='$id'");

                //excute the query
                $stmt->execute();
                if($stmt){
                    //echo success massage
                    $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . 'record updated</div>';
                    redirctHome($themsg,'back');
                }else
                    $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
            }
        } else {
            $themsg='<div class="alert alert-danger">sorry you cant browse this page </div>';
            redirctHome($themsg);
        }
        echo '</div>';
    }elseif($do=='delete'){
        //delete member
        echo '<h1 class="text-center">delete member</h1>
        <div class="container">';
        //check if get request userid is numirc & get the integer value of it
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        //select all data depend on id
        $check=checkItem('itemid','items',$itemid);

        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM items WHERE ItemID='$itemid'");
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
        //approve member
        echo '<h1 class="text-center">Approve Item</h1>
        <div class="container">';
        //check if get request userid is numirc & get the integer value of it
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        //select all data depend on id
        $check=checkItem('ItemID','items',$itemid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE items SET Approve=1 WHERE ItemID='$itemid'");
            $stmt->execute();
            if($stmt){
                $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . ' record approved</div>';
                redirctHome($themsg,'back');
            }else
                $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
        }else{
            $themsg= '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirctHome($themsg);
        }
        echo '</div>';
    }
    include $tpl.'footer.php';
}else{
    header('location: index.php');
    exit();
}
ob_end_flush();

?>