<?php

session_start();
include "general.php";
include "assets/scripts/php/connection.php";

$email = $_SESSION['email'];
$id = $_GET['id'];

if($id != "") {
    $result = mysqli_query($connection, "SELECT * FROM user_table WHERE id ='$id'");
} else {
    $result = mysqli_query($connection, "SELECT * FROM user_table WHERE email ='$email'");
}


while($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
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

    <?php head_scripts("My Profile", false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/profile.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>

    <div class="container section d-flex align-items-top">
        <div class="img-wrapper">
            <?php
            if($image == "") {
                $u_img = "https://via.placeholder.com/300";
            } else {
                $u_img = $image;
            }
            ?>
            <img src="<?php echo $u_img; ?>" class="profile-pic img-fluid">
        </div>
        <div class="info">
            <h1><?php echo $firstname." ".$lastname; ?></h1>
            <div class="balance d-flex align-items-center">
                Balance: <span class="bal">₱<?php echo $balance; ?></span>
            </div>
            <div class="personal-info">
                <div class="info-content">
                    <div class="title">Email</div>
                    <div class="value"><?php echo $email; ?></div>
                </div>
                <div class="info-content">
                    <div class="title">Password</div>
                    <div class="value">
                        <?php
                            for($i = 0; $i < strlen($pass); $i++) {
                                echo "&#8226;";
                            }
                        ?>
                    </div>
                </div>
                <div class="info-content">
                    <div class="title">Contact No.</div>
                    <div class="value"><?php echo $number; ?></div>
                </div>
                <div class="info-content">
                    <div class="title">Delivery Address</div>
                    <div class="value">
                        <?php
                            if($address == "") {
                                echo "<i>To be added...</i>";
                            } else {
                                echo $address;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <a href="edit-profile.php?id=<?php echo $id; ?>" class="edit">
            <button class="btn edit-button d-flex align-items-center"><ion-icon name="settings-outline"></ion-icon></button>
        </a>
    </div>

    <?php

        // footer
        footer();

        // general scripts
        body_scripts();
    
    ?>

</body>
</html>