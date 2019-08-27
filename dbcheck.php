<?php 
    //below  will give the whole connectionstring in a single string
    $conn = getenv("MYSQLCONNSTR_localdb"); 

    //Let's split it and decorate it in an array
    $conarr2 = explode(";",$conn); 
    $conarr = array();
    foreach($conarr2 as $key=>$value){
        $k = substr($value,0,strpos($value,'='));
        $conarr[$k] = substr($value,strpos($value,'=')+1);
    }
    // $conarr is an array of values of connection string
    print_r($conarr); 
?>