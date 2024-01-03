<?php
$previousLocation = $_SERVER['HTTP_REFERER'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if a file was uploaded
    if (isset($_FILES["profile_picture"])) {
        $file = $_FILES["profile_picture"];

        // Check for errors during the upload
        if ($file["error"] === UPLOAD_ERR_OK) {
            // Check if the uploaded file is an actual image
            $mime = getimagesize($file["tmp_name"])["mime"];
            $allowedMimes = ["image/jpeg", "image/png"];
            if (in_array($mime, $allowedMimes)) {
                // Define the maximum allowed file size (in bytes)
                $maxFileSize = 2 * 1024 * 1024; // 2MB

                // Check if the file size is within the allowed limit
                if ($file["size"] <= $maxFileSize) {
                    // Generate a unique filename for the image
                    $filename = uniqid() . "_" . $file["name"];

                    // Specify the destination directory to save the image
                    $uploadDir = "./user_profile_pic/";
                    $uploadDestination = "./user_profile_pic/" . $filename;

                    $destination = "./src/data/user_profile_pic/" . $filename;

                    // Move the uploaded file to the destination
                    if (move_uploaded_file($file["tmp_name"], $uploadDestination)) {
                        $conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
                        session_start();

                        // Get the previous image path from the database
                        $previousImagePath = '';
                        $stmt = mysqli_prepare($conn, 'SELECT profile_picture FROM users WHERE id = ?');
                        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $previousImagePath);
                        mysqli_stmt_fetch($stmt);
                        mysqli_stmt_close($stmt);

                        // Delete the old image if it's different from the default profile picture
                        if ($previousImagePath != './default_profile_pic/default_pp.png') {
                            $previousImagePath = str_replace('/src/data', '', $previousImagePath);
                                if (file_exists($previousImagePath)) {
                                    unlink($previousImagePath);
                                }
                            
                        }
                        // Prepare and bind the statement
                        $stmt = mysqli_prepare($conn, 'UPDATE users SET profile_picture = ? WHERE id = ?');
                        mysqli_stmt_bind_param($stmt, "si", $destination, $_SESSION['user_id']);

                        // Execute the statement
                        if (mysqli_stmt_execute($stmt)) {
                            mysqli_stmt_close($stmt);
                            mysqli_close($conn);
                            header("Location: $previousLocation?upload=successful");
                            return true;
                        } else {
                            mysqli_stmt_close($stmt);
                            mysqli_close($conn);
                            return false;
                        }
                    } else {
                        header("Location: $previousLocation?upload=failed");
                    }
                } else {
                    header("Location: $previousLocation?upload=failed&error=size");
                }
            } else {
                header("Location: $previousLocation?upload=failed&error=imgType");
            }
        } else {
            header("Location: $previousLocation?upload=failed&error=unkown");
        }
    } else {
        header("Location: $previousLocation?upload=failed&error=emptyFile");

    }
}


?>