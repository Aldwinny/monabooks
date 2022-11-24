<?php

session_start();
include "general.php";

?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts("About Us", false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/about.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>

    <div class="section container-fluid d-flex flex-column align-items-center">
        <!--<h1 class="heading-accent">About Us</h1>-->
        <div class="logo-wrapper">
            <img src="assets/media/images/logo/logo-tall.png" class="full-logo img-fluid">
        </div>
        <div class="container about">
            <p>Creating your own costume and prop can be time-consuming and expensive as you encounter trial and error while creating them. Most events will also have short deadlines for cosplay-related competitions. Lock & Load sees this as an opportunity to exercise its goals across this untapped market by offering an alternative solution by being a one-stop shop to sell cosplay props and costumes.</p>
            <p><b>Lock & Load</b> will cater to everyone from avid cosplayers to regular persons who are in need or others who just want to buy for personal use. We offer high-quality 3D printed prop guns and knitted costumes, aside from those, the site will also display t-shirts, hoodies, figurines, and keychains themed for Valorant.</p>
        </div>
        <h1 class="heading-accent">Our Team</h1>
        <div class="container d-flex flex-wrap align-items-center justify-content-center member-wrapper">
            <div class="member">
                <div class="img-wrapper">
                    <img class="img-fluid member-img" src="assets/media/images/team/isaguirre.jpg">
                </div>
                <p>Cassandra A. Isaguirre</p>
            </div>
            <div class="member">
                <div class="img-wrapper">
                    <img class="img-fluid member-img" src="assets/media/images/team/reyes.jpg">
                </div>
                <p>Aldwin Dennis L. Reyes</p>
            </div>
        </div>
    </div>

    <?php

        // footer
        footer();

        // general scripts
        body_scripts();
    
    ?>\

</body>
</html>