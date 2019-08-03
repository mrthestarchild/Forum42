<?php

// this function clears all session data for the user effectivly logging them out.

session_start();
header('Content-type: application/json; charset=utf-8');
if(isset($_POST['logout'])){
    if($_POST['logout'] == true){
        session_unset();
        session_destroy();
    }
}
?>