<?php

session_start();
include "general.php";
include "assets/scripts/php/connection.php";

$type = $_GET["type"];
$showAll = false;

if($type == "all") {
    $pagetitle = "All Products";
    $db_array = ["fiction", "non_fiction", "drama", "poetry", "folktale", "historical", "educational", "children_books"];
    $showAll = true;
} else if($type == "fiction") {
    $pagetitle = "Fiction";   
} else if($type == "non_fiction") {
    $pagetitle = "Non-Fiction";
} else if($type == "drama") {
    $pagetitle = "Drama";
} else if($type == "poetry") {
    $pagetitle = "Poetry";
} else if($type == "folktale") {
    $pagetitle = "Folktale";
} else if($type == "historical") {
    $pagetitle = "Historical";
} else if($type == "educational") {
    $pagetitle = "Educational";
} else if($type == "children_books") {
    $pagetitle = "Children Books";
}
?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts($pagetitle, false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/products.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>

    <div class="header container-fluid">
        <div class="links d-flex align-items-center">
            <a href="index.php">Home</a>
            <ion-icon name="chevron-forward-outline"></ion-icon>
            <?php
                if(!$showAll) {
                    ?>
                    <a href="products.php?type=all">All Products</a>
                    <ion-icon name="chevron-forward-outline"></ion-icon>
                    <?php
                }
            ?>
        </div>
        <h1 style="font-size: 75px;"><?php echo $pagetitle; ?></h1> 
        <img src="assets/media/images/svg-bg/ascent.jpg" class="header-bg">
    </div>

    <div class="section products-list container-fluid d-flex flex-column align-items-center">
        <div class="container d-flex justify-content-center flex-wrap">
            <?php
            if($showAll) {
                foreach($db_array as $db) {
                    $result = mysqli_query($connection, "SELECT * FROM $db");
                    while($row = mysqli_fetch_array($result)) {
                        ?>
                        <div class="card product">
                            <div class="card-body">
                                <img class="img-fluid" src="<?php echo $row['product_img']; ?>">
                                <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                                <p class="card-text">₱<?php echo $row['product_price']; ?></p>
                                <a href="indiv-product.php?type=<?php echo $db; ?>&id=<?php echo $row['id']; ?>" title="Learn More...">
                                    <div class="product-overlay">
                                        <button class="btn"><ion-icon name="add-outline"></ion-icon></button>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php
                    }
                }
            } else {
                $result = mysqli_query($connection, "SELECT * FROM $type");
                while($row = mysqli_fetch_array($result)) {
                    ?>
                    <div class="card product">
                        <div class="card-body">
                            <img class="img-fluid" src="<?php echo $row['product_img']; ?>">
                            <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                            <p class="card-text">₱<?php echo $row['product_price']; ?></p>
                            <a href="indiv-product.php?type=<?php echo $type; ?>&id=<?php echo $row['id']; ?>" title="Learn More...">
                                <div class="product-overlay">
                                    <button class="btn"><ion-icon name="add-outline"></ion-icon></button>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
        </div>
    </div>
    <!-- scripts -->
      
    <?php

        // footer
        footer();

        // general scripts
        body_scripts();
    
    ?>
    <script src="assets/scripts/js/products.js?v=<?php echo time(); ?>"></script>
</body>
</html>