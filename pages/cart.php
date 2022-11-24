<?php
session_start();
include "general.php";
include "assets/scripts/php/connection.php";

$email = $_SESSION['email'];

$result = mysqli_query($connection, "SELECT * FROM user_table WHERE email ='$email'");
while($row = mysqli_fetch_array($result)) {
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

    <?php head_scripts("My Cart", false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/cart.css?v=<?php echo time(); ?>">
    
</head>
<body>
    
    <?php navigation(); ?>

    <!-- stuff -->
    <div class="section container-fluid d-flex flex-column align-items-center">
        <h1 class="heading-accent">My Cart</h1>
        <div class="container cart d-flex flex-column justify-content-center flex-wrap">
            <?php
            $result = mysqli_query($connection, "SELECT * FROM cart");
            if(mysqli_num_rows($result) == 0) {
                ?>
                <div class="d-flex flex-column align-items-center">
                    <p style="text-align:center;font-weight:500;font-size:1.3rem;">Huh? There's nothing here.</p>
                    <a href="products.php?type=all">
                        <button class="btn main-button">Continue Shopping</button>
                    </a>
                </div>
                <?php
            } else {
                while($row = mysqli_fetch_array($result)) {
                    $total_product_price = $row['product_price'] * $row['quantity'];
                    $total_price = $total_price + $total_product_price;
                    ?>
                    <div class="card d-flex flex-row flex-wrap">
                        <div class="img-wrapper">
                            <img src="<?php echo $row['product_img']; ?>" class="product-img img-fluid">
                        </div>
                        <div class="info">
                                <h2><?php echo $row['product_name']; ?></h2>
                                <p>Quantity: <?php echo $row['quantity']; ?></p>
                                <div>₱<?php echo $total_product_price; ?></div>
                            </form>
                        </div>
                        <form action="admin-delete.php?id=<?php echo $row ['id']; ?>" method="post" class="btn-wrapper">
                            <button type="submit"  name= "remove-cart" class="btn submit-btn delete d-flex align-items-center" onclick="return confirm('Are you sure you want to delete this product?');"><ion-icon name="remove"></ion-icon></button>
                        </form>
                    </div>
                <?php
                }?>
                <div class="card summary">
                    <h2>Order Summary</h2>
                    <div class="info">
                        <div class="row">
                            <div class="col summary-title">Subtotal</div>
                            <div class="col">₱<?php echo $total_price;?></div>
                        </div>
                        <div class="row">
                            <div class="col summary-title">Delivery Fee</div>
                            <div class="col">₱150</div>
                        </div>
                        <div class="row">
                            <div class="col summary-title">Total</div>
                            <div class="col">₱<?php echo $total_price + 150; ?></div>
                        </div>
                    </div>
                </div>
                <div class="card address">
                    <h2>Delivery Address</h2>
                    <p><?php echo $address; ?></p>
                </div>
                <form action="checkout.php?total=<?php echo $total_price; ?>" method="post">
                    <button type="submit" name="checkout" class="btn main-button d-flex align-items-center" onclick="alert('Are you sure you want to checkout?');">Checkout</button>
                </form>
           <?php
           }
           ?>
            
            
        </div>
    </div>

    <?php

        // footer
        footer();

        // general scripts
        body_scripts();
        
    ?>

</body>
</html>