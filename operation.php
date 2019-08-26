<?php
include "db.php";
$post = $_GET;

$role = $post["b-info"];
$self = $post["b-self"];
$postKeys = array_keys($post);
$room = $post["b-room"];
$preRole = $post["b-prev"];

//狼人
if ($role == "w") {
    foreach ($postKeys as $value) {
        if ($value != 'b-info' && $value != 'flip-checkbox' && $value != 'b-room') {
            $id = substr($post[$value], 6);
            $handle = Connection();
            $handle->query("UPDATE Running SET death1=$id,w=$id,`$preRole`=-`$preRole` WHERE RmNo=$room");
            if ($handle->affected_rows > 0) {
                echo ("<script>location.href='room.html?room=$room&user=$self'</script>");
            } else {
                echo ("error");
            }
            break;
        }
    }
}
//女巫
else if ($role == "g-witch") {
    $result1 = 0;
    $result2 = 0;
    $handle = Connection();

    if (isset($post["board"])) {
        $id = substr($post["board"], 6);
        $handle->query("UPDATE Running SET death2=$id, `g-witch`=100 WHERE RmNo=$room");
        $result2 = $handle->affected_rows;
    }
    if (isset($post["flip-checkbox"]) && $post["flip-checkbox"] == "on") {
        $handle->query("UPDATE Running SET `g-witch`=death1, death1=0 WHERE RmNo=$room");
        $result1 = $handle->affected_rows;
    }
    $handle->query("UPDATE Running SET `$preRole`=-`$preRole` WHERE RmNo=$room");
    $result3 = $handle->affected_rows;
    if ($result1 > 0 || $result2 > 0) {
        echo ("<script>location.href='room.html?room=$room&user=$self'</script>");
    } else {
        echo ("error");
    }
}
//预言家
else if ($role == "g-seer") {
    $board = $post["board"];
    $handle = Connection();
    $result = $handle->query("UPDATE Running SET `$preRole`=-`$preRole`, `g-seer`=$board WHERE RmNo=$room");

    if ($result->affected_rows > 0) {
        echo ("success");
    } else {
        echo ("error");
    }
}
//守卫
else if ($role == "g-guard") {
    $handle = Connection();
    $killed = $handle->query("SELECT death1,`g-witch` FROM Running WHERE RmNo=$room");
    //获取女巫信息
    if ($r = $killed->fetch_assoc()) {
        $kill = (int) $r['death1'];
        $save = abs((int) $r['g-witch']);
    }
    if (isset($post["board"])) {
        $id = (int) substr($post["board"], 6);
        if ($kill != 0 && $id == $kill) {
            //成功救
            $result = $handle->query("UPDATE Running SET `g-guard`=$id, `$preRole`=-`$preRole`, death1=0 WHERE RmNo=$room");
        } else if ($kill == 0 && $save == $id) {
            //同守同救
            $result = $handle->query("UPDATE Running SET `g-guard`=$id, `$preRole`=-`$preRole`, death1=$id WHERE RmNo=$room");
        } else {
            //没救到
            $result = $handle->query("UPDATE Running SET `g-guard`=$id, `$preRole`=-`$preRole` WHERE RmNo=$room");
        }
    } else {
        //没救
        $result = $handle->query("UPDATE Running SET `g-guard`=100, `$preRole`=-`$preRole` WHERE RmNo=$room");
    }
    //
    if ($handle->affected_rows > 0) {
        echo ("<script>location.href='room.html?room=$room&user=$self'</script>");
    } else {
        echo ("error");
    }
}
