<?php 
include_once("./Admin.php");
$admin = new Admin();
session_start();
if(isset($_SESSION['user_id']) && $admin->isAdmin($_SESSION['user_id'])){
    $admin->forceDeleteUser($_POST['id']);
}
?>