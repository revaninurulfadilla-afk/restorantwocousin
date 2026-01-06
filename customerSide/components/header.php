<?php
require_once '../config.php';
session_start();
// MAIN DISHES
$sqlmainDishes = "
    SELECT m.*, IFNULL(s.jumlah_stok,0) AS stok
    FROM menu m
    JOIN kategori k ON m.id_kategori = k.id_kategori
    LEFT JOIN stok s ON m.id_menu = s.id_menu
    WHERE k.nama_kategori = 'Main Dishes'
    ORDER BY m.nama_menu ASC
";
$resultmainDishes = mysqli_query($link, $sqlmainDishes);
$mainDishes = mysqli_fetch_all($resultmainDishes, MYSQLI_ASSOC);


// SIDE SNACKS
$sqlsides = "
    SELECT m.*, IFNULL(s.jumlah_stok,0) AS stok
    FROM menu m
    JOIN kategori k ON m.id_kategori = k.id_kategori
    LEFT JOIN stok s ON m.id_menu = s.id_menu
    WHERE k.nama_kategori = 'Side Snacks'
    ORDER BY m.nama_menu ASC
";
$resultsides = mysqli_query($link, $sqlsides);
$sides = mysqli_fetch_all($resultsides, MYSQLI_ASSOC);


// DRINKS
$sqldrinks = "
    SELECT m.*, IFNULL(s.jumlah_stok,0) AS stok
    FROM menu m
    JOIN kategori k ON m.id_kategori = k.id_kategori
    LEFT JOIN stok s ON m.id_menu = s.id_menu
    WHERE k.nama_kategori = 'Drinks'
    ORDER BY m.nama_menu ASC
";
$resultdrinks = mysqli_query($link, $sqldrinks);
$drinks = mysqli_fetch_all($resultdrinks, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <title>Home</title>
</head>

<body>
    <!-- Header -->

    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="brand">
                    <a class="nav-link" href="../home/home.php#hero">
                        <img src="/restoran/image/logo.png" alt="logo" style="height:60px;">
                    </a>
                </div>
                <div class="nav-list">
                    <div class="hamburger">
                        <div class="bar"></div>
                    </div>
                    <div class="navbar-container">

                        <div class="navbar">
                            <ul>
<?php
$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
                               <li><a href="../home/home.php#hero" data-after="Home">Home</a></li>

<?php
if (strpos($current_url, "localhost/customerSide/home/home.php") !== false) {
?>
                                <li><a href="#projects" data-after="Projects">Menu</a></li>
                                <li><a href="#about" data-after="About">About</a></li>
                                <li><a href="#contact" data-after="Contact">Contact</a></li>
<?php
} else {
?>
                                <li><a href="../CustomerReservation/reservePage.php"
                                        data-after="Service">Reservation</a></li>
                                <li><a href="../../adminSide/StaffLogin/login.php" data-after="Staff">Staff</a></li>
                                <li><a href="../CustomerOrder/cart.php" data-after="Cart">Cart</a></li>

<?php
}
?>




                                <div class="dropdown">
                                    <button class="dropbtn">ACCOUNT <i class="fa fa-caret-down" aria-hidden="true"></i>
                                    </button>
                                   <div class="dropdown-content">
<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && ($_SESSION["role"] ?? "") === "customer") {
    echo '<a style="display:block; padding:10px 15px; font-size:15px; color:white;" href="../customerLogin/profile.php">Profil</a>';
    echo '<a style="display:block; padding:10px 15px; font-size:15px; color:white;" href="../customerLogin/logout.php">Logout</a>';

} else {
    echo '<a style="display:block; padding:10px 15px; font-size:15px; color:white; text-decoration:none;" 
      href="../customerLogin/register.php">Sign Up</a>';
    echo '<a style="display:block; padding:10px 15px; font-size:15px; color:white; text-decoration:none;" 
      href="../customerLogin/login.php">Log In</a>';

}
?>
</div>

                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header -->