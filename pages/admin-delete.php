<?php
include "assets/scripts/php/connection.php";
$id=$_GET["id"];

if(isset($_POST['remove-fiction'])){
    mysqli_query($connection,"delete from fiction where id =$id");
 
    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-non_fiction'])){
    mysqli_query($connection,"delete from non_fiction where id =$id");

    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-drama'])){
    mysqli_query($connection,"delete from drama where id =$id");
  
    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-poetry'])){
    mysqli_query($connection,"delete from poetry where id =$id");
    
    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-folktale'])){
    mysqli_query($connection,"delete from foktale where id =$id");

    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-historical'])){
    mysqli_query($connection,"delete from historical where id =$id");

    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-education'])){
    mysqli_query($connection,"delete from education where id =$id");

    header("location:admin-products.php"); // redirects to all records page
}
else if(isset($_POST['remove-children_books'])){
    mysqli_query($connection,"delete from children_books where id =$id");

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

    


