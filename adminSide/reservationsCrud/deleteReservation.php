<?php
session_start();
require_once "../config.php";
if (!isset($_GET['id'])) {
    die("ID pesanan tidak ditemukan!");
}

$id_pesanan = (int)$_GET['id'];
$qPesanan = mysqli_query($link, "SELECT * FROM pesanan WHERE id_pesanan='$id_pesanan'");
$pesanan = mysqli_fetch_assoc($qPesanan);

if (!$pesanan) {
    die("Pesanan tidak ditemukan!");
}

$id_meja = $pesanan['id_meja'];
mysqli_begin_transaction($link);

try {
    mysqli_query($link, "DELETE FROM pembayaran WHERE id_pesanan='$id_pesanan'");
    mysqli_query($link, "DELETE FROM detail_pesanan WHERE id_pesanan='$id_pesanan'");
    mysqli_query($link, "DELETE FROM pesanan WHERE id_pesanan='$id_pesanan'");
    if (!empty($id_meja)) {
        mysqli_query($link, "UPDATE meja SET status='kosong' WHERE id_meja='$id_meja'");
    }
    mysqli_commit($link);
    header("Location: ../panel/reservation-panel.php");
    exit;

} catch (Exception $e) {
    mysqli_rollback($link);
    echo "Gagal menghapus pesanan/reservasi: " . $e->getMessage();
}
?>
