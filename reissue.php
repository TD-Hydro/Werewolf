<?php
include "db.php";
$post = $_GET;

$roomNo = $post["room"];
$playNo = $post['playerNo'];
$newArray = array();

$handle = $handle = Connection();
$users = $handle->query("SELECT * FROM Player WHERE RmNo=$roomNo");
$i=1;
while ($u = $users->fetch_assoc()) {
    $newRole = rand(1, $playNo);
    while (in_array($newRole, $newArray)) {
        $newRole = rand(1, $playNo);
    }
    $newArray[] = $newRole;
    $handle->query("UPDATE Player SET Role=$newRole WHERE RmNo=$roomNo AND No=$i" );
    $i+=1;
}

$runners = $handle->query("SELECT * FROM Running WHERE RmNo=$roomNo");
#$cols = $runners->field_count; 
if ($r = $runners->fetch_assoc()) {
    foreach($r as $key=>$value){
        if($value != NULL and $key != "RmNo")
        $handle->query("UPDATE Running SET `$key`=0 WHERE RmNo=$roomNo" );
    }
}

echo ("<script>window.history.back();</script>");