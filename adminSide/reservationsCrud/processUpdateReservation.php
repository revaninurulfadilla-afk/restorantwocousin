<?php
session_start();
require_once "../config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../panel/reservation-panel.php");
    exit;
}

$id_pesanan   = (int)$_POST['id_pesanan'];
$id_meja_lama = (int)$_POST['id_meja_lama'];
$id_meja_baru = (int)$_POST['id_meja'];

$tanggal = $_POST['tanggal'];
$jam     = $_POST['jam'];
$catatan = trim($_POST['catatan']);

$tanggal_pesan = $tanggal . " " . $jam;

mysqli_begin_transaction($link);

try {
    $sql = "UPDATE pesanan 
            SET id_meja=?, tanggal_pesan=?, catatan=? 
            WHERE id_pesanan=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "issi", $id_meja_baru, $tanggal_pesan, $catatan, $id_pesanan);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($id_meja_baru != $id_meja_lama) {
        if ($id_meja_lama > 0) {
            mysqli_query($link, "UPDATE meja SET status='kosong' WHERE id_meja='$id_meja_lama'");
        }
        mysqli_query($link, "UPDATE meja SET status='reservasi' WHERE id_meja='$id_meja_baru'");
    }

    mysqli_commit($link);

    header("Location: ../panel/reservation-panel.php?update=success");
    exit;

} catch (Exception $e) {
    mysqli_rollback($link);
    die("Gagal update reservation: " . $e->getMessage());
}
?>
