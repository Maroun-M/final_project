<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ouvatech</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700;1,900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
  <script src="./app.js" defer></script>

</head>

<body>
  <div class="wrapper  gradient-custom-login">

    <?php
    include_once("./src/login/login.php");
    $user_login = new login();
    // Start session
    session_start();
    $access_lvl;
    $user_id;
    if (isset($_SESSION['user_id'])) {
      $access_lvl = $user_login->getUserAccessLevel($_SESSION['user_id']);
      $user_id = $_SESSION['user_id'];
    }

    if (isset($user_id) && $access_lvl == 1) { // Check if the user is logged in
      // User is logged in, redirect them to the home page or any other page
      header("Location: ./patientMainMenu.php");
      exit;
    } else if (isset($user_id) && $access_lvl == 2) {
      header("Location: ./doctorMainMenu.php");
      exit;
    } else if(isset($user_id) && $access_lvl == 3) {
      header("LOCATION: ./admin.php");
    }
    if (isset($user_id)) {
      header('location: ./index.html');
      exit;
    }
    // Generate CSRF token
    $_SESSION['token'] = bin2hex(random_bytes(32));
    ?>



    <div class="login-form-container">
      <div class="header">
        <div class="logo-img-container">
          <img src="./images/logo.jfif" alt="">
        </div>
        <h2>LOGIN</h2>
        <p>Please enter your email or phone number and password</p>
      </div>
      <form id="loginForm" action="./src/login/userLogin.php" method="POST" enctype="multipart/form-data">
        <div class="input-container">
          <input type="text" id="email" name="email" placeholder="Email" required> <br>
          <div id="emailError" class="error-message"></div>
          <input type="password" id="password" name="password" placeholder="Password" required><br>
          <div id="passwordError" class="error-message" style="color:purple;"></div>
          <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
        </div>
        <div class="reset-login-container">
          <a href="./forgotPassword.php">
            <p >Forgot Password?</p>
          </a>
          <div class="login-btn-container">
            <button class="login-btn" id="loginBtn" disabled>LOGIN</button>
          </div>
        </div>
        <div class="signup-prompt-container">
          <p>Don't have an account? <span class="sign-up-btn">
              Sign Up
            </span></p>
        </div>
      </form>
    </div>


  </div>
  <!-- footer container end -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
    crossorigin="anonymous"></script>
</body>

</html>