<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || ($_SESSION['role'] ?? '') !== 'customer') {
    header('Location: ../customerLogin/login.php');
    exit;
}

$user_id = $_SESSION['id_user'];

$sql = "SELECT nama, username, no_hp, role, status, created_at
        FROM login
        WHERE id_user = ? AND role = 'customer'";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    echo "Data profil tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Customer</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        .profile-card {
            max-width: 550px;
            margin: 70px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        .profile-header {
            background: #111;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .profile-body {
            background: white;
            padding: 25px;
        }
        .profile-body p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .btn-block {
            margin-top: 12px;
        }
    </style>
</head>

<body>

<div class="card profile-card">
    <div class="profile-header">
        <h3>Profil Customer</h3>
        <p style="margin:0;">Hai, <?= htmlspecialchars($row['nama']); ?> 👋</p>
    </div>

    <div class="profile-body">
        <p><b>Nama:</b> <?= htmlspecialchars($row['nama']); ?></p>
        <p><b>Username:</b> <?= htmlspecialchars($row['username']); ?></p>
        <p><b>No HP:</b> <?= htmlspecialchars($row['no_hp']); ?></p>
        <p><b>Status:</b> <?= htmlspecialchars($row['status']); ?></p>

        <p><b>Tanggal Daftar:</b> 
            <?= date("d M Y, H:i", strtotime($row['created_at'])); ?>
        </p>

        <!-- Tombol -->
        <a href="../home/home.php" class="btn btn-secondary btn-block">⬅ Back to Home</a>
        <a href="../customerLogin/logout.php" class="btn btn-danger btn-block">🚪 Logout</a>
    </div>
</div>

</body>
</html>
