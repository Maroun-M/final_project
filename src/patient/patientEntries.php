<?php include_once("./Patient.php");
session_start();
$conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
$patient = new Patient($conn);

if (!isset($_SESSION['user_id'])) {
    // Handle the case when the user is not logged in
    echo "User not logged in";
    exit();
}
if(isset($_POST["heart-rate"])){
    $patient->insert_hr($_POST["heart-rate"], $_SESSION["user_id"]);
}
if(isset($_POST["systolic"]) && isset($_POST["diastolic"])){
    $patient->insert_bp($_POST["systolic"], $_POST["diastolic"], $_SESSION["user_id"]);
}
if(isset($_POST["temperature"])){
    $patient->addTemperature($_POST["temperature"], $_SESSION["user_id"]);
}

if(isset($_POST["oxygen"])){
    $patient->insert_blood_oxygen($_POST["oxygen"], $_SESSION["user_id"]);
}

if(isset($_POST["glucose"])){
    $patient->insertBloodGlucose($_POST["glucose"], $_SESSION["user_id"]);
}

if( isset($_POST["fetal-weight"]) && isset($_POST["fetal-heart-rate"])){
    $patient->insertOrUpdateFetusRecord($_SESSION["user_id"], $_POST["fetal-weight"], $_POST["fetal-heart-rate"]);
}

?>