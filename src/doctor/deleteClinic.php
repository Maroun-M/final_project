<?php
include_once("./Doctor.php");
session_start();
$doctor = new Doctor();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $doctor->deleteClinicRecord($_POST['clinic_id'], $_SESSION['user_id']);
}

?>