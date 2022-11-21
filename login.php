<?php
session_start();
include "general.php";
include "assets/scripts/php/connection.php";

if(isset($_POST['email'])){
    $adminEmail="admin@gmail.com";
	$email=$_POST['email'];
	$pass=$_POST['pass'];

	$sql="select * from user_table where email = '".$email."' AND pass = '".$pass."' limit 1";

	$result = mysqli_query($connection, $sql);

	if(mysqli_num_rows($result)==1){
        session_start();
		$_SESSION['email'] = $email;
        if($email==$adminEmail){
            header("Location: http://locknload.rf.gd//admin-main.php");
        }
        else{
            header("Location: http://locknload.rf.gd//index.php");
        }
        
        exit();
	}
	else{
        ?>
        <script type="text/javascript">
            alert("An error occurred. Please try again.");
        </script>
        <?php
	}
}

?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts("Login", false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/login.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>

    <div class="form container-fluid section">
        <h1>Log In</h1>
        <form name="login-form" class="custom-form" method="POST" enctype="multipart/form-data" action="#">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" placeholder=" " class="form-control" autocomplete="off" required input-field>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="pass" placeholder=" " class="form-control" autocomplete="off" required input-field>
            </div>
            <button type="submit" name ="login-submit" value="LOGIN" class="btn main-button">Sign In</button>
        </form>
        <div><a href="register.php">Create an Account</a></div>
    </div>

    <?php

        // footer
        footer();

        // general scripts
        body_scripts();
    
    ?>
    <script src="assets/scripts/js/login.js?v=<?php echo time(); ?>"></script>

</body>
</html>