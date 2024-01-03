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
  <script src="./admin.js" defer></script>

</head>

<body>
  <?php
  session_start();
  include_once("./src/admin/Admin.php");
  $admin = new Admin();
  if (!isset($_SESSION['user_id']) || !$admin->isAdmin($_SESSION['user_id'])) { // Check if the user is logged in and is doctor
    echo "You don't have access to this page.";
    header("LOCATION: ./index.php?access=unauthorized");
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

    <!-- PAGE CONTENT -->
    <div class="content-wrap ">
      <div class="hamburger-container">
        <i class="bi bi-list"></i>
      </div>
      <div class="patients-tables-container card-display admin-patients-container">
        <div class="search-container">
          <h1 align=center style="font-weight:600">Patients:</h1>
          <label for="search-patients">Search by patient name:</label>
          <input type="text" id="search-patients" placeholder="Search..">
        </div>
        <div class="dr-container data-container patients-container admin-patients-holder">


        </div>
        <div class="pagination-container">

        </div>
      </div>


      <div class="patients-tables-container card-display admin-doctors-container">
        <div class="search-container">
          <h1 align=center style="font-weight:600">Doctors:</h1>

          <label for="search-doctors">Search by doctor name:</label>
          <input type="text" id="search-doctors" placeholder="Search..">
        </div>
        <div class="dr-container data-container patients-container admin-doctors-holder">

        </div>
        <div class="dr-pagination-container">

        </div>
      </div>

    </div>
    <div class="confirmation-overlay">
      <div class="confirmation-container">
        <div class="confirmation-description-container">
          Are you sure you want to delete this user?
        </div>
        <div class="confirmation-buttons-container">
            <button class="confirmation-btn" id="yes-btn">Yes</button>
            <button class="confirmation-btn" id="no-btn">No</button>

        </div>
      </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
  integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"
  defer></script>

</html>