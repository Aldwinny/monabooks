<?php

session_start();
include "general.php";
include "assets/scripts/php/connection.php";

$type = $_GET["type"];
$id = $_GET["id"];

$result = mysqli_query($connection, "SELECT * FROM $type WHERE id=$id");
while($row = mysqli_fetch_array($result)) {
    $name = $row['product_name'];
    $desc = $row['product_desc'];
    $image = $row['product_img'];
    $price = $row['product_price'];
}

if($type == "fiction") {
    $category = "Fiction"; 
} else if($type == "fiction") {
    $category = "Fiction";   
} else if($type == "non_fiction") {
    $category = "Non-Fiction";
} else if($type == "drama") {
    $category = "Drama";
} else if($type == "poetry") {
    $category = "Poetry";
} else if($type == "folktale") {
    $category = "Folktale";
} else if($type == "historical") {
    $category = "Historical";
} else if($type == "educational") {
    $category = "Educational";
} else if($type == "children_books") {
    $category = "Children Books";
}

?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts($name, false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/indiv-product.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>

    <div class="section container-fluid">
        <div class="links d-flex align-items-center">
            <a href="index.php">Home</a>
            <ion-icon name="chevron-forward-outline"></ion-icon>
            <a href="products.php?type=all">All Products</a>
            <ion-icon name="chevron-forward-outline"></ion-icon>
            <a href="products.php?type=<?php echo $type; ?>"><?php echo $category; ?></a>
            <ion-icon name="chevron-forward-outline"></ion-icon>
        </div>
        <div class="d-flex content">
            <div class="img-wrapper">
                <img src="<?php echo $image; ?>" class="product-img img-fluid">
            </div>
            <div class="info">
                <form method="POST" enctype="multipart/form-data">
                    <h1 class="name"><?php echo $name; ?></h1>
                    <div class="price">₱<?php echo $price; ?></div>
                    <div class="desc"><?php echo $desc; ?></div>
                    <div class="quantity d-flex align-items-center">
                        <p>Quantity:</p>
                        <input type="number" id="qty" name="qty" class="form-control" value="1" min="1" max="20">
                    </div>
                    <button type="submit" name="addToCart" class="btn add-to-cart">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <?php

        // footer
        footer();

        // general scripts
        body_scripts();

        $qty = $_POST['qty'];

        if(isset($_POST['addToCart'])) {

            mysqli_query($connection, "INSERT INTO `cart` (`id`, `product_name`, `quantity`, `product_price`, `product_img`) VALUES (NULL, '$name', '$qty', '$price', '$image')"); ?>
            <script>
                alert("Added to cart!");

                var type = <?= $type ?>;
                var id = <?= $id ?>;

                window.location = "indiv-product.php?type=" + type + "&id=" + id;
            </script><?php
        }
    
    ?>

</body>
</html>