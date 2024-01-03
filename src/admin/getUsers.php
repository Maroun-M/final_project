<?php 
include_once("./Admin.php");
$admin = new Admin();
session_start();
if(isset($_SESSION['user_id']) && $admin->isAdmin($_SESSION['user_id'] )){
    if($_GET['type'] == "1"){
        $admin->getUsersData($_GET['name'], (int) $_GET['page'],(int) $_GET['perPage']);
    } else if($_GET['type'] == "2"){
        $admin->getDoctors($_GET['name'], (int) $_GET['page'],(int) $_GET['perPage']);
    }
}

?>