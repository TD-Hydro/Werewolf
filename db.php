<?php
//connection only

function Connection(){
    //$conn = new mysqli("localhost", "admin", "c2h5oh", "werewolf");
    $conn = new mysqli("127.0.0.1:52201", "azure", "6#vWHD_$", "werewolf");
    return $conn;
}