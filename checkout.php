<?php
session_start();
include "assets/scripts/php/connection.php";

if(isset($_POST['checkout'])){
    $id=$_GET["id"];
    $total = $_GET["total"];
    $email = $_SESSION['email'];

    if($email != null){
        $res = mysqli_query($connection, "select balance from user_table where email='$email'");
        while($row = mysqli_fetch_array($res)){
            $bal = $row["balance"];
        }
        $newTotal = $total + 150;
        if($bal >= $newTotal){
            $newBal = $bal - $newTotal;
            $query2 = "UPDATE user_table SET balance = '$newBal' WHERE email = '$email'";
            mysqli_query($connection, $query2);
            $query3 = "TRUNCATE TABLE cart";
            mysqli_query($connection, $query3);
            header("location:index.php");
            // echo "<script type='text/javascript'>alert('Order is placed and processing. Thank you for shopping with us!');</script>";
            
        }
        else{
            echo "<script type='text/javascript'>alert('You have insufficient balance!'); window.location.href = 'http://locknload.rf.gd/cart.php'</script>";
        }
    }
    else{
        echo "<script type='text/javascript'>alert('You have to login first!'); window.location.href = 'http://locknload.rf.gd/login.php'</script>";
    }

}
?>