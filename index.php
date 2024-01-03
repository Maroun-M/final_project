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
  <link rel="icon" type="image/png" href="./images/logo-removebg-preview.png">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
</head>

<body>
  <?php
  $logged_in;
  session_start();
  if (isset($_SESSION['user_id'])) {
    $logged_in = 1;
  } else {
    $logged_in = 0;
  }
  ?>

  <div class="logged-in" hidden>
    <p class="login-status">
      <?php
      echo $logged_in;
      ?>
    </p>
  </div>




  <div class="front">
    <div class="ouvatech-img-container">
      <!-- <img src="./images/logo-removebg-preview.png" alt="" /> -->
      <div class="ouvatech-description-container">
        <h1>Ouvatech</h1>
        <h3 class="col-sm-12 col-lg-6 content">Empowering pregnant women to monitor their health and well-being through
          innovative technology and compassionate care, we are dedicated to
          improving maternal and fetal health outcomes and promoting a safe and
          fulfilling pregnancy experience for all women. <br />
          <button class="m-5 px-5 py-2 btn-ouva-purple" onClick="document.querySelector('.vh-150').scrollIntoView();">
            Sign Up Now!
          </button>
        </h3>
      </div>
    </div>
  </div>

  <div class="navbar-wrap">
    <div class="navbar-container">
      <div class="nav-logo-container">
        <a href="./index.php"> <img src="./images/logo-removebg-preview.png" alt=""></a>
      </div>
      <div class="nav-btns-container">
        <p class="nav-link" class="nav-link active" onClick="document.querySelector('.front').scrollIntoView();">
          HOME
        </p>
        <p class="nav-link" onClick="document.querySelector('.logos-wrap').scrollIntoView();">
          FEATURES
        </p>
        <p class="nav-link" onClick="document.querySelector('.info-wrapper').scrollIntoView();">
          INFO
        </p>
        <p class="nav-link" onClick="document.querySelector('.vh-150').scrollIntoView();">
          REGISTER
        </p>
        <p class="nav-link login-nav-btn"><a href="./login.php">LOGIN</a></p>
        <p class="nav-link account-nav-btn" ><a href="./src/login/userLogin.php">ACCOUNT</a></p>
        <p class="nav-link logout-nav-btn" ><a href="./src/login/logout.php">LOGOUT</a></p>
        <i class="bi bi-list home-list"></i>
      </div>
    </div>
  </div>
  <div class="navbar-overlay">
    <div class="navbar-mobile-container">
    
      <div class="nav-btns-mobile-container">
        <p class="nav-link" class="nav-link active" onClick="document.querySelector('.front').scrollIntoView();">
          Home
        </p>
        <p class="nav-link" onClick="document.querySelector('.logos-wrap').scrollIntoView();">
          Features
        </p>
        <p class="nav-link" onClick="document.querySelector('.info-wrapper').scrollIntoView();">
          Info
        </p>
        <p class="nav-link" onClick="document.querySelector('.vh-150').scrollIntoView();">
          Register
        </p>
        <p class="nav-link login-nav-btn-mobile"><a href="./login.php">Login</a></p>
        <p class="nav-link account-nav-btn" ><a href="./src/login/userLogin.php">Account</a></p>
        <p class="nav-link logout-nav-btn" ><a href="./src/login/logout.php">Logout</a></p>
      </div>
      
    </div>
    <div class="empty-div">
        
      </div>
  </div>
  <!-- features wrapper -->
  <div class="logos-wrap">
    <div class="logo-container">
      <div class="logo-div">
        <img src="./logos/logo1.svg" />
      </div>
      <div class="header-container">
        <h2>Journey</h2>
        <p>
          Take charge of your pregnancy journey with our innovative femtech
          platform. From personalized fitness plans to emotional wellness
          resources, Ouvatech will help you stay healthy and happy throughout
          your pregnancy.
        </p>
      </div>
    </div>
    <div class="logo-container">
      <div class="logo-div">
        <img src="./logos/logo2.svg" />
      </div>
      <div class="header-container">
        <h2>Preventions</h2>
        <p>
          From gestational diabetes to preeclampsia, there are many high-risk
          complications that can occur during pregnancy. With Ouvatech
          platform, you can stay informed and take action to prevent them.
        </p>
      </div>
    </div>
    <div class="logo-container">
      <div class="logo-div">
        <img src="./logos/logo3.svg" />
      </div>
      <div class="header-container">
        <h2>Care</h2>
        <p>
          Ouvatech enables you to provide remote care and use advanced triage
          methods for a safer, more personalized pregnancy experience.
        </p>
      </div>
    </div>
    <div class="logo-container">
      <div class="logo-div">
        <img src="./logos/logo4.svg" />
      </div>
      <div class="header-container">
        <h2>Affordability</h2>
        <p>
          By covering remote monitoring with ouvatech platform, medical
          insurance companies can reduce the cost of high-risk complications
          providing thus more affordable healthcare for pregnant women.
        </p>
      </div>
    </div>
    <div class="logo-container">
      <div class="logo-div">
        <img src="./logos/logo5.svg" />
      </div>
      <div class="header-container">
        <h2>Time</h2>
        <p>
          With 130 Millions pregnancies each year, there is no time to waste
          in improving women's Healthcare.
        </p>
      </div>
    </div>
  </div>
  <!-- end of features wrapper -->
  <!-- <div class="info-section-wrap gradient-custom">

        <div class="info-section-container">
            
            <div class="text-container">
                <h1>Title</h1>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur facere minus, voluptate impedit quibusdam doloremque maiores quia eveniet molestias corrupti quasi cumque error voluptatibus quis, quae qui eum. Enim, obcaecati!</p>
            </div>
            <div class="info-section-img-container">
                <div class="img-container">
                    <img src="./woman.png" alt="">

                </div>
            </div>
        </div>
    </div> -->

  <div class="info-wrapper">
    <div class="info-col-container">
      <div class="info-img-container">
        <img src="./images/Picture1.png" alt="" />
      </div>
      <div class="info-title-container">
        <p>Continuous remote monitoring Maternal<br> and Fetal Health</p>
      </div>
    </div>
    <div class="info-col-container">
      <div class="info-img-container">
        <img src="./images/Picture2.png" alt="" />
      </div>
      <div class="info-title-container">
        <p>Educational Resources & Support- AI-powered</p>
      </div>
    </div>
    <div class="info-col-container">
      <div class="info-img-container">
        <img src="./images/Picture3.png" alt="" />
      </div>
      <div class="info-title-container">
        <p>Personalized Care</p>
      </div>
    </div>
    <div class="info-col-container">
      <div class="info-img-container">
        <img src="./images/Picture4.png" alt="" />
      </div>
      <div class="info-title-container">
        <p>Medication & Appointment management</p>
      </div>
    </div>
    <div class="info-col-container">
      <div class="info-img-container">
        <img src="./images/Picture5.png" alt="" />
      </div>
      <div class="info-title-container">
        <p>Mental Health and Wellbeing</p>
      </div>
    </div>
    <div class="info-col-container">
      <div class="info-img-container">
        <img src="./images/Picture6.png" alt="" />
      </div>
      <div class="info-title-container">
        <p>Medical Triage</p>
      </div>
    </div>
  </div>

  <!-- start of sign up div -->
  <section class="vh-150 registration-form z-0">
    <div class="container py-5 h-100 z-0">
      <div class="row justify-content-center align-items-center h-100 z-0">
        <div class="col-12 col-lg-9 col-xl-7 z-0">
          <div class="card shadow-2-strong card-registration " style="border-radius: 15px; z-index: 0;">
            <div class="card-body p-4 p-md-5">
              <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Registration Form</h3>
              <form action="./registration.php" method="POST" enctype="multipart/form-data"
                onsubmit="validateForm(event)">
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <input type="text" id="firstName" name="firstName"  class="form-control form-control-lg"
                        required />
                      <label class="form-label" for="firstName" >First Name</label>
                    </div>
                    <div id="firstName-error" class="error-message"></div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <input type="text" id="lastName" name="lastName" class="form-control form-control-lg" required />
                      <label class="form-label" for="lastName">Last Name</label>
                    </div>
                    <div id="lastName-error" class="error-message"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-4 pb-2">
                    <div class="form-outline">
                      <input type="email" id="emailAddress" name="emailAddress" class="form-control form-control-lg"
                        required />
                      <label class="form-label" for="emailAddress">Email</label>
                    </div>
                    <div id="emailAddress-error" class="error-message"></div>
                  </div>
                  <div class="col-md-6 mb-4 pb-2">
                    <div class="form-outline">
                      <input type="tel" id="phoneNumber" name="phoneNumber" value="+961 "
                        class="form-control form-control-lg" required />
                      <label class="form-label" for="phoneNumber">Phone Number</label>
                    </div>
                    <div id="phoneNumber-error" class="error-message"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-4 pb-2">
                    <div class="form-outline">
                      <input type="password" id="password" name="password" class="form-control form-control-lg"
                        required />
                      <label class="form-label" for="password">Password</label>
                    </div>
                    <div id="password-error" class="error-message"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-4 pb-2">
                    <div class="form-outline">
                      <input type="password" id="confirm-password" name="confirm-password"
                        class="form-control form-control-lg" required />
                      <label class="form-label" for="confirm-password">Confirm Password</label>
                    </div>
                    <div id="confirm-password-error" class="error-message"></div>
                  </div>
                </div>

                <div class="mt-4 pt-2">
                  <input class="btn btn-primary btn-lg" type="submit" value="Submit" name="submit-registration" />
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end of sign up div -->

  <!-- footer container -->
  <hr />
  <div class="footer-container">
    <div class="footer-content">
      <p>Ouvatech© 2023. All rights reserved.</p>
    </div>
  </div>
  <!-- footer container end -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
    crossorigin="anonymous"></script>



</body>

</html>