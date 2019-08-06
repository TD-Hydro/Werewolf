<?php
$room = $_GET['room'];
if (isset($_GET['start'])) {
    $handle = new SQLite3("gameplay.db3");
    $result = $handle->exec("UPDATE Running SET start=1 WHERE RmNo=$room");
    if ($result) {
        echo ("success");
    } else {
        echo ("error");
    }
    $handle->close();
} else if (isset($_GET['verify'])) {
    $role = $_GET['role'];
    $handle = new SQLite3("gameplay.db3");
    $result = $handle->query("SELECT \"$role\" FROM Running WHERE RmNo=$room");
    $prepared = 0;
    while ($r = $result->fetchArray()) {
        $prepared = $r[$role];
    }
    echo $prepared;
} else if (isset($_GET['acquire3'])) {
    $item = $_GET['item'];
    $item2 = $_GET['item2'];
    $item3 = $_GET['item3'];
    $handle = new SQLite3("gameplay.db3");
    $result = $handle->query("SELECT \"$item\",\"$item2\",\"$item3\" FROM Running WHERE RmNo=$room");
    $prepared = "";
    while ($r = $result->fetchArray()) {
        $prepared = $r[$item] . " " . $r[$item2]. " " .$r[$item3];
    }
    echo $prepared;
} else {
}

