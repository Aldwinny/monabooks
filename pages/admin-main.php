<?php

include "general.php";

?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts("Admin", false); ?>
    <link rel="stylesheet" href="assets\stylesheets\admin\main.css?v=<?php echo time(); ?>">
    
</head>
<body>

    <a class="btn d-flex align-items-center go-back" href="index.php">
        <ion-icon name="chevron-back"></ion-icon>
        <img src="assets\media\images\logo\logo1.png">
    </a>

    <div class="container">
        <div class="heading">
            <h1>Hello, Admin.</h1>
            <h2>What would you like to do?</h2>
        </div>
        <div class="row">
            <a class="col d-flex flex-column align-items-center justify-content-center box-link" href="admin-add.php">
                Add Product
            </a>
            <a class="col d-flex flex-column align-items-center justify-content-center box-link" href="admin-products.php">
                Edit/Delete Product
            </a>
            <a class="col d-flex flex-column align-items-center justify-content-center box-link" href="admin-review.php">
                Review Customer Feedback
            </a>
        </div>
    </div>
      
    <!-- scripts -->
    <?php

        // general scripts
        body_scripts();
    
    ?>

</body>
</html>