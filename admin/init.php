<?php
    include_once'connect.php';

    //routes
    $tpl='includes/templates/'; //templates direction
    $css='layout/css/';         //css direction
    $js='layout/js/';           //js direction
    $func='includes/functions/'; //functions direction

    // included important files
    include_once $func.'func.php';
    include_once $tpl.'header.php';
    
    //includes navbar in all page expects page have nonavbar variable
    if(!isset($nonavbar)){include_once $tpl.'navbar.php';}
    
?>