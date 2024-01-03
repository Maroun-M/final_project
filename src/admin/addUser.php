<?php 
include_once("./Admin.php");
$admin = new Admin();
session_start();
if(isset($_SESSION['user_id']) && $admin->isAdmin($_SESSION['user_id'])){


    if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['mobile']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['type'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $type = $_POST['type'];
        if($type === "Patient"){
            $type = 1;
        } else {
            $type = 2;
        }

        $admin->forceRegisterUser($fname, $lname, $mobile, $email, $password, $type);
        header("Location: ../../manageUsers.php?user=added");
      }
}
?>