<?php
class Login
{
    private $conn; // holds database connection object

    // constructor takes a database connection object as parameter
    public function __construct()
    {
        $this->conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
        ;

    }


    public function login($email, $password, $token)
    {
        if (empty($email) || empty($password) || empty($token) || !hash_equals($_SESSION['token'], $token)) {
            return false;
        }

        // sanitize input to prevent SQL injection attacks
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = mysqli_real_escape_string($this->conn, $password);

        // Prepare query to select user by email
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? OR phone_number = CONCAT('+961 ', ?)");
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();

        // Check if user exists
        $row = $result->fetch_assoc();

        if ($result->num_rows == 1 && password_verify($password, $row['account_password'])) {

            //  Set user information
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            return true;

        } else {
            // Invalid email or password
            echo 'Invalid email or password';
            return false;
        }

    }

    public function confirmUser($email, $confirmationCode)
    {
        $email = $this->conn->real_escape_string($email);
        $confirmationCode = $this->conn->real_escape_string($confirmationCode);

        // Check if the confirmation code belongs to the email
        $query = "SELECT * FROM users WHERE email = ? AND confirmation_code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $email, $confirmationCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result || $result->num_rows == 0) {
            return false;
        }

        // Update the confirmation status
        $query = "UPDATE users SET confirmed = 1 WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        header("location: ../../index.php?account=confirmed");
        return true;
    }
    private $accessLevel;

    public function getUserAccessLevel($userId)
    {



        // Prepare and execute the SQL query
        $stmt = $this->conn->prepare("SELECT access_level FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($this->accessLevel);
        $stmt->fetch();


        // Return the access level as an integer
        return (int) $this->accessLevel;
    }



    public function changePassword($userId, $currentPassword, $newPassword, $confirmPassword)
    {

        if (empty($newPassword)) {
            $response = array(
                'success' => false,
                'message' => 'New password is required.'
            );
            echo json_encode($response);
            return false; // Passwords don't match
        }
        if (strlen($newPassword) < 8 || !preg_match('/[A-Z]/', $newPassword)) {
            $response = array(
                'success' => false,
                'message' => 'New password must be at least 8 characters long and contain at least one capital letter.'
            );
            echo json_encode($response);
            return false; // Passwords don't match
        }
        // Check if the new password and confirm password match
        if ($newPassword !== $confirmPassword) {
            $response = array(
                'success' => false,
                'message' => 'New password and confirm password do not match.'
            );
            echo json_encode($response);
            return false; // Passwords don't match
        }

        

        // Check if the current password matches the stored password for the user
        $query = "SELECT account_password FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['account_password'];

            // Verify the current password
            if (password_verify($currentPassword, $storedPassword)) {

                if ($newPassword === $currentPassword) {
                    $response = array(
                        'success' => false,
                        'message' => 'New password should be different from the old password.'
                    );
                    echo json_encode($response);
                    return false; 
                }
                // Generate a new password hash for the new password
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the user's password in the database
                $updateQuery = "UPDATE users SET account_password = ? WHERE id = ?";
                $stmt = $this->conn->prepare($updateQuery);
                $stmt->bind_param("si", $newPasswordHash, $userId);
                $stmt->execute();

                if ($stmt->affected_rows === 1) {
                    $response = array(
                        'success' => true,
                        'message' => 'Password changed successfully.'
                    );
                    echo json_encode($response);
                    return true; // Password change successful
                }
            }
        }

        $response = array(
            'success' => false,
            'message' => 'Failed to change password. Please check your current password.'
        );
        echo json_encode($response);
        return false; // Password change failed
    }





}
?>