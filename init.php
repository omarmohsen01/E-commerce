<?php
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    include_once'admin/connect.php';

    $sessionUser='';
    if(isset($_SESSION['name'])){
        $sessionUser=$_SESSION['name'];
    }
    //routes
    $tpl='includes/templates/'; //templates direction
    $css='layout/css/';         //css direction
    $js='layout/js/';           //js direction
    $func='includes/functions/'; //functions direction

    // included important files
    include_once $func.'func.php';
    include_once $tpl.'header.php';
