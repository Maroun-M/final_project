<?php
$conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
session_start();
// Check if the server request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $previousLocation = $_SERVER['HTTP_REFERER'];
    // Get the user's current profile picture from the database
    $userID = $_SESSION['user_id'];
    $query = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($currentProfilePicture);
    $stmt->fetch();
    $stmt->close();
    // Check if the current profile picture path is different from the default profile picture
    $defaultProfilePicture = './default_profile_pic/default_pp.png';
    if ($currentProfilePicture !== $defaultProfilePicture) {
        // Delete the current profile picture file from the server
        $currentProfilePicture = str_replace('/src/data', '', $currentProfilePicture);
        if (file_exists($currentProfilePicture)) {
            unlink($currentProfilePicture);
        }

        // Update the database to set the profile picture path to the default profile picture
        $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $defaultProfilePicture, $userID);
        $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
        header("Location: $previousLocation?delete=successful");
        return true;
    } else {
        header("Location: $previousLocation?delete=failed");

        return false;
    }
}


?>