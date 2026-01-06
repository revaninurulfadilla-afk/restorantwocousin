<?php
session_start();
require_once "../config.php";
require_once "../posBackend/checkIfLoggedIn.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../panel/staff-panel.php");
    exit;
}

$id_user  = (int)($_POST['id_user'] ?? 0);
$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$no_hp    = trim($_POST['no_hp'] ?? '');
$role     = trim($_POST['role'] ?? '');
$status   = trim($_POST['status'] ?? '');
$passNew  = trim($_POST['password_new'] ?? '');

if ($id_user <= 0 || $nama=='' || $username=='' || !in_array($role,['admin','kasir']) || !in_array($status,['aktif','nonaktif'])) {
    die("Input tidak valid.");
}
$sqlCheck = "SELECT id_user FROM login WHERE username = ? AND id_user <> ? LIMIT 1";
$stmt = mysqli_prepare($link, $sqlCheck);
mysqli_stmt_bind_param($stmt, "si", $username, $id_user);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    die("Username sudah dipakai.");
}
mysqli_stmt_close($stmt);

if ($passNew !== "") {
    $sql = "UPDATE login
            SET nama=?, username=?, no_hp=?, role=?, status=?, password=?
            WHERE id_user=? AND role IN ('admin','kasir')";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $nama, $username, $no_hp, $role, $status, $passNew, $id_user);
} else {
    $sql = "UPDATE login
            SET nama=?, username=?, no_hp=?, role=?, status=?
            WHERE id_user=? AND role IN ('admin','kasir')";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $nama, $username, $no_hp, $role, $status, $id_user);
}

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: ../panel/staff-panel.php");
    exit;
} else {
    $err = mysqli_error($link);
    mysqli_stmt_close($stmt);
    die("Gagal update: " . $err);
}
