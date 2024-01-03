<?php

include_once("./updateUserInfo.php");
if (!isset($_SESSION['user_id'])) {
    // Handle the case when the user is not logged in
    echo "User not logged in";
    exit();
}
if($patient->checkID($_SESSION['user_id'])){
    $json_data = json_encode($patient->getAllInfo());
    header('Content-Type: application/json');
    echo $json_data;

}
?>