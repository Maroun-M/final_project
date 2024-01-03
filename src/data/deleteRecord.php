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
$user_id = $_SESSION['user_id'];

// Check the data type passed in the GET request
if (isset($_POST['dataType']) && isset($_POST['recordId'])) {
    $dataType = $_POST['dataType'];
    // Call the appropriate function based on the data type
    switch ($dataType) {
        case 'Blood Glucose':
            $recordId = $_POST['recordId'];

            $result = $patient->deleteBloodGlucoseRecord($user_id, $recordId);
            break;
        case 'Blood Oxygen':
            $recordId = $_POST['recordId'];
            $result = $patient->deleteBloodOxygenRecord($user_id, $recordId);
            break;
        case 'Fetus Data':
            $recordId = $_POST['recordId'];
            $result = $patient->deleteFetusRecord($user_id, $recordId);
            break;
        case 'Blood Pressure':
            $recordId = $_POST['recordId'];
            $result = $patient->deleteBP($user_id, $recordId);
            break;
        case 'Heart Rate':
            $recordId = $_POST['recordId'];
            $result = $patient->deleteHR($user_id, $recordId);
            break;
        case 'Temperature':
            $recordId = $_POST['recordId'];
            $result = $patient->deleteTemperatureRecord($user_id, $recordId);
            break;
        case 'Lab Tests':
            $recordId = $_POST['recordId'];
            $result = $patient->deleteUserFile($user_id, $recordId);
            break;
        default:
            // Handle the case when the data type is not recognized
            echo "Invalid data type";
            exit();
    }
}
?>