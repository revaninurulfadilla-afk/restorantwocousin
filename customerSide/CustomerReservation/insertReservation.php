<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../customerLogin/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_customer = (int)$_SESSION["id_user"];
    $id_meja     = (int)($_POST["id_meja"] ?? 0);
    $tanggal     = $_POST["reservation_date"] ?? "";
    $jam         = $_POST["reservation_time"] ?? "";
    $catatan     = trim($_POST["special_request"] ?? "");

    if ($id_meja <= 0 || empty($tanggal) || empty($jam)) {
        die("Data reservation tidak lengkap!");
    }

    $tanggal_pesan = $tanggal . " " . $jam;

    mysqli_begin_transaction($link);

    try {
        $sql = "INSERT INTO pesanan (id_meja, id_customer, tanggal_pesan, status_pesanan, total_harga, catatan)
                VALUES (?, ?, ?, 'reservasi', 0, ?)";

        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "iiss", $id_meja, $id_customer, $tanggal_pesan, $catatan);
        mysqli_stmt_execute($stmt);

        $id_pesanan = mysqli_insert_id($link);
        mysqli_stmt_close($stmt);

        $sql2 = "UPDATE meja SET status = 'reservasi' WHERE id_meja = ?";
        $stmt2 = mysqli_prepare($link, $sql2);
        mysqli_stmt_bind_param($stmt2, "i", $id_meja);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        mysqli_commit($link);
        header("Location: reservationReceipt.php?id_pesanan=" . $id_pesanan);
        exit;

    } catch (Exception $e) {
        mysqli_rollback($link);
        echo "Gagal membuat reservasi: " . $e->getMessage();
    }
}
?>
