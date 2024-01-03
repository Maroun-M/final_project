<?php
include("../patient/Patient.php");
$conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
session_start();
$patient = new patient($conn);

if (!isset($_SESSION['user_id'])) {
    // Handle the case when the user is not logged in
    echo "User not logged in";
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    
    if(isset($_GET['patientRequest']) && $_GET['patientRequest'] === "1"){
        if($_GET['type'] === "Blood Glucose"){
            $patient->getBloodGlucoseData($_SESSION['user_id'], $_GET['range']);
        } elseif($_GET['type'] === "Blood Oxygen"){
            $patient->getBloodOxygenData($_SESSION['user_id'], $_GET['range']);
        }elseif($_GET['type'] === "Heart Rate"){
            $patient->getHRData($_SESSION['user_id'], $_GET['range']);
        }elseif($_GET['type'] === "Blood Pressure"){
            $patient->getBPData($_SESSION['user_id'], $_GET['range']);
        }elseif($_GET['type'] === "Fetus Data"){
            $patient->getFetusData($_SESSION['user_id'], $_GET['range']);
        }elseif($_GET['type'] === "Temperature"){
            $patient->getTemperatureData($_SESSION['user_id'], $_GET['range']);
        }elseif($_GET['type'] === "Lab Tests"){
            $patient->getUserFilesData($_SESSION['user_id'], $_GET['range']);
        }
        
    }


    elseif (isset($_GET['patient'])) {
        $patient->getRecentUserData($_GET['patient']);
    } 
    elseif (isset($_GET['ID']) && isset($_GET['range'])) {
        if($_GET['type'] === "Blood Glucose"){
            $patient->getBloodGlucoseData($_GET['ID'], $_GET['range']);
        } elseif($_GET['type'] === "Blood Oxygen"){
            $patient->getBloodOxygenData($_GET['ID'], $_GET['range']);
        }elseif($_GET['type'] === "Heart Rate"){
            $patient->getHRData($_GET['ID'], $_GET['range']);
        }elseif($_GET['type'] === "Blood Pressure"){
            $patient->getBPData($_GET['ID'], $_GET['range']);
        }elseif($_GET['type'] === "Fetus Data"){
            $patient->getFetusData($_GET['ID'], $_GET['range']);
        }elseif($_GET['type'] === "Temperature"){
            $patient->getTemperatureData($_GET['ID'], $_GET['range']);
        }elseif($_GET['type'] === "Lab Tests"){
            $patient->getUserFilesData($_GET['ID'], $_GET['range']);
        }
    }
}

?>