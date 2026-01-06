<?php
session_start();
require_once "../config.php";

$cart = $_SESSION["cart"] ?? [];

// fungsi rupiah
function rupiah($x){
    return "Rp".number_format($x,0,',','.');
}

// ambil stok setiap item dari DB
$stokMenu = [];
if(!empty($cart)){
    $ids = implode(",", array_keys($cart));
    $sql = "SELECT m.id_menu, IFNULL(s.jumlah_stok,0) AS stok
            FROM menu m
            LEFT JOIN stok s ON m.id_menu = s.id_menu
            WHERE m.id_menu IN ($ids)";
    $res = mysqli_query($link, $sql);
    while($row = mysqli_fetch_assoc($res)){
        $stokMenu[$row["id_menu"]] = (int)$row["stok"];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .qty-box {
            display:flex;
            align-items:center;
            justify-content:center;
            gap:10px;
        }
        .qty-box a {
            width:35px;
            height:35px;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:18px;
            font-weight:bold;
            border-radius:6px;
            text-decoration:none;
            color:white;
        }
        .btn-minus { background:#dc3545; }
        .btn-plus { background:#28a745; }
        .btn-disabled {
            background:gray !important;
            pointer-events:none;
            opacity:0.5;
        }
        .stok-text {
            font-size:12px;
            color:#555;
            text-align:center;
        }
    </style>
</head>

<body class="p-5">

<h2>🛒 Keranjang Belanja</h2>

<?php if(empty($cart)): ?>
    <p>Keranjang masih kosong.</p>
    <a href="../home/home.php#projects" class="btn btn-primary">⬅ Kembali ke Menu</a>

<?php else: ?>

<table class="table table-bordered mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Menu</th>
            <th>Harga</th>
            <th style="width:200px;">Qty</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $grandTotal = 0; ?>

        <?php foreach($cart as $id => $item): ?>
            <?php 
                $stok = $stokMenu[$id] ?? 0;
                $total = $item["harga"] * $item["qty"];
                $grandTotal += $total;

                $disablePlus = ($item["qty"] >= $stok || $stok <= 0);
            ?>
            <tr>
                <td><?= htmlspecialchars($item["nama_menu"]) ?></td>
                <td><?= rupiah($item["harga"]) ?></td>

                <td>
                    <div class="qty-box">
                        <a href="update_cart.php?id=<?= $id ?>&action=minus" class="btn-minus">-</a>

                        <b><?= $item["qty"] ?></b>

                        <a href="update_cart.php?id=<?= $id ?>&action=plus"
                           class="btn-plus <?= $disablePlus ? 'btn-disabled':'' ?>">
                           +
                        </a>
                    </div>
                    <div class="stok-text">Stok: <?= $stok ?></div>
                </td>

                <td><?= rupiah($total) ?></td>
                <td>
                    <a href="remove_cart.php?id=<?= $id ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus item ini dari cart?')">
                       Hapus
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><b>Grand Total</b></td>
            <td colspan="2"><b><?= rupiah($grandTotal) ?></b></td>
        </tr>
    </tbody>
</table>

<a href="../home/home.php#projects" class="btn btn-secondary">⬅ Tambah Menu</a>
<a href="checkout.php" class="btn btn-success">✅ Checkout</a>

<?php endif; ?>

</body>
</html>
