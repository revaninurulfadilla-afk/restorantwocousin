<?php
session_start();
require_once "../config.php";
require_once "../posBackend/checkIfLoggedIn.php";

if (($_SESSION["staff_role"] ?? "") !== "admin") {
    header("Location: ../panel/account-panel.php?forbidden=1");
    exit;
}

$nama = $username = $password = $no_hp = $role = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama     = trim($_POST["nama"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $no_hp    = trim($_POST["no_hp"]);
    $role     = trim($_POST["role"]);

    // validasi kosong
    if ($nama == "" || $username == "" || $password == "" || $no_hp == "" || $role == "") {
        $err = "Semua field wajib diisi!";
    } else {

        // cek username sudah dipakai atau belum
        $check = "SELECT id_user FROM login WHERE username = ? LIMIT 1";
        $stmtCheck = mysqli_prepare($link, $check);
        mysqli_stmt_bind_param($stmtCheck, "s", $username);
        mysqli_stmt_execute($stmtCheck);
        $resCheck = mysqli_stmt_get_result($stmtCheck);

        if (mysqli_fetch_assoc($resCheck)) {
            $err = "Username sudah digunakan!";
        } else {

            // ✅ Insert staff baru
            $sql = "INSERT INTO login (nama, username, password, role, no_hp, status, created_at)
                    VALUES (?, ?, ?, ?, ?, 'aktif', NOW())";

            $stmt = mysqli_prepare($link, $sql);

            // kalau mau password aman pakai hash:
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            mysqli_stmt_bind_param($stmt, "sssss", $nama, $username, $passwordHash, $role, $no_hp);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../panel/account-panel.php?success=1");
                exit;
            } else {
                $err = "Gagal tambah staff: " . mysqli_error($link);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Staff</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4" style="max-width:600px;margin:auto;">
        <h2 class="mb-4">Tambah Staff Baru</h2>

        <?php if ($err != ""): ?>
            <div class="alert alert-danger"><?= $err ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Nama Staff</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>">
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

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" <?= ($role=="admin"?"selected":""); ?>>Admin</option>
                    <option value="kasir" <?= ($role=="kasir"?"selected":""); ?>>Kasir</option>
                </select>
            </div>

            <button type="submit" class="btn btn-dark">Simpan Staff</button>
            <a href="../panel/account-panel.php" class="btn btn-secondary">Back</a>

        </form>
    </div>
</div>

</body>
</html>
