<?php
session_start();
    if(!isset($_SESSION['login']) && $_SESSION['login'] != true){
        header("Location: upload-notice.php");
    }
    require_once "db.php";
    
    if(isset($_GET['nid']) && $_SESSION['login'] === true){
        $change_status = $db->change_status($_GET['nid']);
    }else{
        header("Location: upload-notice.php");
    }
?>