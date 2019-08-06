<?php
include "db.php";
$post = $_POST;
$postKeys = array_keys($post);

$addRole = "";
$roleNo = 0;

foreach ($postKeys as $value) {
    if ($value != 'n-werewolf' && $value != 'n-folk' && $value != 'username') {
        $addRole .= $value . ";";
        $roleNo += 1;
    }
}
$addRole = substr($addRole, 0, -1);

$werewolves = (int)$post['n-werewolf'];
$folks = (int)$post['n-folk'];
$roleNo += $werewolves;
$roleNo += $folks;
$roomNo = rand(0, 99);
$creator = $post['username'];

$result = 0;

$handle = Connection();
if ($handle->connect_error) {
    echo("Connection failed: " . $handle->connect_error);
}
$rm = $handle->query("SELECT RmNo FROM Game WHERE RmNo=$roomNo");
if ($rm){
    $handle->query("DELETE FROM Game WHERE RmNo=$roomNo");
    $handle->query("DELETE FROM Running WHERE RmNo=$roomNo");
    $handle->query("DELETE FROM Player WHERE RmNo=$roomNo");
    $result = 1;
}
else{
    $res = $handle->query("INSERT INTO Game VALUES($roomNo, \"$addRole\", $werewolves, $folks, $roleNo, \"$creator\", \"0\", 0,\"$creator\")") or die($handle->error);
    $handle->query("INSERT INTO Running (RmNo,death1,death2,death3,start) VALUES($roomNo,0,0,0,0)");
    while ($row = $res->fetch_assoc()) {
        echo($row);
    }
    foreach ($postKeys as $value) {
        if ($value != 'n-werewolf' && $value != 'n-folk' && $value != 'username') {
            $handle->query("UPDATE Running SET \"$value\"=0 WHERE RmNo=$roomNo");
        }
    }
}

$handle->close();


if ($result >= 1) {
    //echo ("<script>location.href='room.html?room=$roomNo&user=$creator'</script>");
} else echo "error";
