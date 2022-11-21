<?php
include "general.php";
include "assets/scripts/php/connection.php";
?>

<!DOCTYPE html>
<html>

<head>

    <?php head_scripts("Review Feedback - Admin", false); ?>
    <link rel="stylesheet" href="assets\stylesheets\admin\general.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets\stylesheets\admin\feedback.css?v=<?php echo time(); ?>">
    
</head>
<body>

    <a class="btn d-flex align-items-center go-back" href="admin-main.php">
        <ion-icon name="chevron-back"></ion-icon>
        <span>Return to Main Page</span>
    </a>

    <div class="container-fluid">
    
        <h1>Customer Feedback</h1>

        <table class="table table-striped feedback-list">
            <thead>
                <tr>
                    <th class="id">ID</th>
                    <th class="name">Name</th>
                    <th class="email">Email Address</th>
                    <th class="contact">Contact No.</th>
                    <th class="msg">Message</th>
                    <th class="controls">Controls</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = mysqli_query($connection, "SELECT * FROM contact");
                    while($row = mysqli_fetch_array($result)) {
                        ?><tr>
                            <td class="align-middle id"><?php echo $row["id"]; ?></td>
                            <td class="align-middle name"><?php echo $row['name']; ?></td>
                            <td class="align-middle email"><?php echo $row["email"]; ?></td>
                            <td class="align-middle contact"><?php echo $row["phone"]; ?></td>
                            <td class="align-middle msg"><?php echo $row["message"]; ?></td>
                            <td class="align-middle controls">
                                <div class="d-flex align-items-center justify-content-center">
                                    <form action="admin-delete.php?id=<?php echo $row ["id"]; ?>" method="post">
                                        <button type="submit"  name= "remove-review" class="btn submit-btn danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr><?php
                    }
                ?>
            </tbody>
        </table>
    

        
    </div>
      
    <!-- scripts -->
    <?php

        // general scripts
        body_scripts();
    
    ?>

</body>
</html>