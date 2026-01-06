<?php
require_once "../config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: orderItem.php");
    exit;
}

$id_pesanan = (int)($_POST['id_pesanan'] ?? 0);
$id_menu    = (int)($_POST['id_menu'] ?? 0);
$qty        = (int)($_POST['qty'] ?? 1);

if ($id_pesanan <= 0 || $id_menu <= 0 || $qty <= 0) {
    die("Data tidak valid!");
}

$qMenu = mysqli_query($link, "SELECT harga FROM menu WHERE id_menu='$id_menu'");
if (!$qMenu || mysqli_num_rows($qMenu) == 0) {
    die("Menu tidak ditemukan!");
}

$menuData = mysqli_fetch_assoc($qMenu);
$harga_satuan = (float)$menuData['harga'];
$subtotal = $harga_satuan * $qty;

$qCheck = mysqli_query($link, "SELECT * FROM detail_pesanan WHERE id_pesanan='$id_pesanan' AND id_menu='$id_menu'");
if (mysqli_num_rows($qCheck) > 0) {
    $row = mysqli_fetch_assoc($qCheck);
    $newQty = $row['qty'] + $qty;
    $newSubtotal = $newQty * $harga_satuan;

    mysqli_query($link, "
        UPDATE detail_pesanan 
        SET qty='$newQty', subtotal='$newSubtotal'
        WHERE id_pesanan='$id_pesanan' AND id_menu='$id_menu'
    ");
} else {
    mysqli_query($link, "
        INSERT INTO detail_pesanan (id_pesanan, id_menu, qty, harga_satuan, subtotal)
        VALUES ('$id_pesanan','$id_menu','$qty','$harga_satuan','$subtotal')
    ");
}

$qTotal = mysqli_query($link, "SELECT SUM(subtotal) AS total FROM detail_pesanan WHERE id_pesanan='$id_pesanan'");
$totalData = mysqli_fetch_assoc($qTotal);
$total_harga = $totalData['total'] ?? 0;

mysqli_query($link, "UPDATE pesanan SET total_harga='$total_harga' WHERE id_pesanan='$id_pesanan'");

header("Location: orderItem.php?id_pesanan=$id_pesanan");
exit;
?>
