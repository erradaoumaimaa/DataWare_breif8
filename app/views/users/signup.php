<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link rel="shortcut icon" href="<?php echo URLROOT; ?>/assets/brand.png" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/signup.css" type="text/css">
    <style>
        .signup-body {
            background-color: #00a6e8;
        }
    </style>
</head>

<body class="signup-body">
    <div class="signup-page">
        <div class="left">
            <img src="<?php echo URLROOT; ?>/assets/logo.png" alt="logo">
            <h2>Let's work <br> Together!</h2>
        </div>
        <div class="right">
            <h4 class="title">SignUp</h4>
            <p>Let's get started! Please create an account.</p>
            <form action="<?php echo URLROOT; ?>/userController/signup" method="post">
                <div class="labels med">
                    <label for="fname">
                        <h4>First Name</h4>
                        <input type="text" name="fname" id="fname" required placeholder="Enter First Name">
                    </label>
                    <label for="lname">
                        <h4>Last Name</h4>
                        <input type="text" name="lname" id="lname" required placeholder="Enter Last Name">
                    </label>
                </div>
                <label for="email">
                    <h4>Email</h4>
                    <input type="email" name="email" id="email" placeholder="example@gmail.com" required>
                </label>
                <div class="labels med">
                    <label for="service">
                        <h4>Service</h4>
                        <input type="text" name="service" id="service" placeholder="Full-Stack Dev">
                    </label>
                    <label for="tel">
                        <h4>Phone Number</h4>
                        <input type="tel" name="tel" id="tel" placeholder="07XXXXXXXX">
                    </label>
                </div>
                <label for="password">
                    <h4>Password</h4>
                    <input type="password" name="password" id="password" placeholder="Enter Password" required>
                </label>
                <div class="btns">
                    <button type="reset">Cancel</button>
                    <button type="submit" name="sendF">Submit</button>
                </div>
            </form>
            <p class="sign">Alreadt Have an account? <a href="../index.php">LogIn</a></p>
        </div>
    </div>
</body>

</html>