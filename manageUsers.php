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
      <div class="sidebar-nav-container" onclick="window.location.href = './admin.php'">
        <div class="sidebar-nav-logo">
          <i class="bi bi-people-fill"></i>
        </div>
        <div class="sidebar-nav-name ">
          <p>Manage Users</p>
        </div>
      </div>
      <div class="sidebar-nav-container active" onclick="window.location.href = './admin.php'">
        <div class="sidebar-nav-logo">
          <i class="bi bi-plus-square-dotted"></i>
        </div>
        <div class="sidebar-nav-name ">
          <p>Add/drop Users</p>
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
      <div class="patients-tables-container card-display">
        <div class="force-add-title">
          <h2>Force Add User</h2>
        </div>
        <div class="force-add-form-container">
        <form action="./src/admin/addUser.php" method="POST" enctype="multipart/form-data">
        <div class="force-add-input">
          <label for="fname">First Name:</label><br>
          <input type="text" id="fname" name="fname" required>
        </div>
        <div class="force-add-input">
          <label for="lname">Last Name:</label><br>
          <input type="text" id="lname" name="lname" required>
        </div>
        <div class="force-add-input">
          <label for="mobile">Phone Number:</label><br>
          <input type="text" id="mobile" name="mobile" required>
        </div>
        <div class="force-add-input">
          <label for="email">Email:</label><br>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="force-add-input">
          <label for="password">Password:</label><br>
          <input type="text" id="password" name="password" required>
        </div>
        <div class="force-add-input">
          <label for="type">User Type:</label><br>
          <select id="type" name="type" required>
            <option value="" disabled selected>Select a type</option>
            <option value="Patient">Patient</option>
            <option value="Doctor">Doctor</option>
          </select>
        </div>
        <div class="force-add-input">
          <input type="submit" id="submit-btn-force" value="Add User">
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