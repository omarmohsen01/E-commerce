<?php
//
//gettitle function v1.0
//set pagetitle if exist else set defualt
//
function gettitle(){
    global $pagetitle;
    if(isset($pagetitle)){
        echo $pagetitle;
    }
    else{
        echo'defualt';
    }
}
//
//home function redirct v2.0
//[function accept parameter]
//$themsg = echo the error massage
//$seconds  = seconds before redircting
//
function redirctHome($themsg,$url=null,$seconds=3){
    if($url===null){
        $url='index.php';
        $link='HomePage';
    }else{
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){
            $url=$_SERVER['HTTP_REFERER'];
            $link='previous page';
        }else{
            $url='index.php';
            $link='HomePage';
        }
    }

    echo $themsg;
    echo "<div class='alert alert-info'>You Will Redircting To $link After $seconds Second</div>";
    header("refresh:$seconds;url=$url");
    exit();
}

//
//check item function v1.0
//function to check item in database[function accept parameter]
//$select = the item to select[example:user,item,category]
//$from   = the table to select from[example:users,items,categories]
//$value  = the value of select[example:omar,box,elctronics]
//

function checkItem($select,$table,$value){
    global $con;
    $statment=$con->prepare("SELECT $select FROM $table WHERE $select='$value'");
    $statment->execute();
    $count=$statment->rowCount();
    return $count;
}

//
//count number of item function v1.0
//function to count number of item row
//$item  = the item to count
//$table = the table to choose from
//  

function countItem($item,$table){
    global $con;
    $statment=$con->prepare("SELECT COUNT($item) FROM $table");
    $statment->execute();
    return $statment->fetchColumn();
}

//
//Get record func v2.0
//function to get all from database
//
function getAll($field, $tableName, $orderfield, $ordering='DESC',$where = NULL,$and=NULL,$limit=NULL){
    global $con;
    $getall=$con->prepare("SELECT $field FROM $tableName $where $and ORDER BY $orderfield $ordering LIMIT $limit");
    $getall->execute();
    $all=$getall->fetchAll();
    return $all;
}