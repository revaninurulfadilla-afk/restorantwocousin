<?php
require_once "../config.php";
session_start();

if (!isset($_GET['id_pesanan'])) {
    die("ID reservation tidak valid.");
}

$id_pesanan = (int)$_GET['id_pesanan'];
$qPesanan = mysqli_query($link, "SELECT * FROM pesanan WHERE id_pesanan='$id_pesanan'");

if (!$qPesanan || mysqli_num_rows($qPesanan) == 0) {
    die("ID reservation tidak valid.");
}

$data = mysqli_fetch_assoc($qPesanan);
$nomor_meja = "-";
if (!empty($data['id_meja'])) {
    $qMeja = mysqli_query($link, "SELECT nomor_meja FROM meja WHERE id_meja='{$data['id_meja']}'");
    if ($qMeja && mysqli_num_rows($qMeja) > 0) {
        $m = mysqli_fetch_assoc($qMeja);
        $nomor_meja = $m['nomor_meja'];
    }
}

$nama_customer = "-";
if (!empty($data['id_customer'])) {
    $qCust = mysqli_query($link, "SELECT nama FROM login WHERE id_user='{$data['id_customer']}'");
    if ($qCust && mysqli_num_rows($qCust) > 0) {
        $c = mysqli_fetch_assoc($qCust);
        $nama_customer = $c['nama'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reservation Receipt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body { background: #f2f2f2; }
        .receipt-box {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.15);
        }
        .receipt-header { text-align: center; margin-bottom: 20px; }
        .receipt-header h2 { font-weight: bold; }
        .receipt-header small { display: block; font-size: 14px; color: #666; }
        .receipt-details p { margin: 5px 0; font-size: 16px; }
        .receipt-details b { display: inline-block; width: 150px; }
    </style>
</head>

<body>
<div class="container mt-5">
    <div class="receipt-box">

        <div class="receipt-header">
            <h2>Johnny's Restaurant</h2>
            <small>Reservation Receipt</small>
            <hr>
            <h5>Reservation ID: #<?= $data['id_pesanan'] ?></h5>
        </div>

        <div class="receipt-details">
            <p><b>Customer Name</b> : <?= $nama_customer ?></p>
            <p><b>Meja</b> : <?= $nomor_meja ?></p>
            <p><b>Tanggal</b> : <?= $data['tanggal_pesan'] ?></p>
            <p><b>Status</b> : <?= strtoupper($data['status_pesanan']) ?></p>
            <p><b>Catatan</b> : <?= $data['catatan'] ?: '-' ?></p>
        </div>

        <hr>

        <div class="text-center">
            <p style="color:#666;">Terima kasih telah melakukan reservasi!</p>
            <a href="reservePage.php" class="btn btn-secondary btn-block mt-3">Kembali</a>
        </div>

    </div>
</div>
</body>
</html>
