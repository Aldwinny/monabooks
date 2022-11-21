<?php
session_start();
include "general.php";
include "assets\scripts\php\connection.php";

$bestsellers_type = ["fiction", "non-fiction", "drama", "poetry"];
$bestsellers_id = [1, 2, 3, 4];

?>

<!DOCTYPE html>
<html>

<head>
    
    <?php head_scripts("MONA BOOKS", true); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/home.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>
    
    <!-- header -->
    <div id="featured-items" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#featured-items" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#featured-items" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#featured-items" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="slide-bg" src="assets\media\images\svg-bg\range.png">
                <div class="container-fluid d-flex flex-column align-items-center justify-content-center item-featured item-featured-x">
                    <div class="item-featured-content">
                        <img src="assets\media\images\products\prime.png">
                        <h1 style="font-size: 75px;">Prime Vandal Replica</h1>
                        <p>Valorant Weapon Prime Vandal Cosplay Replica Prop</p>
                        <div class="horizontal-center">
                            <a href="indiv-product.php?type=gun_skins&id=2">
                                <button type="button" class="btn shop-now">Shop Now</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="slide-bg" src="assets\media\images\svg-bg\split.png">
                <div class="container-fluid d-flex align-items-center justify-content-center item-featured item-featured-y">
                    <div class="item-featured-content d-flex align-items-center justify-content-center">
                        <div class="item-featured-y-desc">
                            <h1 style="font-size: 75px;">Killjoy Cosplay Suit</h1>
                            <p>Check out our Killjoy Valorant cosplay selection for the very best in unique or custom, handmade pieces from our clothing shops.</p>
                            <a href="indiv-product.php?type=agent_clothes&id=3">
                                <button type="button" class="btn shop-now">Shop Now</button>
                            </a>
                        </div>
                        <img src="assets\media\images\products\killjoy.png" class="tall-img with-shadow">
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="slide-bg" src="assets\media\images\svg-bg\fracture.png">
                <div class="container-fluid d-flex flex-column align-items-center justify-content-center item-featured item-featured-y">
                    <div class="item-featured-content d-flex align-items-center justify-content-center">
                        <div class="item-featured-y-desc">
                            <h1 style="font-size: 75px;">Protocol Hoodie</h1>
                            <p>Clutch in style with this mega-comfortable hoodie.</p>
                            <a href="indiv-product.php?type=riot_merch&id=1">
                                <button type="button" class="btn shop-now">Shop Now</button>
                            </a>
                        </div>
                        <img src="assets\media\images\products\hoodie_merch.png" class="long-img with-shadow">
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#featured-items" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#featured-items" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    
    <!-- bestsellers -->
    <div class="section bestsellers container-fluid d-flex flex-column align-items-center">
        <h1 class="heading-accent">Our Bestsellers</h1>
        <div class="container-fluid d-flex justify-content-center">
            <?php
                for($i = 0; $i < count($bestsellers_type); $i++) {
                    $result = mysqli_query($connection, "SELECT * FROM $bestsellers_type[$i] WHERE id=$bestsellers_id[$i]");
                    while($row = mysqli_fetch_array($result)) {
                        ?>
                        <div class="card product">
                            <div class="card-body">
                                <img class="img-fluid" src="<?php echo $row['product_img']; ?>">
                                <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                                <p class="card-text">₱<?php echo $row['product_price']; ?></p>
                                <a href="indiv-product.php?type=<?php echo $bestsellers_type[$i]; ?>&id=<?php echo $row['id']; ?>" title="Learn More...">
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
        <a class="more" href="products.php?type=all">
            <button class="btn btn-primary main-button">See More Products</button>
        </a>
    </div>
    
    <!-- scripts -->
    <?php

        // footer
        footer();

        // general scripts
        body_scripts();
    
    ?>
    <script src="assets\scripts\js\home.js?v=<?php echo time(); ?>"></script>   
</body>
</html>