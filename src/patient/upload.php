<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Handle the case when the user is not logged in
    echo "User not logged in";
    exit();
}
if (isset($_FILES["lab-tests"])) {

    $files = $_FILES['lab-tests'];
    $fileName = $_FILES['lab-tests']['name'];
    $fileTmpName = $_FILES['lab-tests']['tmp_name'];
    $fileSize = $_FILES['lab-tests']['size'];
    $fileError = $_FILES['lab-tests']['error'];
    $fileType = $_FILES['lab-tests']['type'];

    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowedExt = array('jpg', 'png', 'jpeg', 'pdf');
    // Check if file extension is allowed
    if (in_array($fileExt, $allowedExt)) {
        // Check for upload errors
        if ($fileError === 0) {
            // Check file size
            if ($fileSize < 10000000) {
                // Generate unique filename and save file to destination
                $fileNameNew = $_SESSION['user_id'] . '_' . uniqid('IMG-', true) . '.' . $fileExt;
                $fileDestination = './tests/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);                
                $conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
                // Prepare and bind the statement
                $stmt = mysqli_prepare($conn, "INSERT INTO user_files (user_id, file_path) VALUES (?, ?)");
                mysqli_stmt_bind_param($stmt, "is", $_SESSION['user_id'], $fileDestination);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    header("Location: ../../labTests.php?upload=successful");
                    return true;
                } else {
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    return false;
                }

            } else {
                header("Location: ../../labTests.php?error=file_size");
            }
        } else {
            header("Location: ../../labTests.php?error=upload_error");
        }
    } else {
        header("Location: ../../labTests.php?error=invalid_extension");
    }

}
?>