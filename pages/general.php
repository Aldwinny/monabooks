<?php
include "assets/scripts/php/connection.php";


function head_scripts($page_title, $isIndexPage) {
    if(!($isIndexPage)) {
        $page_title = $page_title . ' | MONA BOOKS';
    }

    echo
    '<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="icon" href="assets\media\images\logo\logo-tall.png" />
    <title>' . $page_title . '</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="assets/stylesheets/general.css?v='. time() . '">
    <link rel="stylesheet" href="assets/stylesheets/variables.css?v=' . time() . '">';
}

function body_scripts() {
    echo
        '<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="assets\scripts\js\general.js?v=' . time() . '"></script>';
}

function navigation() {

    if((isset($_SESSION['email']))){
        $links = '<li><a class="dropdown-item menu-dropdown-item" href="/profile.php">Profile</a></li>
                  <li><a class="dropdown-item menu-dropdown-item" href="/logout.php">Log out</a></li>';
    } else {
        $links = '<li><a class="dropdown-item menu-dropdown-item" href="/register.php">Sign Up</a></li>
                  <li><a class="dropdown-item menu-dropdown-item" href="/login.php">Log in</a></li>';
    }

    echo
    '<nav class="navbar navbar-expand-lg custom-navbar sticky-top" id="default-nav">
        <div class="container" id="#main-menu">
            <a id="main-logo" class="navbar-brand logo" href="index.php"><img src="assets\media\images\logo\logo1.png?v=<?php echo time(); ?>" class="img-fluid"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown" id="product-dropdown">
                        <a class="nav-link dropdown-toggle products-link" href="#" id="navbarProducts" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Genres
                        </a>
                        <ul class="dropdown-menu products-dropdown dropdown-menu-center" aria-labelledby="navbarProducts">
                            <li><a class="dropdown-item menu-dropdown-item" href="products.php?type=fiction">Fiction</a></li>
                            <li><a class="dropdown-item menu-dropdown-item" href="products.php?type=non_fiction">Non-Fiction</a></li>
                            <li><a class="dropdown-item menu-dropdown-item" href="products.php?type=drama">Drama</a></li>
                            <li><a class="dropdown-item menu-dropdown-item" href="products.php?type=poetry">Poetry</a></li>
                            <li><a class="dropdown-item menu-dropdown-item" href="products.php?type=folktale">Folktale</a></li>
                            <li><a class="dropdown-item menu-dropdown-item" href="products.php?type=historical">Historical</a></li>
                            <li><a class="dropdown-item menu-dropdown-item" href="products.php?type=educational">Educational</a></li>
                            <li><a class="dropdown-item menu-dropdown-item" href="products.php?type=children_books">Children Books</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
                <ul class="navbar-nav icon-links">
                    <li class="nav-item"><a class="nav-link" href="#"><ion-icon name="search-outline"></ion-icon></a></li>
                    <li class="nav-item dropdown" id="user-dropdown">
                        <a class="nav-link dropdown-toggle products-link" href="#" id="navbarUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <ion-icon name="person-outline"></ion-icon>
                        </a>
                        <ul class="dropdown-menu products-dropdown dropdown-menu-center" aria-labelledby="navbarUser">'
                            . $links .
                        '</ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="cart.php"><ion-icon name="cart-outline"></ion-icon></a></li>
                </ul>
            </div>
        </div>
    </nav>';
}

function footer() {
    echo
    '<footer class="page-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="title">LOCK N LOAD</div>
                    <p>Mona Books is all about finding the next great read. With over a hundred thousand titles across multiple genres—you will never run out of stories. Visit one of our bookstores in the Philippines.</p>
                    <div class="socials">Find Us Elsewhere</div>
                    <p>
                        <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                        <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                        <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                    </p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="title">Contact</div>
                    <p>938 Aurora Boulevard, Cubao, Quezon City
                    <br/>MonaBooks@mbstore.com
                    <br/>(+632) 8935-5983
                    <br/>(+632) 8945-2365</p>
                </div>
            </div>
        <div class="footer-copyright text-center">&copy; 2022 Created By Mona Books All Rights Reserved.</div>
    </footer>';
}
?>