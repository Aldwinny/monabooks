<?php
include "assets/scripts/php/connection.php";
$id=$_GET["id"];

if(isset($_POST['remove-keychain_buddies'])){
    mysqli_query($connection,"delete from keychain_buddies where id =$id");
 
    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-kitchen'])){
    mysqli_query($connection,"delete from agent_clothes where id =$id");

    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-washing'])){
    mysqli_query($connection,"delete from printed_maps where id =$id");
  
    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-aircon'])){
    mysqli_query($connection,"delete from gun_skins where id =$id");
    
    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-small'])){
    mysqli_query($connection,"delete from riot_merch where id =$id");

    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-review'])){
    mysqli_query($connection,"delete from contact where id =$id");
    header("location:admin-review.php"); // redirects to all records page
}
else if(isset($_POST['remove-cart'])){
    mysqli_query($connection,"delete from cart where id =$id");
    header("location:cart.php"); // redirects to all records page
}
?>

    


