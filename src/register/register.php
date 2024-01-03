<?php
include_once("./Registration.php");
include_once("../login/login.php");

if (isset($_POST['firstName'], $_POST['lastName'], $_POST['phoneNumber'], $_POST['emailAddress'], $_POST['password'], $_POST['confirm-password'], $_POST['registration-type'])) {
    
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['emailAddress'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $type = $_POST['registration-type'];

    $requiredParams = array($firstName, $lastName, $phoneNumber, $email, $password, $confirmPassword, $type);

    // Check if any required parameter is empty
    if (in_array('', $requiredParams)) {
        header('Location: ../../index.php?registration=failed&error=missingFields');
        exit; // Stop further execution
    }

    $account = new Registration($firstName, $lastName, $phoneNumber, $email, $password, $confirmPassword, $type);
    $account->register();
    
    $login = new Login();
    $login->__construct();  
    session_start();
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $login->login($email, $password, $_SESSION['token']);
    header("LOCATION: ../../confirm.php");

} 

if(isset($_GET['resend'])){
    session_start();
    $confirm = Registration::createWithoutParams();
    $confirm->resendActivationEmail($_SESSION['user_email']);
}


?>