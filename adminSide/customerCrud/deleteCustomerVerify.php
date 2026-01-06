<?php
require_once "../config.php";
session_start();

// pastikan staff login dulu
if (!isset($_SESSION["logged_account_id"])) {
    header("Location: ../StaffLogin/login.php");
    exit;
}

// ambil id customer yang mau dihapus
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../panel/customer-panel.php");
    exit;
}

$table_id = (int)$_GET['id'];

$err = "";

// jika tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $admin_id = (int)($_POST["admin_id"] ?? 0);
    $password = trim($_POST["password"] ?? "");

    // cari admin sesuai input
    $sql = "SELECT id_user, password FROM login WHERE id_user = ? AND role = 'admin' LIMIT 1";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $admin = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($admin) {
        // plain text sesuai sistem kamu sekarang
        if ($password === $admin["password"]) {
            header("Location: ../customerCrud/deleteCustomer.php?id=" . $table_id);
            exit;
        } else {
            $err = "Password salah!";
        }
    } else {
        $err = "Admin ID tidak ditemukan!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Verify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../css/verifyAdmin.css" rel="stylesheet" />
</head>

<body>
    <div class="login-container">
        <div class="login_wrapper">
            <div class="wrapper">
                <h2 style="text-align: center;">Admin Login</h2>
                <h5>Admin Credentials needed to Delete Customer</h5>

                <?php if($err): ?>
                    <div class="alert alert-danger"><?= $err; ?></div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-group">
                        <label>Admin Id</label>
                        <input type="number" name="admin_id" class="form-control" placeholder="Enter Admin ID" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Admin Password" required>
                    </div>

                    <button class="btn btn-light" type="submit">Delete Customer</button>
                    <a class="btn btn-danger" href="../panel/customer-panel.php">Cancel</a>
                </form>

            </div>
        </div>
    </div>
</body>
</html>
