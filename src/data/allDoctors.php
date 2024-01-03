<?php
include("../doctor/Doctor.php");
$doctor = new doctor();
session_start();
if (!isset($_SESSION['user_id'])) {
    // Handle the case when the user is not logged in
    echo "User not logged in";
    exit();
}
if(isset($_GET['getTotal']) && ($_GET['getTotal'] === "true")){
    $doctor->getTotalDoctors();
}

if(isset($_GET['page'])){
    $doctor->getDoctors($_GET['page']);
}

// assign doctor 
include("../patient/Patient.php");
$conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
$patient = new patient($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
    $data = json_decode(file_get_contents('php://input'), true);
    $doctorID = $data['doctor_id'];
    $patient->assignDoctorToPatient($_SESSION['user_id'], $doctorID);
}
?>