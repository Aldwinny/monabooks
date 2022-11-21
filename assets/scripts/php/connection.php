<?php
$host="localhost";
$user="root";
$password="";
$db="monabooks";

$connection = mysqli_connect($host, $user, $password, $db);
mysqli_select_db($connection, $db) or die (mysqli_error($connection));
?>