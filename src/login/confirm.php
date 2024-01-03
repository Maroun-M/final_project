<?php
// Include the Login class file
require_once('./login.php');
// Create a new Login object
session_start();
$login = new Login();


if (isset($_POST['confirmation-email']) && isset($_POST['confirmation-code'])) {
    $login->confirmUser($_POST['confirmation-email'], $_POST['confirmation-code']);

}

if (isset($_GET['email']) && isset($_GET['confirmationCode'])) {
    $login->confirmUser($_GET['email'], $_GET['confirmationCode']);
}

?>