<?php
include "db.php";
$room = $_GET['room'];
if (isset($_GET['start'])) {
    $handle = $handle = Connection();
    $result = $handle->query("UPDATE Running SET start=1 WHERE RmNo=$room");
    if ($handle->affected_rows > 0) {
        echo ("success");
    } else {
        echo ("error");
    }
    $handle->close();
} else if (isset($_GET['verify'])) {
    $role = $_GET['role'];
    $handle = $handle = Connection();
    $result = $handle->query("SELECT `$role` FROM Running WHERE RmNo=$room");
    $prepared = 0;
    while ($r = $result->fetch_assoc()) {
        $prepared = $r[$role];
    }
    echo $prepared;
} else if (isset($_GET['acquire3'])) {
    $item = $_GET['item'];
    $item2 = $_GET['item2'];
    $item3 = $_GET['item3'];
    $handle = $handle = Connection();
    $result = $handle->query("SELECT `$item`, `$item2`, `$item3` FROM Running WHERE RmNo=$room");
    $prepared = "";
    while ($r = $result->fetch_assoc()) {
        $prepared = $r[$item] . " " . $r[$item2] . " " . $r[$item3];
    }
    echo $prepared;
} else if (isset($_GET['ongoing'])) {
    $handle = $handle = Connection();
    $result = $handle->query("SELECT * FROM Running WHERE RmNo=$room");
    while ($r = $result->fetch_assoc()) {
        $prepared = $r;
    }
    echo json_encode($prepared);
} else {

}
