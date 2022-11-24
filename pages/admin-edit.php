<?php

include "general.php";
include "assets/scripts/php/connection.php";

$type = $_GET["type"];
$id = $_GET["id"];

if($type == "fiction") {
    $dbtable = "fiction";
    $ptype = "Fiction";
} 
else if($type == "non_fiction") {
    $dbtable = "non_fiction";
    $ptype = "Fiction";
} 
else if($type == "drama") {
    $dbtable = "drama";
    $ptype = "Drama";
} 
else if($type == "poetry") {
    $dbtable = "poetry";
    $ptype = "Poetry";
} 
else if($type == "folktale") {
    $dbtable = "folktale";
    $ptype = "Folktale";
}
else if($type == "historical") {
    $dbtable = "historical";
    $ptype = "Historical ";
} 
else if($type == "educational") {
    $dbtable = "educational";
    $ptype = "Educational";
} 
else if($type == "children_books") {
    $dbtable = "children_books";
    $ptype = "Children Books";
}


$res = mysqli_query($connection, "select * from $dbtable where id=$id");
while($row = mysqli_fetch_array($res))
{
	$image = $row["product_img"];
    $brand = $row["brand"];
    $name = $row["product_name"];
    $desc = $row["product_desc"];
    $price = $row["product_price"];
}


?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts("Edit Product - Admin", false); ?>
    <link rel="stylesheet" href="assets\stylesheets\admin\general.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets\stylesheets\admin\edit.css?v=<?php echo time(); ?>">

    
</head>
<body>

    <a class="btn d-flex align-items-center go-back" href="admin-products.php">
        <ion-icon name="chevron-back"></ion-icon>
        <span>Return</span>
    </a>

    <div class="container-fluid">
    
        <h1>Edit Product</h1>

        <form name="remove" method="post" enctype="multipart/form-data">
            <div class="form-group left-text">
                <div class="img-wrapper">
                    <img src="<?php echo $image; ?>">
                </div>
                <div class="input-group">
                    <label for="type" class="col-form-label col-2">Product Image</label>
                    <div class="col">
                        <input type="file" id= "file" name="productimg" class="form-control" accept="image/*" input-field>
                    </div>
                </div>
                <div class="input-group">
                    <label for="type" class="col-form-label col-2 left-text">Product Type</label>
                    <div class="col">
                        <select name="type" class="form-select" required input-field>
                            <option value="fiction">Fiction</option>
                            <option value="non_fiction">Air Conditioner</option>
                            <option value="drama">Drama</option>
                            <option value="poetry">Poetry</option>
                            <option value="folktale">Folktale</option>
                            <option value="historical">Historical</option>
                            <option value="educational">Educational</option>
                            <option value="children_books">Children Books</option>
                        </select>
                    </div>
                </div>
                <div class="input-group">
                    <label for="brand" class="col-form-label col-2">Brand</label>
                    <div class="col">
                        <input type="text" id="brand" name="brand" class="form-control" value="<?php echo $brand; ?>" input-field limited-special>
                    </div>
                </div>
                <div class="input-group">
                    <label for="name" class="col-form-label col-2">Product Name</label>
                    <div class="col">
                        <input type="text" id="productname" name="productname" class="form-control" value="<?php echo $name; ?>" input-field limited-special>
                    </div>
                </div>
                <div class="input-group">
                    <label for="desc" class="col-form-label col-2">Product Description</label>
                    <div class="col">
                        <textarea type="text" id="desc" name="desc" class="form-control" rows="5" input-field><?php echo $desc; ?></textarea>
                    </div>
                </div>
                <div class="input-group">
                    <label for="price" class="col-form-label col-2">Price</label>
                    <div class="col">
                        <input type="number" id= "price" name="price" class="form-control" value="<?php echo $price; ?>" input-field>
                    </div>
                </div>
            </div>
            <div class="button-group">
                <button type="submit" name="change" class="btn submit-btn" onclick="return confirm('Are you sure you want to save changes?');">Save Changes</button>
                <button type="submit" name="cancel" class="btn submit-btn danger">Cancel</button>
            </div>
        </form>
    </div>
      
    <!-- scripts -->
    
    <?php
        // general scripts
        body_scripts();
        $name = $_POST["productname"];
        $brand = $_POST["brand"];
        $desc = $_POST["desc"];
        $price = $_POST["price"];

        if(isset($_POST["change"])){
            if($type != $_POST["type"]){
                $type = $_POST["type"];
                if($type == "fiction") {
                    $oldTbl= $dbtable;
                    $dbtable2 = "fiction";
                } 
                elseif($type == "gun_skins") {
                    $oldTbl= $dbtable;
                    $dbtable2 = "gun_skins";
                } 
                elseif($type == "printed_maps") {
                    $oldTbl= $dbtable;
                    $dbtable2 = "printed_maps";
                }  
                elseif($type == "non_fiction") {
                    $oldTbl= $dbtable;
                    $dbtable2 = "non_fiction";
                }  
                elseif($type == "riot_merch") {
                    $oldTbl= $dbtable;
                    $dbtable2 = "riot_merch";
                }

                if($_FILES["productimg"]["size"] == 0 && $_FILES["productimg"]["error"] == 0) {
                    $file_name = basename($image);
                    $img_dbname = 'assets/media/images/products/'.$dbtable2.'/'.$file_name;
                    rename($image, $img_dbname);
                } else {
                    $time = md5(time());
                    $file_name = $_FILES["productimg"]["name"];
                    $target_path = "assets/media/images/products/$dbtable2/".$time.$file_name;
                    $img_dbname = "assets/media/images/products/$dbtable2/".$time.$file_name;
                    move_uploaded_file($_FILES["productimg"]["tmp_name"], $target_path);
                }                

                mysqli_query($connection, "INSERT INTO $dbtable2 values(NULL, '$brand', '$name', '$desc', '$img_dbname', '$price')");
                mysqli_query($connection,"delete from $oldTbl where id =$id");
                ?>
                    <script>
                        alert("Changes saved!");
                        window.location="admin-products.php";
                    </script>
                <?php
            }
            else if($type == $_POST["type"]){

                if($_FILES["productimg"]["size"] == 0 && $_FILES["productimg"]["error"] == 0) {
                    $query = "update $dbtable set brand='$brand', product_name='$name', product_desc='$desc', product_price='$price' where id=$id";
                } else {
                    $file_name = basename($image);
                    $img_dbname = 'assets/media/images/products/'.$dbtable.'/'.$file_name;
                    rename($image, $img_dbname);
                    $query = "update $dbtable set brand='$brand', product_name='$name', product_desc='$desc', product_img='$img_dbname', product_price='$price' where id=$id";
                }

                mysqli_query($connection, $query);
                ?>
                    <script>
                        alert("Changes saved!");
                        window.location="admin-products.php";
                    </script>
                <?php
            }

        }


        else if(isset($_POST["cancel"])){
            ?><script>
                if(confirm("Are you sure you want to cancel?")) {
                    window.location = "admin-products.php";
                }
            </script><?php
        }
        
    ?>
    <script>
        $(document).ready(function() {
            var t = "<?= $type; ?>";
            $("select").find("option[value='" + t + "']").attr("selected", "selected");
        });
    </script>
    <script src="assets/scripts/js/form-validation.js?v=<?php echo time(); ?>"></script>
    
</body>
</html>