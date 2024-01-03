<?php
include_once("./Doctor.php");
session_start();
$doctor = new doctor();
if (!isset($_SESSION['user_id'])) {
  // Handle the case when the user is not logged in
  echo "User not logged in";
  exit();
}

    if ($doctor->isDoctor($_SESSION['user_id'])){
      $doctor->fetchDoctorsDataAsJson($_SESSION['user_id']);
    } else {
      $doctor->fetchDoctorsDataAsJson($_GET['dr_user_id']);
    }


?>