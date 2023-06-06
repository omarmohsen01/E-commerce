<?php
session_start();
include"init.php";?>

<div class="container">
    <h1 class="text-center">show category</h1>
    <?php
        $items=getAll("*","items","ItemID","","WHERE Cat_ID={$_GET['pageid']}","AND Approve=1");
        foreach($items as $item){
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

<?php

include_once $tpl.'footer.php';?>