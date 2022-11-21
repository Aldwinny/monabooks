<?php
include "general.php";
include "assets/scripts/php/connection.php";
?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts("Edit/Delete Product - Admin", false); ?>
    <link rel="stylesheet" href="assets\stylesheets\admin\general.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets\stylesheets\admin\products.css?v=<?php echo time(); ?>">
    
</head>
<body>

    <a class="btn d-flex align-items-center go-back" href="admin-main.php">
        <ion-icon name="chevron-back"></ion-icon>
        <span>Return to Main Page</span>
    </a>

    <div class="container-fluid">
    
        <h1>Edit/Delete Product</h1>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="fiction-tab" data-bs-toggle="tab" data-bs-target="#fiction" type="button" role="tab" aria-controls="fiction" aria-selected="true">Fiction</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="non_fiction-tab" data-bs-toggle="tab" data-bs-target="#non_fiction" type="button" role="tab" aria-controls="non_fiction" aria-selected="false">Non-Fiction</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="drama-tab" data-bs-toggle="tab" data-bs-target="#drama" type="button" role="tab" aria-controls="drama" aria-selected="false">Drama</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="poetry-tab" data-bs-toggle="tab" data-bs-target="#poetry" type="button" role="tab" aria-controls="poetry" aria-selected="false">Poetry</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="folktale-tab" data-bs-toggle="tab" data-bs-target="#folktale" type="button" role="tab" aria-controls="folktale" aria-selected="false">Folktale</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="historical-tab" data-bs-toggle="tab" data-bs-target="#historical" type="button" role="tab" aria-controls="historical" aria-selected="false">Historical</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="educational-tab" data-bs-toggle="tab" data-bs-target="#educational" type="button" role="tab" aria-controls="educational" aria-selected="false">Educational</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="children_books-tab" data-bs-toggle="tab" data-bs-target="#children_books" type="button" role="tab" aria-controls="children_books" aria-selected="false">Children Books</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="fiction" role="tabpanel" aria-labelledby="fiction-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="display-image">Image</th>
                            <th class="brand">Brand</th>
                            <th class="name">Name</th>
                            <th class="desc">Description</th>
                            <th class="price">Price</th>
                            <th class="controls">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($connection, "SELECT * FROM fiction");
                            while($row = mysqli_fetch_array($result)) {
                                ?><tr>
                                    <td class="align-middle id"><?php echo $row["id"]; ?></td>
                                    <td class="align-middle display-image"><img src="<?php echo $row['product_img']; ?>"></td>
                                    <td class="align-middle name"><?php echo $row["product_name"]; ?></td>
                                    <td class="align-middle desc"><?php echo $row["product_desc"]; ?></td>
                                    <td class="align-middle price"><?php echo $row["product_price"]; ?></td>
                                    <td class="align-middle controls">
                                        <div class="d-flex flex-column btn-group">
                                            <a href="admin-edit.php?type=fiction&id=<?php echo $row['id'];?>">
                                                <button type="button" class="btn submit-btn">Edit</button>
                                            </a>
                                            <form action="admin-delete.php?id=<?php echo $row ['id']; ?>" method="post">
                                                <button type="submit"  name= "remove-fiction" class="btn submit-btn danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="non_fiction" role="tabpanel" aria-labelledby="non_fiction-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="display-image">Image</th>
                            <th class="brand">Brand</th>
                            <th class="name">Name</th>
                            <th class="desc">Description</th>
                            <th class="price">Price</th>
                            <th class="controls">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($connection, "SELECT * FROM non_fiction");
                            while($row = mysqli_fetch_array($result)) {
                                ?><tr>
                                    <td class="align-middle id"><?php echo $row["id"]; ?></td>
                                    <td class="align-middle display-image"><img src="<?php echo $row['product_img']; ?>"></td>
                                    <td class="align-middle name"><?php echo $row["product_name"]; ?></td>
                                    <td class="align-middle desc"><?php echo $row["product_desc"]; ?></td>
                                    <td class="align-middle price"><?php echo $row["product_price"]; ?></td>
                                    <td class="align-middle controls">
                                        <div class="d-flex flex-column btn-group">
                                            <a href="admin-edit.php?type=non_fiction&id=<?php echo $row['id']; ?>">
                                                <button type="button" class="btn submit-btn">Edit</button>
                                            </a>
                                            <form action="admin-delete.php?id=<?php echo $row ["id"]; ?>" method="post">
                                                <button type="submit" name= "remove-kitchen" onclick="return confirm('Are you sure you want to delete this product?');" class="btn submit-btn danger"> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="drama" role="tabpanel" aria-labelledby="drama-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="display-image">Image</th>
                            <th class="brand">Brand</th>
                            <th class="name">Name</th>
                            <th class="desc">Description</th>
                            <th class="price">Price</th>
                            <th class="controls">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($connection, "SELECT * FROM drama");
                            while($row = mysqli_fetch_array($result)) {
                                ?><tr>
                                    <td class="align-middle id"><?php echo $row["id"]; ?></td>
                                    <td class="align-middle display-image"><img src="<?php echo $row['product_img']; ?>"></td>
                                    <td class="align-middle name"><?php echo $row["product_name"]; ?></td>
                                    <td class="align-middle desc"><?php echo $row["product_desc"]; ?></td>
                                    <td class="align-middle price"><?php echo $row["product_price"]; ?></td>
                                    <td class="align-middle controls">
                                        <div class="d-flex flex-column btn-group">
                                            <a href="admin-edit.php?type=drama&id=<?php echo $row['id']; ?>">
                                                <button type="button" class="btn submit-btn">Edit</button>
                                            </a>
                                            <form action="admin-delete.php?id=<?php echo $row ["id"]; ?>" method="post">
                                                <button type="submit" name= "remove-washing" onclick="return confirm('Are you sure you want to delete this product?');" class="btn submit-btn danger"> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="poetry" role="tabpanel" aria-labelledby="poetry-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="display-image">Image</th>
                            <th class="brand">Brand</th>
                            <th class="name">Name</th>
                            <th class="desc">Description</th>
                            <th class="price">Price</th>
                            <th class="controls">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($connection, "SELECT * FROM poetry");
                            while($row = mysqli_fetch_array($result)) {
                                ?><tr>
                                    <td class="align-middle id"><?php echo $row["id"]; ?></td>
                                    <td class="align-middle display-image"><img src="<?php echo $row['product_img']; ?>"></td>
                                    <td class="align-middle name"><?php echo $row["product_name"]; ?></td>
                                    <td class="align-middle desc"><?php echo $row["product_desc"]; ?></td>
                                    <td class="align-middle price"><?php echo $row["product_price"]; ?></td>
                                    <td class="align-middle controls">
                                        <div class="d-flex flex-column btn-group">
                                            <a href="admin-edit.php?type=poetry&id=<?php echo $row['id']; ?>">
                                                <button type="button" class="btn submit-btn">Edit</button>
                                            </a>
                                            <form action="admin-delete.php?id=<?php echo $row ["id"]; ?>" method="post">
                                                <button type="submit" name= "remove-aircon" onclick="return confirm('Are you sure you want to delete this product?');" class="btn submit-btn danger"> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="folktale" role="tabpanel" aria-labelledby="folktale-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="display-image">Image</th>
                            <th class="brand">Brand</th>
                            <th class="name">Name</th>
                            <th class="desc">Description</th>
                            <th class="price">Price</th>
                            <th class="controls">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($connection, "SELECT * FROM folktale");
                            while($row = mysqli_fetch_array($result)) {
                                ?><tr>
                                    <td class="align-middle id"><?php echo $row["id"]; ?></td>
                                    <td class="align-middle display-image"><img src="<?php echo $row['product_img']; ?>"></td>
                                    <td class="align-middle name"><?php echo $row["product_name"]; ?></td>
                                    <td class="align-middle desc"><?php echo $row["product_desc"]; ?></td>
                                    <td class="align-middle price"><?php echo $row["product_price"]; ?></td>
                                    <td class="align-middle controls">
                                        <div class="d-flex flex-column btn-group">
                                            <a href="admin-edit.php?type=folktale&id=<?php echo $row['id']; ?>">
                                                <button type="button" class="btn submit-btn">Edit</button>
                                            </a>
                                            <form action="admin-delete.php?id=<?php echo $row ["id"]; ?>" method="post">
                                                <button type="submit" name= "remove-small" onclick="return confirm('Are you sure you want to delete this product?');" class="btn submit-btn danger"> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="historical" role="tabpanel" aria-labelledby="historical-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="display-image">Image</th>
                            <th class="brand">Brand</th>
                            <th class="name">Name</th>
                            <th class="desc">Description</th>
                            <th class="price">Price</th>
                            <th class="controls">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($connection, "SELECT * FROM historical");
                            while($row = mysqli_fetch_array($result)) {
                                ?><tr>
                                    <td class="align-middle id"><?php echo $row["id"]; ?></td>
                                    <td class="align-middle display-image"><img src="<?php echo $row['product_img']; ?>"></td>
                                    <td class="align-middle name"><?php echo $row["product_name"]; ?></td>
                                    <td class="align-middle desc"><?php echo $row["product_desc"]; ?></td>
                                    <td class="align-middle price"><?php echo $row["product_price"]; ?></td>
                                    <td class="align-middle controls">
                                        <div class="d-flex flex-column btn-group">
                                            <a href="admin-edit.php?type=historical&id=<?php echo $row['id']; ?>">
                                                <button type="button" class="btn submit-btn">Edit</button>
                                            </a>
                                            <form action="admin-delete.php?id=<?php echo $row ["id"]; ?>" method="post">
                                                <button type="submit" name= "remove-aircon" onclick="return confirm('Are you sure you want to delete this product?');" class="btn submit-btn danger"> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="educational" role="tabpanel" aria-labelledby="educational-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="display-image">Image</th>
                            <th class="brand">Brand</th>
                            <th class="name">Name</th>
                            <th class="desc">Description</th>
                            <th class="price">Price</th>
                            <th class="controls">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($connection, "SELECT * FROM educational");
                            while($row = mysqli_fetch_array($result)) {
                                ?><tr>
                                    <td class="align-middle id"><?php echo $row["id"]; ?></td>
                                    <td class="align-middle display-image"><img src="<?php echo $row['product_img']; ?>"></td>
                                    <td class="align-middle name"><?php echo $row["product_name"]; ?></td>
                                    <td class="align-middle desc"><?php echo $row["product_desc"]; ?></td>
                                    <td class="align-middle price"><?php echo $row["product_price"]; ?></td>
                                    <td class="align-middle controls">
                                        <div class="d-flex flex-column btn-group">
                                            <a href="admin-edit.php?type=educational&id=<?php echo $row['id']; ?>">
                                                <button type="button" class="btn submit-btn">Edit</button>
                                            </a>
                                            <form action="admin-delete.php?id=<?php echo $row ["id"]; ?>" method="post">
                                                <button type="submit" name= "remove-aircon" onclick="return confirm('Are you sure you want to delete this product?');" class="btn submit-btn danger"> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="children_books" role="tabpanel" aria-labelledby="children_books-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="display-image">Image</th>
                            <th class="brand">Brand</th>
                            <th class="name">Name</th>
                            <th class="desc">Description</th>
                            <th class="price">Price</th>
                            <th class="controls">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($connection, "SELECT * FROM children_books");
                            while($row = mysqli_fetch_array($result)) {
                                ?><tr>
                                    <td class="align-middle id"><?php echo $row["id"]; ?></td>
                                    <td class="align-middle display-image"><img src="<?php echo $row['product_img']; ?>"></td>
                                    <td class="align-middle name"><?php echo $row["product_name"]; ?></td>
                                    <td class="align-middle desc"><?php echo $row["product_desc"]; ?></td>
                                    <td class="align-middle price"><?php echo $row["product_price"]; ?></td>
                                    <td class="align-middle controls">
                                        <div class="d-flex flex-column btn-group">
                                            <a href="admin-edit.php?type=children_books&id=<?php echo $row['id']; ?>">
                                                <button type="button" class="btn submit-btn">Edit</button>
                                            </a>
                                            <form action="admin-delete.php?id=<?php echo $row ["id"]; ?>" method="post">
                                                <button type="submit" name= "remove-aircon" onclick="return confirm('Are you sure you want to delete this product?');" class="btn submit-btn danger"> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        
    </div>
      
    <!-- scripts -->
    <?php

        // general scripts
        body_scripts();
    
    ?>

</body>
</html>