<?php
include_once("./Doctor.php");
session_start();
$doctor = new doctor();
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $doctor->updateClinicRecord($_SESSION['user_id'], $_POST["clinic_id"], $_POST["clinic_name"], $_POST["clinic_number"], $_POST["clinic_location"]);
}
?>