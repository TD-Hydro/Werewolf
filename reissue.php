<?php
$post = $_GET;

$roomNo = $post["room"];
$playNo = $post['playerNo'];
$newArray = array();

$handle = new SQLite3("gameplay.db3");
$users = $handle->query("SELECT * FROM Player WHERE RmNo=$roomNo");
$i=1;
while ($u = $users->fetchArray()) {
    $newRole = rand(1, $playNo);
    while (in_array($newRole, $newArray)) {
        $newRole = rand(1, $playNo);
    }
    $newArray[] = $newRole;
    $handle->exec("UPDATE Player SET Role=$newRole WHERE RmNo=$roomNo AND No=$i" );
    $i+=1;
}

$runners = $handle->query("SELECT * FROM Running WHERE RmNo=$roomNo");
$cols = $runners->numColumns(); 
while ($r = $runners->fetchArray()) {
    for ($i = 1; $i < $cols; $i++) { 
        if($r[$i] != NULL){
            $columnName = $runners->columnName($i);
            $handle->exec("UPDATE Running SET \"$columnName\"=0 WHERE RmNo=$roomNo" );
        }
        
    }
}

echo ("<script>window.history.back();</script>");