<?php
$post = $_GET;

$postKeys = array_keys($post);
$room = $post["room"];
$role = $post["role"];

if ($role == "g-seer") {
    $handle = new SQLite3("gameplay.db3");
    $id = $post["board"];
    $check = $handle->query("SELECT Role FROM Player WHERE RmNo=$room AND No=$id");
    if ($c = $check->fetchArray()) {
        $ms = $c["Role"];
        $handle->exec("SELECT Role FROM Player WHERE RmNo=$room AND No=$id");
        echo $ms;
    }
}