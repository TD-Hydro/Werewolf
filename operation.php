<?php

$post = $_GET;

$role = $post["b-info"];
$postKeys = array_keys($post);
$room = $post["b-room"];

if ($role == "w") {
    foreach ($postKeys as $value) {
        if ($value != 'b-info' && $value != 'flip-checkbox' && $value != 'b-room') {
            $id = substr($post[$value], 6);
            $handle = new SQLite3("gameplay.db3");
            $result = $handle->exec("UPDATE Running SET death1=$id,w=$id,start=0 WHERE RmNo=$room");
            if ($result) {
                echo ("<script>window.history.back();</script>");
            } else {
                echo ("error");
            }
            break;
        }
    }
} else if ($role == "g-witch") {
    $result1 = true;
    $result2 = true;
    $handle = new SQLite3("gameplay.db3");
    if (isset($post["flip-checkbox"]) &&  $post["flip-checkbox"] == "on") {
        $result1 = $handle->exec("UPDATE Running SET death1=0, \"g-witch\"=w WHERE RmNo=$room");
    }

    if (isset($post["board"])) {
        $id = substr($post["board"], 6);
        $result2 = $handle->exec("UPDATE Running SET death2=$id, \"g-witch\"=w WHERE RmNo=$room");
    }
    $result3 = $handle->exec("UPDATE Running SET w=-w WHERE RmNo=$room");
    if ($result1 && $result2) {
        echo ("<script>window.history.back();</script>");
    } else {
        echo ("error");
    }
} else if ($role == "g-seer") {
    $board = $post["board"];
    $handle = new SQLite3("gameplay.db3");
    $result = $handle->exec("UPDATE Running SET \"g-witch\"=-\"g-witch\", \"g-seer\"=$board WHERE RmNo=$room");

    if ($result) {
        echo ("success");
    } else {
        echo ("error");
    }
}
