<?php
session_start();
require_once '../config.php';

// proteksi harus login customer
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../customerLogin/login.php");
    exit;
}

$id_customer = (int)($_SESSION["id_user"] ?? 0);
$cart = $_SESSION["cart"] ?? [];

if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

// Ambil meja kosong
$sqlMeja = "SELECT id_meja, nomor_meja, kapasitas 
            FROM meja 
            WHERE status = 'kosong'
            ORDER BY nomor_meja ASC";

$resultMeja = mysqli_query($link, $sqlMeja);
$mejaList = mysqli_fetch_all($resultMeja, MYSQLI_ASSOC);

// function rupiah
function rupiah($x) {
    return "Rp" . number_format($x,0,',','.');
}

// total cart
$total = 0;
foreach ($cart as $c) {
    $total += $c["harga"] * $c["qty"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="p-4">

<h2>Checkout</h2>

<div class="card p-3 mb-3">
    <h5>Ringkasan Pesanan</h5>
    <ul>
        <?php foreach($cart as $c): ?>
            <li><?= htmlspecialchars($c["nama_menu"]); ?> (<?= $c["qty"]; ?>) - <?= rupiah($c["harga"]*$c["qty"]); ?></li>
        <?php endforeach; ?>
    </ul>
    <h4>Total: <?= rupiah($total); ?></h4>
</div>

<form action="process_checkout.php" method="POST">

    <div class="form-group">
        <label>Pilih Meja</label>
        <select name="id_meja" class="form-control" required>
            <option value="">-- Pilih Meja Kosong --</option>
            <?php foreach($mejaList as $m): ?>
                <option value="<?= $m["id_meja"]; ?>">
                    <?= $m["nomor_meja"]; ?> (Kapasitas <?= $m["kapasitas"]; ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Catatan Pesanan (opsional)</label>
        <textarea name="catatan" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-success">✅ Buat Pesanan</button>
    <a href="cart.php" class="btn btn-secondary">⬅ Kembali</a>

</form>

</body>
</html>
