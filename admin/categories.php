<?php
/*
==============================================
=====       manage categories page               
=====you can add|edit|delete|approve category from here
==============================================
*/

ob_start();

session_start();

$pagetitle='Categories';

if(isset($_SESSION['UserName'])){
    include'init.php';

    $do=isset($_GET['do'])?$_GET['do']:'manage';
    
    if($do=='manage'){
        $sort='ASC';
        $sort_arr=array('ASC','DESC');

        if(isset($_GET['sort'])&&in_array($_GET['sort'],$sort_arr)){
            $sort=$_GET['sort'];
        }
        $stmt=$con->prepare("SELECT * FROM categories WHERE Parent=0 ORDER BY Ordering $sort");
        $stmt->execute();
        $cats=$stmt->fetchAll();
        ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-defualt">
                <div class="panel-heading" style="background-color: #f0f0f0;">
                    Manage Categories
                    <div class="ordering pull-right">
                        Ordering:
                        <a href="?sort=ASC">[ ASC</a> |
                        <a href="?sort=DESC">DESC ]</a>
                    </div>
                </div>
                <div class="panel-body"  style="padding: 0;">
                    <?php
                        foreach($cats as $cat){
                            echo "<div class='cat' style='padding:10px;
                                position:relative;
                                overflow:hidden;'>";
                                echo "<div class='hidden-button' style='
                                    position: absolute;
                                    top: 15px;
                                    right: 10px;

                                    '>";
                                    echo "<a href='?do=edit&catid=".$cat['CategoryID']."' style='margin-right: 5px;' class='btn btn-xs btn-primary'>Edit</a>";
                                    echo "<a href='?do=delete&catid=".$cat['CategoryID']."' class='btn btn-xs btn-danger'>Delete</a>";
                                echo "</div>";
                                echo '<h3 style="margin: 0 0 10px">'.$cat['Name'].'</h3>';
                                echo '<p>'; if($cat['Description']==''){echo 'That No Description';}else{echo $cat['Description'];} echo'</p>';
                                if($cat['Visibility']==1){echo '<span class="vis" style="background-color: #c0392b;
                                    color: #fff;
                                    padding: 4px 6px;
                                    margin-right: 10px;
                                    border-radius: 6px;">Hidden</span>';}
                                if($cat['AllowComment']==1){echo '<span class="comment" style="background-color: #2c3e50;
                                    color: #fff;
                                    padding: 4px 6px;
                                    margin-right: 10px;
                                    border-radius: 6px;">Comment Diabled</span>';}
                                if($cat['AllowAds']==1){echo '<span class="ads" style="background-color: #d35400;
                                    color: #fff;
                                    padding: 4px 6px;
                                    margin-right: 10px;
                                    border-radius: 6px;">ads Diabled</span>';}
                            echo "</div>";
                            $stmt=$con->prepare("SELECT * FROM categories WHERE Parent={$cat['CategoryID']}");
                            $stmt->execute();
                            $childCats=$stmt->fetchAll();
                            if(!empty($childCats)){
                                echo"<h4 class='child-head'>Child Category</h4>";
                                echo"<ul class='list-unstyled child-cat'>";
                                foreach($childCats as $childCat){
                                    echo"<li>". $childCat['Name'].'<li>';
                                    echo "<a href='?do=edit&catid=".$childCat['CategoryID']."' style='margin-right: 5px;' class='btn btn-xs btn-primary'>Edit</a>";
                                    echo "<a href='?do=delete&catid=".$childCat['CategoryID']."' class='btn btn-xs btn-danger'>Delete</a>";
                                }
                                echo"</ul>";
                                echo "<hr style='margin-top:5px;
                                margin-bottom:5px;'>";
                            }
                        }
                    ?>
                </div>
            </div>
            <a class="btn btn-primary" href="categories.php?do=add">Add New Category</a>
        </div>
        
        <?php

    }elseif($do=='add'){
        ?>
        <!-- add member page -->
        <h1 class="text-center">add Category</h1>
        <div class="container">
            <form class="form-horizontal" method="POST" action="?do=insert">
                <!-- end name faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" required="required" autocomplete="off" placeholder="Name of the category" />
                    </div>
                </div>
                <!-- end name faild-->
                <!-- start description faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="descripe the category" />
                    </div>
                </div>
                <!-- end description faild-->
                <!-- start ordering faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="ordering" class="form-control" placeholder="number to arange the category" />
                    </div>
                </div>
                <!-- end ordering faild-->
                <!-- start parent field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">parent</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="parent">
                            <option value="0">...</option>
                            <?php
                                $stmt=$con->prepare("SELECT * FROM categories WHERE Parent=0");
                                $stmt->execute();
                                $c=$stmt->fetchAll();
                                foreach($c as $cat_with_parent){
                                    echo "<option value='".$cat_with_parent['CategoryID']."'>".$cat_with_parent['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end parent field -->
                <!-- start visible faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1" />
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- end visible faild-->
                <!-- start allow commenting faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" checked />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1" />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- end allow commenting faild-->
                <!-- start allow ads faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked />
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" />
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- end allow commenting faild-->
                <!-- start btn faild-->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- end btn faild-->
            </form>
        </div>
        <?php
    }elseif($do=='insert'){
            //insert page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Category</h1>";
            echo "<div class='container'>";

            //get variable from the form
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $ordering    = $_POST['ordering'];
            $parent      = $_POST['parent'];
            $visibility  = $_POST['visibility'];
            $commenting  = $_POST['commenting'];
            $ads         = $_POST['ads'];

            //update the database  with this info
            //check if no error
            if (empty($formError)) {
                //check if user exist in database or not
                $check = checkItem("Name","categories",$name);
                if ($check == 1) {
                    $themsg= '<div class="alert alert-danger">Sorry This category Is Exist</div>';
                    redirctHome($themsg,'back');
                } else {
                    //query to insert userinfo in database
                    $stmt = $con->prepare("INSERT INTO 
                            categories(Name,Description,Ordering,Parent,Visibility,AllowComment,AllowAds)
                            VALUES('$name','$description','$ordering','$parent','$visibility','$commenting','$ads')
                            ");
                    //execute the query
                    $stmt->execute();
                    //check if record inserted
                    if($stmt){
                        $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . 'record inserted</div>';
                        redirctHome($themsg,'back');
                    }else
                        $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
                }
            }
        }
         else {
            echo '<div class="container">';
            $themsg = '<div class="alert alert-danger">sorry you cant browse this page</div>';
            redirctHome($themsg);
            echo '</div>';
        }
        echo '</div>';
    }elseif($do=='edit'){
        //edit page
        //check if Get request userid is numeric & get the int value
        $catid=isset($_GET['catid']) && is_numeric($_GET['catid']) ?intval($_GET['catid']):0;
        //check if the user exist in database
        $stmt=$con->prepare("SELECT * FROM categories WHERE CategoryID='$catid'");
        //excute query
        $stmt->execute();
        //fetch data
        $row=$stmt->fetch();
        //row count
        $count=$stmt->rowcount();
        //if there is such ID show form
        if($count>0){?>
            <h1 class="text-center">Edit Category</h1>
        <div class="container">
            <form class="form-horizontal" method="POST" action="?do=update">
                <input type="hidden" name='catid' value="<?php echo $catid ?>"
                <!-- end name faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Name of the category" value="<?php echo $row['Name'] ?>"/>
                    </div>
                </div>
                <!-- end name faild-->
                <!-- start description faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="descripe the category" value="<?php echo $row['Description'] ?>"/>
                    </div>
                </div>
                <!-- end description faild-->
                <!-- start ordering faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="ordering" class="form-control" placeholder="number to arange the category" value="<?php echo $row['Ordering'] ?>"/>
                    </div>
                </div>
                <!-- end ordering faild-->
                <!-- start parent field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label ">parent</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="parent">
                            <option value="0">...</option>
                            <?php
                                $stmt=$con->prepare("SELECT * FROM categories WHERE Parent=0");
                                $stmt->execute();
                                $c=$stmt->fetchAll();
                                foreach($c as $cat_with_parent){
                                    echo "<option value='".$cat_with_parent['CategoryID']."'";
                                    if($cat_with_parent['CategoryID']==$row['Parent']){
                                        echo'selected';
                                    }
                                    echo">".$cat_with_parent['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end parent field -->
                <!-- start visible faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($row['Visibility']==0) echo 'checked'?>/>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1" <?php if($row['Visibility']==1) echo 'checked'?>/>
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- end visible faild-->
                <!-- start allow commenting faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" <?php if($row['AllowComment']==0) echo 'checked'?>/>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1" <?php if($row['AllowComment']==1) echo 'checked'?>/>
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- end allow commenting faild-->
                <!-- start allow ads faild-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" <?php if($row['AllowAds']==0) echo 'checked'?>/>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" <?php if($row['AllowAds']==1) echo 'checked'?>/>
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- end allow commenting faild-->
                <!-- start btn faild-->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Update Category" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- end btn faild-->
            </form>
        </div><?php
        }else {
            echo '<div class="container">';
            $themsg = '<div class="alert alert-danger">there is no such ID</div>';
            redirctHome($themsg);
            echo '</div>';
        }
    }elseif($do=='update'){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";
            //get variable from the form
            $id           = $_POST['catid'];
            $name         = $_POST['name'];
            $description  = $_POST['description'];
            $ordering     = $_POST['ordering'];
            $parent       = $_POST['parent'];
            $visibility   = $_POST['visibility'];
            $commenting   = $_POST['commenting'];
            $ads          = $_POST['ads'];

            if(empty($name)){
                echo '<div class="alert alert-danger">Name of category can not be <strong>empty</strong></div>';
            }
            //update the database  with this info
            $stmt = $con->prepare("UPDATE categories SET Name='$name',Description='$description',Ordering='$ordering',
                                Parent='$parent' ,Visibility='$visibility',AllowComment='$commenting',AllowAds='$ads' 
                                WHERE CategoryID='$id'");
            //excute the query
            $stmt->execute();
            if($stmt){
                //echo success massage
                $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . 'record updated</div>';
                //after executing query and upadate date use redircthome function to return you to previous page
                redirctHome($themsg,'back');
            }else
                $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
        } else {
            $themsg='<div class="alert alert-danger">sorry you cant browse this page </div>';
            redirctHome($themsg);
        }
        echo '</div>';
    }elseif($do=='delete'){
        //delete member
        echo '<h1 class="text-center">Delete Categories</h1>
        <div class="container">';
        //check if get request catid is numirc & get the integer value of it
        $id = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        //select all data depend on id
        $check=checkItem('CategoryID','categories',$id);
        //check if this record is exist
        if ($check > 0) {
            //query to delete the record from database 
            $stmt = $con->prepare("DELETE FROM categories WHERE CategoryID='$id'");
            //execute query
            $stmt->execute();
            //chekc if deleted from database
            if($stmt){
                //show success massage
                $themsg= '<div class="alert alert-success">' . $stmt->rowCount() . ' record deleted</div>';
                //return to home using redirect home function
                redirctHome($themsg,'back');
            }else
                //show danger masage if something went wrong
                $themsg='<div class="alert alert-danger">something is wrong, please try again</div>';
        }else{
            //show danger massage if id is wrong
            $themsg= '<div class="alert alert-danger">This ID Is Not Exist</div>';
            //show danger masage if something went wrong
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



