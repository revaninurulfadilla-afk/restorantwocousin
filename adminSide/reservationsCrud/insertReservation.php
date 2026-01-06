<?php
session_start();
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_customer = trim($_POST["nama_customer"]);
    $id_meja       = (int)$_POST["id_meja"];
    $tanggal       = $_POST["reservation_date"];
    $jam           = $_POST["reservation_time"];
    $catatan       = trim($_POST["special_request"]);

    if (empty($nama_customer) || $id_meja <= 0 || empty($tanggal) || empty($jam)) {
        die("Data reservation tidak lengkap!");
    }

    $tanggal_pesan = $tanggal . " " . $jam;

    $sql = "INSERT INTO pesanan
            (id_meja, nama_customer, tanggal_pesan, status_pesanan, total_harga, catatan)
            VALUES (?, ?, ?, 'reservasi', 0, ?)";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $id_meja, $nama_customer, $tanggal_pesan, $catatan);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../panel/reservation-panel.php?success=1");
        exit;
    } else {
        echo "Gagal menyimpan reservasi!";
    }
}
?>
