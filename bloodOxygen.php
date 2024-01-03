<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ouvatech</title>
    <link rel="stylesheet" href="style.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
        integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    <script src="./patientApp.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@^3"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@^2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>

    <link rel="icon" type="image/png" href="./images/logo-removebg-preview.png">

</head>

<body>
    <?php
    session_start();
    include_once("./src/patient/Patient.php");
    $conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
    $patient = new Patient($conn);
    if (!isset($_SESSION['user_id']) || !$patient->isPatient($_SESSION['user_id'])) { // Check if the user is logged in and is patient
        echo "You don't have access to this page.";
        header("LOCATION: ./index.php?access=unauthorized");
        exit();
    }
    if (!$patient->isUserConfirmed($_SESSION['user_id'])) {
        header("location:./confirm.php");
        exit();
    }

    if (!$patient->has_patient_record($_SESSION['user_id'])) {
        header("location:./userInfo.php");
        exit();
    }
    ?>



    <div class="dashboard-wrapper">

        <!--  sidebar section -->
        <div class="sidebar">
            <div class="sidebar-close-btn">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="sidebar-logo-container">
                <img src="./images/logo-removebg-preview.png" alt="" onclick="window.location.href = './index.php'">
            </div>
            <hr class="sidebar-divider">
            <div class="sidebar-nav-container " onclick="window.location.href = './patientMainMenu.php'">
                <div class="sidebar-nav-logo">
                    <i class="bi bi-house-fill"></i>
                </div>
                <div class="sidebar-nav-name ">
                    <p>Home</p>
                </div>
            </div>
            <hr class="sidebar-divider">
            <div class="sidebar-header">
                <p>TESTS</p>
            </div>
            <div class="sidebar-nav-container" onclick="window.location.href = './heartRate.php'">
                <div class="sidebar-nav-logo">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="sidebar-nav-name">
                    <p>Heart Rate</p>
                </div>
            </div>
            <div class="sidebar-nav-container" onclick="window.location.href = './bloodPressure.php'">
                <div class="sidebar-nav-logo">
                    <img src="./icons/blood_pressure_monitor.svg" alt="">
                </div>
                <div class="sidebar-nav-name">
                    <p>Blood Pressure</p>
                </div>
            </div>
            <div class="sidebar-nav-container" onclick="window.location.href = './temperature.php'">
                <div class="sidebar-nav-logo">
                    <i class="bi bi-thermometer-half"></i>
                </div>
                <div class="sidebar-nav-name">
                    <p>Temperature</p>
                </div>
            </div>
            <div class="sidebar-nav-container" onclick="window.location.href = './bloodGlucose.php'">
                <div class="sidebar-nav-logo">
                    <img src="./icons/diabetes.svg" alt="">
                </div>
                <div class="sidebar-nav-name">
                    <p>Blood Glucose</p>
                </div>
            </div>
            <div class="sidebar-nav-container active" onclick="window.location.href = './bloodOxygen.php'">
                <div class="sidebar-nav-logo">
                    <img src="./icons/o2-oxygen-icon.svg" alt="">
                </div>
                <div class="sidebar-nav-name">
                    <p>Blood Oxygen</p>
                </div>
            </div>
            <div class="sidebar-nav-container" onclick="window.location.href = './fetus.php'">
                <div class="sidebar-nav-logo">
                    <img src="./icons/embryo-pregnancy-icon.svg" alt="">
                </div>
                <div class="sidebar-nav-name">
                    <p>Fetus</p>
                </div>
            </div>
            <hr class="sidebar-divider">
            <div class="sidebar-header">
                <p>LAB TESTS</p>
            </div>
            <div class="sidebar-nav-container" onclick="window.location.href = './labTests.php'">
                <div class="sidebar-nav-logo">
                    <img src="./icons/labtest.svg" alt="">
                </div>
                <div class="sidebar-nav-name">
                    <p>Upload Tests</p>
                </div>
            </div>
            <hr class="sidebar-divider">
            <div class="sidebar-header">
                <p>PROFILE</p>
            </div>
            <div class="sidebar-nav-container" onclick="window.location.href = './userInfo.php'">
                <div class="sidebar-nav-logo">
                    <img src="./icons/details.svg" alt="">
                </div>
                <div class="sidebar-nav-name">
                    <p>Update Profile</p>
                </div>
            </div>
            <div class="sidebar-nav-container" onclick="window.location.href = './chooseDoctor.php'">
                <div class="sidebar-nav-logo">
                    <i class="bi bi-hand-index"></i>
                </div>
                <div class="sidebar-nav-name">
                    <p>Choose Doctor</p>
                </div>
            </div>
            <div class="sidebar-nav-container logout-btn">
                <div class="sidebar-nav-logo">
                    <img src="./icons/logout.svg" alt="">
                </div>
                <div class="sidebar-nav-name">
                    <p>Logout</p>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT -->
        <div class="content-wrap ">
            <div class="hamburger-container">
                <i class="bi bi-list"></i>
            </div>
            <div class="user-info-wrap card-display">


            <form action="./src/patient/patientEntries.php" method="POST" enctype="multipart/form-data"
                class="inputs-form">
                <div class="info-update-logo-container">
                    <img src="./icons/spO2.svg" alt="">
                </div>
                <div class="form-inputs-container">
                    <div class="inputs-header-title">
                        <p>Please insert your blood oxygen level:</p>
                        
                    </div>
                    <div class="rates-input">
                        <input type="text" id="oxygen" name="oxygen" placeholder="95" required>
                        <span class="measure-units">%</span>
                    </div>
                    <p id="oxygen-error"></p>
                    
                </div>

                <div class="btns-container forms-btns">
                    <button class="tests-btn btns-style" id="add-button" >ADD</button>
                </div>
            </form>
            </div>
        </div>

    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"
    defer></script>

</html>































