<?php
require_once "../config.php";
session_start();

$username = $new_password = $confirm_password = "";
$username_err = $new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Masukkan username / email.";
    } else {
        $username = trim($_POST["username"]);
    }

    // password baru
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Masukkan password baru.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password minimal 6 karakter.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // konfirmasi password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Konfirmasi password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && $new_password !== $confirm_password) {
            $confirm_password_err = "Password tidak sama.";
        }
    }

    // ✅ kalau validasi aman → cek username di DB
    if (empty($username_err) && empty($new_password_err) && empty($confirm_password_err)) {

        $sqlCheck = "SELECT id_user FROM login WHERE username = ? AND role='customer' LIMIT 1";
        $stmtCheck = mysqli_prepare($link, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "s", $username);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) == 1) {

            mysqli_stmt_bind_result($stmtCheck, $id_user);
            mysqli_stmt_fetch($stmtCheck);

            mysqli_stmt_close($stmtCheck);

            // ✅ update password
            $sqlUpdate = "UPDATE login SET password = ? WHERE id_user = ?";
            $stmtUpdate = mysqli_prepare($link, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "si", $new_password, $id_user);

            if (mysqli_stmt_execute($stmtUpdate)) {
                header("Location: login.php?reset=success");
                exit;
            } else {
                echo "Gagal update password.";
            }

            mysqli_stmt_close($stmtUpdate);
        } else {
            $username_err = "Username tidak ditemukan.";
            mysqli_stmt_close($stmtCheck);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background:#2c3e50;
            font-family: 'Montserrat', sans-serif;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            color:white;
        }
        .wrapper{
            width:400px;
            padding:30px;
            background:#34495e;
            border-radius:12px;
        }
        h2{text-align:center;}
    </style>
</head>
<body>

<div class="wrapper">
    <h2>Reset Password</h2>
    <p style="text-align:center;">Masukkan Username / Email untuk reset password.</p>

    <form action="" method="POST">

        <div class="form-group">
            <label>Username / Email</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>

        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>

        <button type="submit" class="btn btn-light btn-block">Reset Password</button>

        <p style="margin-top:15px;text-align:center;">
            <a href="login.php" style="color:lightblue;">Kembali ke Login</a>
        </p>

    </form>
</div>

</body>
</html>
