<?php

include "general.php";
include "assets/scripts/php/connection.php";


?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts("Add Product - Admin", false); ?>
    <link rel="stylesheet" href="assets\stylesheets\admin\general.css?v=<?php echo time(); ?>">
    
</head>
<body>

    <a class="btn d-flex align-items-center go-back" href="admin-main.php">
        <ion-icon name="chevron-back"></ion-icon>
        <span>Return to Main Page</span>
    </a>

    <div class="container-fluid">
    
        <h1>Add Product</h1>

        <form name="add" method="POST" enctype="multipart/form-data" action="#">
            <div class="form-group">
                <div class="input-group">
                    <label for="type" class="col-form-label col-2 left-text">Product Type</label>
                    <div class="col">
                        <select name="type" class="form-select" required input-field>
                            <option value="fiction" selected>Fiction</option>
                            <option value="non_fiction">Non-Fiction</option>
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
                    <label for="name" class="col-form-label col-2 left-text">Product Name</label>
                    <div class="col">
                        <input type="text" name="name" class="form-control" required input-field limited-special>
                    </div>
                </div>
                <div class="input-group">
                    <label for="desc" class="col-form-label col-2 left-text">Product Description</label>
                    <div class="col">
                        <textarea type="text" name="desc" class="form-control" rows="5" required input-field></textarea>
                    </div>
                </div>
                <div class="input-group">
                    <label for="brand" class="col-form-label col-2 left-text">Price</label>
                    <div class="col">
                        <input type="number" name="price" class="form-control" required input-field>
                    </div>
                </div>
                <div class="input-group">
                    <label for="image" class="col-form-label col-2 left-text">Product Image</label>
                    <div class="col">
                        <input type="file" name="image" class="form-control" accept="image/*" required input-field>
                    </div>
                </div>
            </div>
            <button type="submit" name="submit" class="btn submit-btn">Add Product</button>
        </form>
    </div>
      
    <!-- scripts -->
    
    <?php

        // general scripts
        body_scripts();

        if(isset($_POST["submit"])) {

            $type = $_POST["type"];
            $name = $_POST["name"];
            $desc = $_POST["desc"];
            $price = $_POST["price"];

            if($type == "fiction") {
                $dbtable = "fiction";
            } 
            elseif($type == "non_fiction") {
                $dbtable = "non_fiction";
            } 
            elseif($type == "drama") {
                $dbtable = "drama";
            }  
            elseif($type == "poetry") {
                $dbtable = "poetry";
            }  
            elseif($type == "folktale") {
                $dbtable = "folktale";
            }
            elseif($type == "historical") {
                $dbtable = "historical";
            }
            elseif($type == "educational") {
                $dbtable = "educational";
            }
            elseif($type == "children_books") {
                $dbtable = "children_books";
            }

            $time = md5(time());
            $file_name = $_FILES["image"]["name"];
            $target_path = "assets/media/images/products/$dbtable/".$time.$file_name;
            $img_dbname = "assets/media/images/products/$dbtable/".$time.$file_name;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_path);

            mysqli_query($connection, "INSERT INTO $dbtable values(NULL, '$name', '$desc', '$img_dbname', '$price')");

            ?>
            <script>
                alert("Successfully added!");
                window.location = "admin-add.php";
            </script>
            <?php
        }
    
    ?>
    <script src="assets/scripts/js/form-validation.js?v=<?php echo time(); ?>"></script>
</body>
</html>
