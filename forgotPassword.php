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
    <?php
    session_start();


    ?>
    <div class="wrapper  gradient-custom-login">



        <div class="login-form-container">
            <div class="header">
                <div class="logo-img-container">
                    <img src="./images/logo.jfif" alt="">
                </div>
                <h2>Reset Your Password: </h2>
                <p>Enter your email or password:</p>
            </div>
            <form action="./src/register/forgotPassword.php" method="POST" enctype="multipart/form-data">
                <div class="input-container">
                    <input type="text" name="emailOrPhone">
                </div>
                <div class="reset-note">
                    <p align="center">A confirmation code will be sent to the registered email.</p>
                </div>
                <div class="confirm-error-handling">
                    <p class="confirm-error"></p>
                </div>
                <div class="reset-login-container">

                    <div class="login-btn-container">
                        <button type="submit" class="login-btn">Send Code</button>
                    </div>
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