<?php

$room = $_GET['room'];
$user = $_GET['user'];

$a['selfRole'] = -2;
$a['playerNo'] = 8;
$a['roleList'] = array();
$a['No'] = 0;
$a['playerList'] = array();
$a['creator'] = "";

$handle = new SQLite3("gameplay.db3");
$flagHasGame = false;
$gameinfo = $handle->query("SELECT * FROM Game WHERE RmNo=$room");
while ($row = $gameinfo->fetchArray()) {
    $a['playerNo'] = (int)$row["PlayerNo"];
    $setRoles = $row["Role"];
    $werewolves = (int)$row["Werewolf"];  
    $folks = (int)$row["Folk"];
    $a['creator'] = $row["Creator"];
    $flagHasGame = true;
}
if(!$flagHasGame){
    echo json_encode($a);
    exit;
}
$a['roleList'] = explode(";",$setRoles);
for ($i=0; $i < $werewolves; $i++) { 
    $a['roleList'][]="w";
}
for ($i=0; $i < $folks; $i++) { 
    $a['roleList'][]="f";
}

$playerinfo = $handle->query("SELECT Role,No FROM Player WHERE RmNo=$room AND Username=\"$user\"");
while ($p = $playerinfo->fetchArray()) {
    $a['selfRole'] = (int)$p['Role'];
    $a['No'] = (int)$p['No'];
}

$listInfo = $handle->query("SELECT Username,No FROM Player WHERE RmNo=$room");
while ($li = $listInfo->fetchArray()) {
    $a['playerList'][(int)$li["No"]] = $li["Username"];
}

if ($a['selfRole'] == -2) {
    $role = array();
    $maxNo = 0;
    $hasRole = $handle->query("SELECT * FROM Player WHERE RmNo=$room");
    while ($hr = $hasRole->fetchArray()) {
        $role[] = (int)$hr["Role"];
        if ((int)$hr["No"] > $maxNo){
            $maxNo = (int)$hr["No"];
            
        }
    }
    if (count($role) >= $a['playerNo']) {
        $a['selfRole'] = -1;
    } else {
        $newRole = rand(1, $a['playerNo']);
        while (in_array($newRole, $role)) {
            $newRole = rand(1, $a['playerNo']);
        }
        $maxNo += 1;
        $handle->exec("INSERT INTO Player VALUES(\"$user\", \"\", $room, $newRole, $maxNo)");
        $a['selfRole'] = $newRole;
        $a['No'] = $maxNo;
        
    }
}
$handle->close();

echo json_encode($a);