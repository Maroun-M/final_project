<?php
// Start the session (if not already started)
session_start();
include("./PHPMailer-master/src/PHPMailer.php");
include("./PHPMailer-master/src/SMTP.php");
include("./PHPMailer-master/src/Exception.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to check email or phone number, store in session, generate confirmation code, and send email
function initiateForgetPassword($emailOrPhone) {
    $emailExists = checkEmailExistsInDatabase($emailOrPhone);
    $phoneExists = checkPhoneExistsInDatabase($emailOrPhone);

    if ($emailExists || $phoneExists) {
        $confirmationCode = generateConfirmationCode();
        $_SESSION['forget_password_email'] = ($emailExists) ? $emailOrPhone : '';
        $_SESSION['forget_password_phone'] = ($phoneExists) ? $emailOrPhone : '';
        $_SESSION['forget_password_code'] = $confirmationCode;
        $emailSent = sendVerificationCodeEmail($emailOrPhone, $confirmationCode);
        $_SESSION['email_forget'] = getEmailByPhoneOrEmail($emailOrPhone);
        if ($emailSent) {
            header("LOCATION: ../../codeVerification.php");
            return true;
        } else {
            header("LOCATION: ../../forgotPassword.php?error=unkown");

            return false;
        }
    } else {
        header("LOCATION: ../../forgotPassword.php?input=invalid");
        
    }
}
// Function to retrieve the email address of a user based on phone number or email
function getEmailByPhoneOrEmail($phoneOrEmail) {
    $conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');

    $query = "SELECT email FROM users WHERE phone_number = CONCAT('+961 ', ?) OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $phoneOrEmail, $phoneOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['email'];
    } else {
        return null; // User not found
    }
}

// Function to check if email exists in the database
function checkEmailExistsInDatabase($email) {
    $conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');

    $query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Function to check if phone number exists in the database
function checkPhoneExistsInDatabase($phone) {
    $conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
    $phone = trim($phone);
    $phone = "+961 " . $phone;
    $query = "SELECT COUNT(*) as count FROM users WHERE phone_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Function to generate a random confirmation code
function generateConfirmationCode() {
    // Example code: generate a 6-digit random number as the confirmation code
    return rand(100000, 999999);
}

// Function to send an email with the verification code
function sendVerificationCodeEmail($recipient, $confirmationCode) {
    $mail = new PHPMailer(true);

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'maroun233245@gmail.com';
        $mail->Password = 'kheqpudxbrnxadlc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $to = getEmailByPhoneOrEmail($recipient);
        echo getEmailByPhoneOrEmail($recipient);
        $mail->setFrom('maroun233245@gmail.com', 'Ouvatech');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(false);
        $mail->Subject = 'Reset Code';
        $mail->Body = 'Your reset code is: ' . $confirmationCode;

        // Send the email
        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Failed to send email
    }
}
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['emailOrPhone'])){
    initiateForgetPassword($_POST["emailOrPhone"]);
}
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['codeVerification']) && isset($_POST['password']) && isset($_POST['confirmPassword'])){
    $pass = $_POST['password'];
    $confirmPass = $_POST['confirmPassword'];
    if($_SESSION['forget_password_code'] == $_POST['codeVerification']){

        if (empty($pass) || empty($confirmPass)) {
            header("LOCATION:../../codeVerification.php?password=invalid");
            return false;
        }
        if (strlen($pass) < 8 || !preg_match('/[A-Z]/', $pass)) {
            header("LOCATION:../../codeVerification.php?password=invalid");
            return false; // Passwords don't match
        }
        // Check if the new password and confirm password match
        if ($pass !== $confirmPass) {
            header("LOCATION:../../codeVerification.php?confirmPassword=invalid");
            return false; // Passwords don't match
        }

                $newPasswordHash = password_hash($pass, PASSWORD_DEFAULT);
                $conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');

                // Update the user's password in the database
                $updateQuery = "UPDATE users SET account_password = ? WHERE email = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ss", $newPasswordHash, $_SESSION['email_forget']);
                $stmt->execute();
                header("LOCATION:../../login.php?reset=successful");
                return true;

    } else {
        header("LOCATION: ../../codeVerification.php?code=incorrect");
        exit();
    }
}


?>