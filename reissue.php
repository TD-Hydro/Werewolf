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
$cols = $runners->numColumns(); 
while ($r = $runners->fetch_assoc()) {
    for ($i = 1; $i < $cols; $i++) { 
        if($r[$i] != NULL){
            $columnName = $runners->columnName($i);
            $handle->query("UPDATE Running SET '$columnName'=0 WHERE RmNo=$roomNo" );
        }
        
    }
}

echo ("<script>window.history.back();</script>");