<?php 
include_once("./Patient.php");
session_start();
$conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
$patient = new Patient($conn);
if (!isset($_SESSION['user_id'])) {
    // Handle the case when the user is not logged in
    echo "User not logged in";
    exit();
}
// Set patient's user ID
if(isset($_POST["dob"]) && isset($_POST["location"]) && isset($_POST["previous-pregnancies"]) && isset($_POST["LMP"]) ){
    if(!$patient->checkID($_SESSION['user_id'])){
        $patient->setUserId();
    }
    $patient->setDateOfBirth($_POST["dob"]);
    $patient->setLocation($_POST["location"]);
    $patient->setPreviousPregnancies($_POST["previous-pregnancies"]);
    $patient->setLMP($_POST["LMP"]);
    $patient->updatePatientEDD();
    $patient->updateGestationAge();
    $patient->updatePregnancyStage();
    if(isset($_POST["diabetics"])){
        if($_POST["diabetics"] === "true"){
            $patient->setDiabetic(1);
        }
        
    } else {
        $patient->setDiabetic(0);
    }
    
    
    if(isset($_POST["hypertension"])){
        $patient->setHypertension(1);
    } else {
        $patient->setHypertension(0);
    }

        header("location: ../../userInfo.php?update=successful");
}


?>