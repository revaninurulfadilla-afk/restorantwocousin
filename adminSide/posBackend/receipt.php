<?php
session_start();
require_once "../config.php";

if (!isset($_GET["id_pesanan"])) {
    die("ID Pesanan tidak ditemukan.");
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

$id_pesanan = (int)$_GET["id_pesanan"];
$pesanan = mysqli_fetch_assoc(mysqli_query($link, "
    SELECT p.*, m.nomor_meja
    FROM pesanan p
    LEFT JOIN meja m ON p.id_meja = m.id_meja
    WHERE p.id_pesanan = '$id_pesanan'
"));

if (!$pesanan) {
    die("Pesanan tidak ditemukan!");
}

$pembayaran = mysqli_fetch_assoc(mysqli_query($link, "
    SELECT * FROM pembayaran WHERE id_pesanan='$id_pesanan'
"));

$items = mysqli_query($link, "
    SELECT dp.*, mn.nama_menu
    FROM detail_pesanan dp
    JOIN menu mn ON dp.id_menu = mn.id_menu
    WHERE dp.id_pesanan='$id_pesanan'
");

$total = $pesanan['total_harga'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background:#f5f5f5; }
        .receipt-box {
            background:white;
            padding:30px;
            max-width:600px;
            margin:30px auto;
            border-radius:10px;
            box-shadow:0px 2px 10px rgba(0,0,0,0.15);
        }
        .title { text-align:center; font-size:22px; font-weight:bold; }
        .small { font-size:14px; color:#555; }
    </style>
</head>
<body>

<div class="receipt-box">
    <div class="title">TWO COUSIN Restaurant</div>
    <p class="text-center small">Receipt Pesanan #<?= $id_pesanan ?></p>

    <hr>
    <p><b>Tanggal:</b> <?= $pesanan['tanggal_pesan'] ?></p>
    <p><b>Meja:</b> <?= $pesanan['id_meja'] ?></p>

    <?php if($pembayaran): ?>
        <p><b>Metode:</b> <?= strtoupper($pembayaran['metode']) ?></p>
        <p><b>Status:</b> <?= strtoupper($pembayaran['status_bayar']) ?></p>
        <p><b>Bayar:</b> Rp <?= number_format($pembayaran['uang_diterima'],0,',','.') ?></p>
        <p><b>Kembali:</b> Rp <?= number_format($pembayaran['kembalian'],0,',','.') ?></p>
    <?php else: ?>
        <p class="text-danger"><b>Belum Dibayar</b></p>
    <?php endif; ?>

    <hr>
    <h5>Detail Pesanan</h5>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Menu</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($items)): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_menu']) ?></td>
                <td><?= $row['qty'] ?></td>
                <td>Rp <?= number_format($row['harga_satuan'],0,',','.') ?></td>
                <td>Rp <?= number_format($row['subtotal'],0,',','.') ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <hr>
    <h4>Total: <b>Rp <?= number_format($total,0,',','.') ?></b></h4>

    <a href="receipt_pdf.php?id_pesanan=<?= $id_pesanan ?>" class="btn btn-dark btn-block no-print">
    Print Receipt
    </a>
    <a href="orderItem.php" class="btn btn-secondary btn-block no-print">
        Kembali
    </a>

</div>

</body>
</html>
