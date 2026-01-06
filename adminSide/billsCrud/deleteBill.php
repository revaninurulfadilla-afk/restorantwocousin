<?php
require_once "../config.php";

if (!isset($_GET['id'])) {
    header("Location: ../panel/bill-panel.php");
    exit;
}

$id_pesanan = (int) $_GET['id'];

mysqli_query($link, "SET FOREIGN_KEY_CHECKS=0");
mysqli_query($link, "DELETE FROM detail_pesanan WHERE id_pesanan='$id_pesanan'");
mysqli_query($link, "DELETE FROM pembayaran WHERE id_pesanan='$id_pesanan'");
mysqli_query($link, "DELETE FROM pesanan WHERE id_pesanan='$id_pesanan'");
mysqli_query($link, "SET FOREIGN_KEY_CHECKS=1");

header("Location: ../panel/bill-panel.php?deleted=1");
exit;
?>
