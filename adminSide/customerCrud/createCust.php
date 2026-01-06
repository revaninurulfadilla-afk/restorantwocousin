<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["logged_account_id"])) {
    header("Location: ../StaffLogin/login.php");
    exit;
}

$nama = $email = $username = $password = $no_hp = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama     = trim($_POST["nama"]);
    $email    = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $no_hp    = trim($_POST["no_hp"]);

    if ($nama == "" || $email == "" || $username == "" || $password == "" || $no_hp == "") {
        $err = "Semua field wajib diisi!";
    } else {

        $check = "SELECT id_user FROM login WHERE username = ? LIMIT 1";
        $stmtCheck = mysqli_prepare($link, $check);
        mysqli_stmt_bind_param($stmtCheck, "s", $username);
        mysqli_stmt_execute($stmtCheck);
        $resCheck = mysqli_stmt_get_result($stmtCheck);

        if (mysqli_fetch_assoc($resCheck)) {
            $err = "Username sudah digunakan!";
        } else {

            $sql = "INSERT INTO login (nama, email, username, password, role, no_hp, status) 
                    VALUES (?, ?, ?, ?, 'customer', ?, 'aktif')";

            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $username, $password, $no_hp);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../panel/customer-panel.php?success=1");
                exit;

            } else {
                $err = "Gagal tambah customer: " . mysqli_error($link);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Add Customer</h2>

        <?php if ($err != ""): ?>
            <div class="alert alert-danger"><?= $err ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" class="form-control" value="<?= htmlspecialchars($password) ?>">
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($no_hp) ?>">
            </div>

            <button type="submit" class="btn btn-dark">Save Customer</button>
            <a href="../panel/account-panel.php" class="btn btn-secondary">Back</a>

        </form>
    </div>
</div>

</body>
</html>
