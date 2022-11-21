<?php

session_start();
include "general.php";
include "assets/scripts/php/connection.php";

$id = $_GET['id'];

$result = mysqli_query($connection, "SELECT * FROM user_table WHERE id ='$id'");

while($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
    $email = $row['email'];
    $pass = $row['pass'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $number = $row['number'];
    $balance = $row['balance'];
    $image = $row['user_img'];
    $address = $row['address'];
}


?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts("Edit Profile", false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/edit-profile.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>

    <div class="form container-fluid section">
        <h1>Edit Profile</h1>
        <form name="login-form" class="custom-form" method="POST" enctype="multipart/form-data" action="#">
            <div class="form-group">
                <label for="userimg" class="form-label">Profile Picture</label>
                <input type="file" name="userimg" class="form-control" accept="image/*" input-field>
            </div>
            <div class="form-group">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" name="fname" class="form-control" value="<?php echo $firstname; ?>" input-field limited-special>
            </div>
            <div class="form-group">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-control" value="<?php echo $lastname; ?>" input-field limited-special>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" input-field limited-special>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="pass" class="form-control" value="<?php echo $pass; ?>" input-field limited-special>
            </div>
            <div class="form-group">
                <label for="address" class="form-label">Contact No.</label>
                <input type="number" name="number" class="form-control" value="<?php echo $number; ?>" input-field limited-special>
            </div>
            <div class="form-group">
                <label for="address" class="form-label">Delivery Address</label>
                <textarea type="text" name="address" class="form-control" rows="2" input-field><?php echo $address; ?></textarea>
            </div>
            <div class="button-group">
                <button type="submit" name="change" class="btn submit-btn" onclick="return confirm('Are you sure you want to save changes?');">Save Changes</button>
                <button type="submit" name="cancel" class="btn submit-btn danger">Cancel</button>
            </div>
        </form>
    </div>

    <?php

        // footer
        footer();

        // general scripts
        body_scripts();

        if(isset($_POST["change"])) {

            $time = md5(time());
            $file_name = $_FILES["userimg"]["name"];

            if($file_name == "") {
                mysqli_query($connection, "UPDATE user_table SET email='$_POST[email]', pass='$_POST[pass]', firstname='$_POST[fname]', lastname='$_POST[lname]', number='$_POST[number]', address='$_POST[address]' WHERE id=$id");
            } else {
                $target_path = "assets/media/images/user/".$time.$file_name;
                $file_dbname = "assets/media/images/user/".$time.$file_name;
                move_uploaded_file($_FILES["userimg"]["tmp_name"], $target_path);

                mysqli_query($connection, "UPDATE user_table SET email='$_POST[email]', pass='$_POST[pass]', firstname='$_POST[fname]', lastname='$_POST[lname]', number='$_POST[number]', user_img='$file_dbname', address='$_POST[address]' WHERE id=$id");
            }

            ?><script>
                alert("Changes saved!");
                window.location = "profile.php?id=<?php echo $id; ?>";
            </script><?php
        }
    
    ?>
    <script src="assets/scripts/js/login.js?v=<?php echo time(); ?>"></script>

</body>
</html>