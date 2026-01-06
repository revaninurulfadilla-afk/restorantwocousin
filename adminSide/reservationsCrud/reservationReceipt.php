<?php
session_start();
require_once "../posBackend/checkIfLoggedIn.php";
require_once "../config.php";
include "../inc/dashHeader.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id_pesanan'])) {
    die("ID reservation tidak valid.");
}

$id_pesanan = (int)$_GET['id_pesanan'];
$sql = "SELECT p.*, m.nomor_meja
        FROM pesanan p
        LEFT JOIN meja m ON p.id_meja = m.id_meja
        WHERE p.id_pesanan = '$id_pesanan'";

$result = mysqli_query($link, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($link));
}

if (mysqli_num_rows($result) == 0) {
    die("ID reservation tidak valid.");
}

$data = mysqli_fetch_assoc($result);
?>

<style>
.wrapper{
    width: 85%;
    padding-left: 200px;
    padding-top: 30px;
}
@media print {
    .no-print { display:none; }
}
</style>

<div class="wrapper">
    <div class="container">
        <div class="card shadow p-4 mx-auto" style="max-width:650px;">
            <h3 class="text-center">Reservation Receipt</h3>
            <hr>

            <p><b>ID Reservasi:</b> <?= $data['id_pesanan'] ?></p>

            <!-- jika kamu sudah tambah kolom nama_customer -->
            <p><b>Customer:</b> <?= $data['nama_customer'] ?? '-' ?></p>

            <p><b>Meja:</b> <?= $data['nomor_meja'] ?? '-' ?></p>
            <p><b>Tanggal Reservasi:</b> <?= $data['tanggal_pesan'] ?></p>
            <p><b>Status:</b> <?= strtoupper($data['status_pesanan']) ?></p>
            <p><b>Catatan:</b> <?= !empty($data['catatan']) ? $data['catatan'] : '-' ?></p>

            <hr>

            <div class="no-print">
                <a href="reservationReceiptPrint.php?id_pesanan=<?= $data['id_pesanan'] ?>" 
                class="btn btn-dark btn-block">
                Print Receipt
                </a>

                <a href="../panel/reservation-panel.php" class="btn btn-secondary btn-block">Kembali</a>
            </div>
        </div>
    </div>
</div>

<?php include "../inc/dashFooter.php"; ?>
