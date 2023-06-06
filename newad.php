<?php
ob_start();
session_start();
$pagetitle = 'New Ad';
include "init.php";
if(isset($_SESSION['name'])){
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $formErrors=array();
        $member=$_SESSION['uid'];
        $title=$_POST['name'];

        $desc=$_POST['description'];

        $price=filter_var($_POST['price'],
            FILTER_SANITIZE_NUMBER_FLOAT,["flags"=>FILTER_FLAG_ALLOW_THOUSAND,FILTER_FLAG_ALLOW_FRACTION]);
        
        $country=$_POST['country'];

        $status=filter_var($_POST['status'],
            FILTER_SANITIZE_NUMBER_FLOAT,["flags"=>FILTER_FLAG_ALLOW_THOUSAND,FILTER_FLAG_ALLOW_FRACTION]);
        
        $category=filter_var($_POST['category'],
            FILTER_SANITIZE_NUMBER_FLOAT,["flags"=>FILTER_FLAG_ALLOW_THOUSAND,FILTER_FLAG_ALLOW_FRACTION]);

        if(strlen($title)<4){
            $formErrors[]='Item title must be greater than 4 and not empty';
        }
        if(strlen($desc)<10){
            $formErrors[]='Item Description must be greater than 10 and not empty';
        }
        if(strlen($country)<2){
            $formErrors[]='country made must be greater than 2';
        }
        if(empty($price)){
            $formErrors[]='price can\'t be empty';
        }
        if(empty($status)){
            $formErrors[]='status can\'t be empty';
        }
        if(empty($category)){
            $formErrors[]='category can\'t be empty';
        }
        //check if no error
        if (empty($formErrors)) {
            //insert userinfo in database
            $stmt = $con->prepare("INSERT INTO 
                                        items(Name,Description,Price,CountryMade,Status,Date,Cat_ID,Member_ID)
                                        VALUES('$title','$desc','$price','$country','$status',now(),'$category','$member')
                                        ");
            $stmt->execute();

            if($stmt){
                $successMsg='Item Added';
            }

        }
    }
?>
<h1 class="text-center">Create New Ad</h1>

<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create New Ad</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
                            <!-- start Name faild-->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label ">Name</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="text" 
                                    name="name" 
                                    class="form-control live-name" 
                                    required="required"
                                    placeholder="Name of item" />
                                </div>
                            </div>
                            <!-- end Name faild-->
                            <!-- start description faild-->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label ">Description</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="text" 
                                    name="description" 
                                    required="required"
                                    class="form-control live-desc" 
                                    placeholder="description of item" />
                                </div>
                            </div>
                            <!-- end description faild-->
                            <!-- start price faild-->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label ">Price</label>
                                <div class="col-sm-10 col-md-8">
                                    <input type="text" 
                                    name="price" 
                                    required="required"
                                    class="form-control live-price" 
                                    placeholder="price of item" />
                                </div>
                            </div>
                            <!-- end price faild-->
                            <!-- start country made faild-->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label ">Country</label>
                                <div class="col-sm-10 col-md-8">
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
                                <label class="col-sm-3 control-label ">Status</label>
                                <div class="col-sm-10 col-md-8">
                                    <select class="form-control" required name="status" >
                                        <option value="">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Very Old</option>
                                    </select>
                                </div>
                            </div>
                            <!-- end price faild-->
                            <!-- start category faild-->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label ">Category</label>
                                <div class="col-sm-10 col-md-8">
                                    <select class="form-control" required name="category">
                                        <option value="">...</option>
                                        <?php
                                            $cats=getAll('*','categories','CategoryID');
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
                                <div class="col-sm-offset-3 col-sm-10">
                                    <input type="submit" value="Add Ad" class="btn btn-primary btn-lg" />
                                </div>
                            </div>
                            <!-- end btn faild-->
                        </form>
                    </div>

                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">$0</span>
                            <img class="img-responsive" src="img.jpg" alt=""/>
                            <div class="caption">
                                <h3>test</h3>
                                <p>desc..</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start looping throught errors -->
                <?php
                    if(!empty($formErrors)){
                        foreach($formErrors as $error)
                            echo '<div class="alert alert-danger">'.$error.'</div>';
                    }elseif(isset($successMsg)){
                        echo'<div class="alert alert-success">'.$successMsg.'</div>';
                    }
                ?>
                <!-- end looping throught errors -->
            </div>
        </div>
    </div>
</div>

<?php }else{
    header("location: login.php");
}
include_once $tpl . 'footer.php';
ob_end_flush(); ?>