<?php

$room = (int)$_GET['room'];
$user = $_GET['user'];

$handle = new SQLite3("gameplay.db3");
$result = $handle->exec("DELETE FROM Player WHERE RmNo=$room AND Username=\"$user\"");

$handle->close();

if ($result >= 1) {
    echo ("<script>alert('您已退出房间');location.href='/'</script>");
} else echo "error";