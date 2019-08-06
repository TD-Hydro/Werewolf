<?php
//connection only

function Connection(){
    $conn = new mysqli("localhost", "admin", "c2h5oh", "werewolf");
    return $conn;
}