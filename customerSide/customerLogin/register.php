<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config.php';
session_start();

$email = $member_name = $username = $password = $phone_number = "";
$email_err = $member_name_err = $username_err = $password_err = $phone_number_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $member_name = trim($_POST["nama"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $phone_number = trim($_POST["phone_number"] ?? "");

    if ($email == "") $email_err = "Email wajib diisi.";
    if ($member_name == "") $member_name_err = "Nama wajib diisi.";
    if ($username == "") $username_err = "Username wajib diisi.";
    if (strlen($password) < 6) $password_err = "Password minimal 6 karakter.";
    if ($phone_number == "" || !is_numeric($phone_number)) $phone_number_err = "No HP wajib angka.";

    // cek username duplicate
    if (empty($username_err)) {
        $check = "SELECT id_user FROM login WHERE username = ?";
        $stmt = mysqli_prepare($link, $check);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $username_err = "Username sudah dipakai.";
        }
        mysqli_stmt_close($stmt);
    }

    if (empty($email_err) && empty($member_name_err) && empty($username_err) && empty($password_err) && empty($phone_number_err)) {

        $sql = "INSERT INTO login (nama, email, username, password, role, no_hp, status, created_at)
        VALUES (?, ?, ?, ?, 'customer', ?, 'aktif', NOW())";

        $stmt = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param($stmt, "sssss",
            $member_name,
            $email,
            $username,
            $password,
            $phone_number
        );


        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php");
            exit;
        } else {
            echo "Gagal register: " . mysqli_error($link);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0; /* Remove default margin */
            background-color:black;
             background-image: url('../image/loginBackground.jpg'); /* Set the background image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            }


        
/* Style for the container within login.php */
.register-container {
  padding: 50px; /* Adjust the padding as needed */
  border-radius: 10px; /* Add rounded corners */
  margin: 100px auto; /* Center the container horizontally */
  max-width: 500px; /* Set a maximum width for the container */
}
        .register_wrapper {
            width: 400px; /* Increase the container width */
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-family: 'Montserrat', serif;
        }

        p {
            font-family: 'Montserrat', serif;
        }

        .form-group {
            margin-bottom: 15px; /* Add space between form elements */
        }

        ::placeholder {
            font-size: 12px; /* Adjust the font size as needed */
        }

        /* Add flip animation class to all Font Awesome icons */
        .fa-flip {
            animation: fa-flip 3s infinite;
        }

        /* Keyframes for the flip animation */
        @keyframes fa-flip {
            0% {
                transform: scale(1) rotateY(0);
            }
            50% {
                transform: scale(1.2) rotateY(180deg);
            }
            100% {
                transform: scale(1) rotateY(360deg);
            }
        }
        
    </style>
</head>
<body>
    <form action="register.php" method="post">

    <div class="register-container">
    <div class="register_wrapper"> 
        <a class="nav-link" href="../home/home.php#hero"> <h1 class="text-center" style="font-family:Copperplate; color:white;"> TWO COUSIN</h1><span class="sr-only"></span></a><br>
       
       <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?php echo htmlspecialchars($email); ?>">
        <span class="text-danger"><?php echo $email_err; ?></span>
    </div>

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" placeholder="Enter Name" value="<?php echo htmlspecialchars($member_name); ?>">
        <span class="text-danger"><?php echo $member_name_err; ?></span>
    </div>

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter Username">
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter Password">
        <span class="text-danger"><?php echo $password_err; ?></span>
    </div>

    <div class="form-group">
        <label>Phone Number</label>
        <input type="text" name="phone_number" class="form-control" placeholder="Enter Phone Number" value="<?php echo htmlspecialchars($phone_number); ?>">
        <span class="text-danger"><?php echo $phone_number_err; ?></span>
    </div>

            <button style="background-color:black;" class="btn btn-dark" type="submit" name="register" value="Register">
        Register
    </button>
           
        </form>

        <p style="margin-top:1em; color:white;">Already have an account? <a href="../customerLogin/login.php" >Proceed to Login</a></p>
    </div>
    </div>
</body>
</html>