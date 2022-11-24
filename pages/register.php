<?php

session_start();
include "general.php";
include "assets/scripts/php/connection.php";

?>
<!DOCTYPE html>
<html>

<head>
    
    <?php head_scripts("Create Account", false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/login.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>

    <div class="form section container-fluid">
        <h1>Create Account</h1>
        <form class="custom-form" method="post" action="#" enctype="multipart/form-data" oninput='rptPass.setCustomValidity(rptPass.value != pass.value ? "Passwords do not match." : "")'>
            <div class="form-group">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" name="firstname" placeholder=" " class="form-control" autocomplete="off" required input-field letters-only>
                </div>

            <div class="form-group">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" name="lastname" placeholder=" " class="form-control" autocomplete="off" required input-field letters-only>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Email</label>
                <input type="email" name="email" placeholder=" " class="form-control" autocomplete="off" required input-field>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Phone Number</label>
                <input type="number" name="phone" placeholder=" " class="form-control" autocomplete="off" required input-field>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="pass" placeholder=" " class="form-control" autocomplete="off" required input-field>
            </div>

            <div class="form-group">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" name="rptPass" placeholder=" " class="form-control" autocomplete="off" required input-field>
            </div>

            <button type="submit" name="signup" class="btn main-button">Sign Up</button>
        </form>
        <div><a href="login.php">Log In</a></div>
    </div>
    
    <!-- scripts -->
    <?php

        // footer
        footer();

        // general scripts
        body_scripts();

        if(isset($_POST["signup"])){

            $email = $_POST["email"];
            $pass = $_POST["pass"];
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $phone = $_POST["phone"];

            $check = mysqli_query($connection, "SELECT email FROM user_table WHERE email='$email'");

            if(mysqli_fetch_array($check) == NULL) {

                mysqli_query($connection, "INSERT INTO `user_table` (`id`, `email`, `pass`, `firstname`, `lastname`, `number`, `balance`, `user_img`, `address`) VALUES(NULL,'$email','$pass','$firstname','$lastname','$phone', '100000', '', '')");

                ?>
                <script type="text/javascript">
                    alert("Registered succesfully!");
                    window.location.href="login.php";
                </script>
                <?php

            } else {
                ?>
                <script type="text/javascript">
                    alert("Email already taken. Please try again.");
                    window.location.href="register.php";
                </script>
                <?php
            }

            
        }
    
    ?>
    <script src="assets/scripts/js/login.js?v=<?php echo time();?>"></script>
    <script src="assets/scripts/js/form-validation.js?v=<?php echo time();?>"></script>
</body>
</html>