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
  <script src="./doctorApp.js" defer></script>

</head>
<body>
<div class="dashboard-wrapper">

<?php
session_start();
include_once("./src/admin/Admin.php");
include_once("./src/doctor/Doctor.php");
$conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
$doctor = new Doctor();
$admin = new Admin();
if (!isset($_SESSION['user_id'])) { // Check if the user is logged in and is a doctor
  echo "You don't have access to this page.";
  header("LOCATION: ./index.php?access=unauthorized");
  exit();
}
if ((!$doctor->isDoctor($_SESSION['user_id']) || !$doctor->has_doctor_record($_SESSION['user_id'])) && !$admin->isAdmin($_SESSION['user_id'])) { // Check if the user is logged in and is a doctor
  echo "You don't have access to this page.";
  header("LOCATION: ./index.php?access=unauthorized");
  exit();
}
if (!$doctor->isDoctorConfirmed($_SESSION['user_id'])) {
  header("location:./confirm.php");
  exit();
}

// Check the user type and echo the corresponding sidebar HTML
if ($doctor->isDoctor($_SESSION['user_id'])) {
  ?>
  <!-- Doctor sidebar -->
  <div class="sidebar">
    <div class="sidebar-close-btn">
      <i class="bi bi-x-circle"></i>
    </div>
    <div class="sidebar-logo-container">
      <img src="./images/logo-removebg-preview.png" alt="" onclick="window.location.href = './index.php'">
    </div>
    <hr class="sidebar-divider">
    <div class="sidebar-nav-container active" onclick="window.location.href = './doctorMainMenu.php'">
        <div class="sidebar-nav-logo">
        <i class="bi bi-people-fill"></i>
        </div>
        <div class="sidebar-nav-name ">
          <p >Patients</p>
        </div>
      </div>
    <hr class="sidebar-divider">

    <div class="sidebar-header">
      <p>PROFILE</p>
    </div>
    <div class="sidebar-nav-container" onclick="window.location.href = './doctorInfo.php'">
      <div class="sidebar-nav-logo">
        <img src="./icons/details.svg" alt="">
      </div>
      <div class="sidebar-nav-name">
        <p>Update Profile</p>
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
  <?php
} else {
  ?>
  <!-- Other user sidebar -->
  <div class="sidebar">
      <div class="sidebar-close-btn">
        <i class="bi bi-x-circle"></i>
      </div>
      <div class="sidebar-logo-container">
        <img src="./images/logo-removebg-preview.png" alt="" onclick="window.location.href = './index.php'">
      </div>
      <hr class="sidebar-divider">
      <div class="sidebar-nav-container active" onclick="window.location.href = './admin.php'">
        <div class="sidebar-nav-logo">
          <i class="bi bi-people-fill"></i>
        </div>
        <div class="sidebar-nav-name ">
          <p>Manage Users</p>
        </div>
      </div>
      <div class="sidebar-nav-container" onclick="window.location.href = './manageUsers.php'">
        <div class="sidebar-nav-logo">
        <i class="bi bi-plus-square-dotted"></i>
        </div>
        <div class="sidebar-nav-name ">
          <p>Add Users</p>
        </div>
      </div>
      <hr class="sidebar-divider">



      <div class="sidebar-nav-container logout-btn">
        <div class="sidebar-nav-logo">
          <img src="./icons/logout.svg" alt="">
        </div>
        <div class="sidebar-nav-name">
          <p>Logout</p>
        </div>
      </div>
    </div>
  <?php
}
?>




    

    <!-- PAGE CONTENT -->
    <div class="content-wrap ">
    <div class="hamburger-container">
        <i class="bi bi-list"></i>
      </div>
    
      <div class="btns-container card-display">
        <p>Filter By:</p>
        <button class="tests-btn btns-style">Blood Glucose</button>
        <button class="tests-btn btns-style">Blood Oxygen</button>
        <button class="tests-btn btns-style">Heart Rate</button>
        <button class="tests-btn btns-style">Temperature</button>
        <button class="tests-btn btns-style">Blood Pressure</button>
        <button class="tests-btn btns-style">Fetus Data</button>
        <button class="tests-btn btns-style">Lab Tests</button>
        <br>
        <button id="weekly-btn" class="btns-style">Weekly</button>
        <button id="monthly-btn" class="btns-style">Monthly</button>
        <button id="yearly-btn" class="btns-style">Yearly</button>
      </div>
      <div class="charts-container card-display">
      
        <canvas id="data-chart"></canvas>

      </div>
      
      <div class="tables-container card-display">
        <div class="dr-container tests-container one-patient-tests-container patient-table tests-data-container">
          
        </div>
      
      </div>
    </div>

  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
  integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"
  defer></script>

</html>





