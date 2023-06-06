<?php
 session_start();
 $pagetitle='Home Page';    
 include"init.php";?>
<div class="container">
    <h1 class="text-center"></h1>
    <?php
        foreach(getAll('*','items','ItemID','','WHERE Approve=1',) as $item){
            echo'<div class="col-sm-6 col-md-3">';
                echo'<div class="thumbnail item-box"><a href="items.php?itemid='.$item['ItemID'].'">';
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
    ?>
</div>

<?php include_once $tpl.'footer.php';?>