<?php
session_start();
require_once '../config.php';

// proteksi login customer
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../customerLogin/login.php");
    exit;
}

$id_customer = (int)($_SESSION["id_user"] ?? 0);
$cart = $_SESSION["cart"] ?? [];

$id_meja = (int)($_POST["id_meja"] ?? 0);
$catatan = trim($_POST["catatan"] ?? "");

if ($id_meja <= 0 || empty($cart)) {
    header("Location: checkout.php");
    exit;
}

// hitung total
$total = 0;
foreach($cart as $c){
    $total += $c["harga"] * $c["qty"];
}

mysqli_begin_transaction($link);

try {

    // 1) insert ke pesanan
    $sqlPesanan = "INSERT INTO pesanan (id_meja, id_customer, tanggal_pesan, status_pesanan, total_harga, catatan)
                   VALUES (?, ?, NOW(), 'pending', ?, ?)";

    $stmt = mysqli_prepare($link, $sqlPesanan);
    mysqli_stmt_bind_param($stmt, "iids", $id_meja, $id_customer, $total, $catatan);
    mysqli_stmt_execute($stmt);
    $id_pesanan = mysqli_insert_id($link);
    mysqli_stmt_close($stmt);

    // 2) insert detail_pesanan
    $sqlDetail = "INSERT INTO detail_pesanan (id_pesanan, id_menu, qty, harga_satuan, subtotal)
                  VALUES (?, ?, ?, ?, ?)";

    $stmtD = mysqli_prepare($link, $sqlDetail);

    foreach($cart as $id_menu => $c){
        $qty = (int)$c["qty"];
        $harga = (float)$c["harga"];
        $subtotal = $qty * $harga;

        mysqli_stmt_bind_param($stmtD, "iiidd", $id_pesanan, $id_menu, $qty, $harga, $subtotal);
        mysqli_stmt_execute($stmtD);
    }

    mysqli_stmt_close($stmtD);

    // 3) update meja jadi terisi
    $sqlUpdateMeja = "UPDATE meja SET status='terisi' WHERE id_meja=?";
    $stmtM = mysqli_prepare($link, $sqlUpdateMeja);
    mysqli_stmt_bind_param($stmtM, "i", $id_meja);
    mysqli_stmt_execute($stmtM);
    mysqli_stmt_close($stmtM);

    // commit
    mysqli_commit($link);

    // kosongkan cart
    unset($_SESSION["cart"]);

    // redirect ke halaman sukses
    header("Location: success_order.php?id=$id_pesanan");
    exit;

} catch (Exception $e) {
    mysqli_rollback($link);
    die("Gagal checkout: " . $e->getMessage());
}
