<?php
    session_start();
    include "general.php";
    include "assets/scripts/php/connection.php";
?>

<html>
<head>
    <?php head_scripts("Contact Us", false); ?>
    <link rel="stylesheet" href="assets/stylesheets/pages/login.css?v=<?php echo time(); ?>">
</head>
<body>

    <?php navigation(); ?>

    <div class="form container-fluid section">
        <h1>Contact us</h1>
        <form class="custom-form" action="" autocomplete="off" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder=" " required input-field limited-special no-number>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder=" " required input-field/>
            </div>   
            <div class="form-group">
                <label for="phone" class="form-label">Contact No.</label>
                <input type="number" name="phone" class="form-control" placeholder=" " required input-field/>
            </div>   
            <div class="form-group">
                <label for="message" class="form-label">Talk to us!</label>
                <textarea name="message" class="form-control" placeholder="What's on your mind?" rows="1" required input-field></textarea>
            </div>
            <input type="submit" value="Send" class="btn main-button" name="send" />
        </form>
    </div>
    

<?php
  footer();
  body_scripts();

  if(isset($_POST["send"])) {

        mysqli_query($connection, "insert into contact values(NULL,'$_POST[name]','$_POST[email]','$_POST[phone]','$_POST[message]')");
  ?>
        <script type='text/javascript'>
          alert('Sent successfully! Thank you for contacting us.\r\nClick OK to go back to the homepage.');
          window.location = 'index.php';
        </script>

    <?php
  }

?>
<script src="assets\scripts\js\general.js?v=<?php echo time(); ?>"></script>
<script src="assets\scripts\js\login.js?v=<?php echo time(); ?>"></script>
<script src="assets\scripts\js\form-validation.js?v=<?php echo time(); ?>"></script>

</body>
</html>
