<?php
include("./PHPMailer-master/src/PHPMailer.php");
include("./PHPMailer-master/src/SMTP.php");
include("./PHPMailer-master/src/Exception.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Registration
{

    private $firstName;
    private $lastName;
    private $phoneNumber;
    private $email;
    private $password;
    private $confirmPassword;
    private $conn;
    private $type;
    private $accessLevel;

    public function __construct($firstName = null, $lastName = null, $phoneNumber = null, $email = null, $password = null, $confirmPassword = null, $type = null)
    {
        $this->firstName = trim($firstName);
        $this->lastName = trim($lastName);
        $this->phoneNumber = trim($phoneNumber);
        $this->email = trim($email);
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
        $this->type = $type;


        $this->conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
    }
    private function initializeConnection()
    {
        $this->conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
    }

    // New constructor with no parameters
    public static function createWithoutParams()
    {
        $instance = new self();
        $instance->initializeConnection();
        return $instance;
    }
    public function __constructWithoutParams()
    {
        $this->conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
    }

    public function validate()
    {
        $errors = array();

        // Validate first name
        if (empty($this->firstName)) {
            $errors['firstName'] = "First name is required.";
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $this->firstName)) {
            $errors['firstName'] = "Only letters allowed.";
        }

        // Validate last name
        if (empty($this->lastName)) {
            $errors['lastName'] = "Last name is required.";
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $this->lastName)) {
            $errors['lastName'] = "Only letters allowed.";
        }

        // Validate phone number
        if (empty($this->phoneNumber)) {
            $errors['phoneNumber'] = "Phone number is required.";
        } elseif (!preg_match("/^\+(?:[0-9] ?){6,14}[0-9]$/", $this->phoneNumber)) {
            $errors['phoneNumber'] = "Invalid phone number.";
        }

        // Validate email
        if (empty($this->email)) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }

        // Validate password
        if (empty($this->password)) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($this->password) < 8 || !preg_match('/[A-Z]/', $this->password)) {
            $errors['password'] = "Password must be at least 8 characters long and contain at least one capital letter.";
        }

        // Validate confirm password
        if (empty($this->confirmPassword)) {
            $errors['confirmPassword'] = "Please confirm your password.";
        } elseif ($this->confirmPassword !== $this->password) {
            $errors['confirmPassword'] = "Passwords do not match.";
        }
        var_dump($errors);
        return $errors;
    }

    private function hashPassword($password)
    {
        $options = [
            'cost' => 12,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    private function generateConfirmationCode()
    {
        $length = 6;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    public function register()
    {
        // Validate form data
        $errors = $this->validate();
        if (!empty($errors)) {
            header('Location: ../../index.php?registration=failed&error=invalidInput');

            return false;
        }

        // Check for duplicate email
        $email = $this->email;
        $phoneNumber = $this->phoneNumber;
        $query = 'SELECT id FROM users WHERE email=? OR phone_number=?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $email, $phoneNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            header('Location: ../../index.php?registration=failed&error=duplicateEmailOrPass');

            return false;
        }

        // Hash password
        $hashedPassword = $this->hashPassword($this->password);

        // Generate confirmation code
        $confirmationCode = $this->generateConfirmationCode();

        // Insert user data into database
        $firstName = $this->conn->real_escape_string($this->firstName);
        $lastName = $this->conn->real_escape_string($this->lastName);
        $phoneNumber = $this->conn->real_escape_string($this->phoneNumber);
        $this->type = $this->conn->real_escape_string($this->type);
        // Determine access level based on registration type
        if ($this->type === 'doctor') {
            $accessLevel = 2;
        } else {
            $accessLevel = 1;
        }
        $email = $this->conn->real_escape_string($this->email);
        $query = "INSERT INTO users (first_name, last_name, phone_number, email, account_password, confirmation_code, access_level) 
              VALUES ('$firstName', '$lastName', '$phoneNumber', '$email', '$hashedPassword', '$confirmationCode', '$accessLevel')";
        $result = $this->conn->query($query);
        // $this->conn->close();
        // Send confirmation email  

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'maroun233245@gmail.com';
        $mail->Password = 'kheqpudxbrnxadlc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $to = $this->email;
        $mail->setFrom('maroun233245@gmail.com', 'Ouvatech');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = "Confirm your registration";
        $mail->Body = "Thank you for registering! Your confirmation code is: <p color='blue'><bold>$confirmationCode</bold></p> Or confirm by clicking on the link below:";
        $mail->Body .= "http://localhost/ouvatech/src/login/userLogin.php?email=$email&confirmationCode=$confirmationCode";
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;

        } else {
            echo 'Message sent!';
            return true;
        }
    }

    public function resendActivationEmail($email)
    {


        $query = "SELECT u.id, u.confirmation_code, rac.resend_count, rac.last_resend_timestamp
              FROM users AS u
              LEFT JOIN resend_activation_counts AS rac ON u.id = rac.user_id
              WHERE u.email = ?";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email); // Bind the email parameter

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['id'];
            $resendCount = $row['resend_count'];
            $lastResendTimestamp = $row['last_resend_timestamp'];

            // Check if the resend limit has been reached
            $maxResendLimit = 3; // Define your desired maximum resend limit
            $resendCooldownSeconds = 3600; // Define the cooldown period in seconds (1 hour in this example)

            if ($resendCount >= $maxResendLimit && time() - strtotime($lastResendTimestamp) < $resendCooldownSeconds) {
                // Resend limit reached and cooldown period is still active
                $response = array(
                    'message' => 'You have reached the maximum resend limit. Please try again later.'
                );
                header('Content-Type: application/json');

                echo json_encode($response);
                exit; // Stop further execution
            }

            // Increment the resend_count and update the last_resend_timestamp in the resend_activation_counts table
            $resendCount++;
            $currentTimestamp = date('Y-m-d H:i:s');
            $updateQuery = "INSERT INTO resend_activation_counts (user_id, resend_count, last_resend_timestamp)
                        VALUES ($user_id, $resendCount, '$currentTimestamp')
                        ON DUPLICATE KEY UPDATE resend_count = VALUES(resend_count), last_resend_timestamp = VALUES(last_resend_timestamp)";
            $this->conn->query($updateQuery);

            // Send the activation email
            $mail = new PHPMailer(true);
            // Configure your PHPMailer settings here
            // ...

            // Generate a new confirmation code
            $newConfirmationCode = $this->generateConfirmationCode();

            // Update the new confirmation code in the users table
            $updateCodeQuery = "UPDATE users SET confirmation_code = '$newConfirmationCode' WHERE id = $user_id";
            $this->conn->query($updateCodeQuery);

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'maroun233245@gmail.com';
            $mail->Password = 'kheqpudxbrnxadlc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $to = $email;
            $mail->setFrom('maroun233245@gmail.com', 'Ouvatech');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = "Confirm your registration";
            $mail->Body = "Thank you for registering! Your confirmation code is: <p color='blue'><bold>$newConfirmationCode</bold></p> Or confirm by clicking on the link below:";
            $mail->Body .= "http://localhost/ouvatech/src/login/confirm.php?email=$email&confirmationCode=$newConfirmationCode";
            if (!$mail->send()) {
                $response = array(
                    'message' => 'Failed to send the activation email.'
                );
                header('Content-Type: application/json');

                echo json_encode($response);
            } else {
                $response = array(
                    'message' => 'Activation email sent successfully!'
                );
                header('Content-Type: application/json');

                echo json_encode($response);
            }
        } else {
            $response = array(
                'message' => 'Email is not registered.'
            );
            header('Content-Type: application/json');

            echo json_encode($response);
        }
    }




}
?>