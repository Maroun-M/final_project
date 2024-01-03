<?php
include_once("./Doctor.php");
session_start();
$doctor = new doctor();
if (!isset($_SESSION['user_id'])) {
  // Handle the case when the user is not logged in
  echo "User not logged in";
  exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input fields from the form
    $user_id = $_SESSION["user_id"];
    $location = $_POST["location"];
    $biography = $_POST["biography"];
    $education = $_POST["education"];
    $date_of_birth = $_POST["dob"];
    $clinic_name = $_POST["clinic_name"];
    $clinic_number = $_POST["clinic_number"];
    $clinic_location = $_POST["clinic_location"];
    // Call the insertOrUpdateData method to insert or update the data in the database
    if ($doctor->insertDoctorData($user_id, $location, $education, $biography, $date_of_birth, $clinic_name, $clinic_number, $clinic_location )) {
      header("location: ../../doctorInfo.php?update=success");
    } else {
      // Insert or update failed
      header("location: ../../doctorInfo.php?update=failed");

    }
  }
?>