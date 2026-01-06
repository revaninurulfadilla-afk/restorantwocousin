<?php
require_once '../config.php';
session_start();

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID pesanan tidak valid.");

$sql = "SELECT ps.id_pesanan, ps.tanggal_pesan, ps.total_harga,
               mj.nomor_meja,
               lg.nama
        FROM pesanan ps
        JOIN meja mj ON mj.id_meja = ps.id_meja
        JOIN login lg ON lg.id_user = ps.id_customer
        WHERE ps.id_pesanan = ?
        LIMIT 1";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

function rupiah($x){
    return "Rp".number_format($x,0,',','.');
}

if (!$data) die("Pesanan tidak ditemukan.");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="card p-4 text-center" style="max-width:500px;margin:auto;">
    <h2 class="text-success">✅ Order Berhasil</h2>
    <p><b>ID Pesanan:</b> <?= $data["id_pesanan"]; ?></p>
    <p><b>Customer:</b> <?= htmlspecialchars($data["nama"]); ?></p>
    <p><b>Tanggal:</b> <?= htmlspecialchars($data["tanggal_pesan"]); ?></p>
    <p><b>Meja:</b> <?= htmlspecialchars($data["nomor_meja"]); ?></p>
    <p><b>Total:</b> <?= rupiah($data["total_harga"]); ?></p>

    <a href="../home/home.php#projects" class="btn btn-dark mt-3">⬅ Back to Menu</a>
</div>

</body>
</html>
