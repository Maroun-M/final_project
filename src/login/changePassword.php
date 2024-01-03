<?php
// Include the Login class file
require_once('./login.php');
// Create a new Login object
session_start();
$login = new Login();

if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    $jsonData = file_get_contents('php://input');
    $postedData = json_decode($jsonData, true);
    // Send the JSON response
    $oldPassword = $postedData["oldPassword"];
    $newPassword = $postedData["newPassword"];
    $confirmNewPassword = $postedData["confirmNewPassword"];
    $login->changePassword($_SESSION['user_id'], $oldPassword, $newPassword, $confirmNewPassword);
}
?>