<?php
session_start();
require_once "../config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id_pesanan'])) {
    die("ID pesanan tidak ditemukan!");
}
$id_pesanan = (int)$_GET['id_pesanan'];

$qPesanan = mysqli_query($link, "
    SELECT p.*, m.nomor_meja 
    FROM pesanan p
    LEFT JOIN meja m ON p.id_meja = m.id_meja
    WHERE p.id_pesanan='$id_pesanan'
");
$pesanan = mysqli_fetch_assoc($qPesanan);

if (!$pesanan) {
    die("Pesanan tidak ditemukan!");
}

$total = (float)$pesanan['total_harga'];

$qBayar = mysqli_query($link, "SELECT * FROM pembayaran WHERE id_pesanan='$id_pesanan' LIMIT 1");
$pembayaran = mysqli_fetch_assoc($qBayar);

if ($pembayaran && $pembayaran['status_bayar'] == 'lunas') {
    header("Location: receipt.php?id_pesanan=$id_pesanan");
    exit;
}

$qMeja = mysqli_query($link, "
    SELECT * FROM meja 
    WHERE status='kosong' OR id_meja='{$pesanan['id_meja']}'
    ORDER BY nomor_meja ASC
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $metode  = $_POST['metode'];
    $id_meja = (int)($_POST['id_meja'] ?? 0);

    if (empty($pesanan['id_meja']) && $id_meja <= 0) {
        $error = "Harus pilih meja dulu!";
    } else {

        if (empty($pesanan['id_meja']) && $id_meja > 0) {
            mysqli_query($link, "UPDATE pesanan SET id_meja='$id_meja' WHERE id_pesanan='$id_pesanan'");
        } else {
            $id_meja = $pesanan['id_meja'];
        }


        if ($metode === "cash") {
            $uang_diterima = (float)($_POST['uang_diterima'] ?? 0);

            if ($uang_diterima < $total) {
                $error = "Uang diterima kurang!";
            } else {
                $kembalian = $uang_diterima - $total;
            }

        } else {
            $uang_diterima = $total;
            $kembalian = 0;
        }

        if (!isset($error)) {

            mysqli_query($link, "
                INSERT INTO pembayaran
                (id_pesanan, metode, total_bayar, uang_diterima, kembalian, status_bayar)
                VALUES
                ('$id_pesanan','$metode','$total','$uang_diterima','$kembalian','lunas')
            ");

            mysqli_query($link, "
                UPDATE pesanan SET status_pesanan='selesai'
                WHERE id_pesanan='$id_pesanan'
            ");

            mysqli_query($link, "UPDATE meja SET status='terisi' WHERE id_meja='$id_meja'");

            header("Location: receipt.php?id_pesanan=$id_pesanan");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="background:#f5f5f5;">
<div class="container mt-5" style="max-width:450px;">
    <div class="card p-4 shadow">

        <h3 class="text-center">Checkout</h3>
        <p class="text-center">Pesanan #<?= $id_pesanan ?></p>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <h4>Total: <b>Rp <?= number_format($total,0,',','.') ?></b></h4>

        <form method="POST">
            <div class="form-group">
                <label>Pilih Meja</label>
                <select name="id_meja" class="form-control" <?= empty($pesanan['id_meja']) ? "required" : "" ?>>
                    <option value="">Pilih Meja</option>
                    <?php while($m = mysqli_fetch_assoc($qMeja)): ?>
                        <option value="<?= $m['id_meja'] ?>"
                            <?= ($pesanan['id_meja'] == $m['id_meja']) ? "selected" : "" ?>>
                            Meja <?= $m['nomor_meja'] ?> (<?= $m['status'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode" id="metode" class="form-control" required>
                    <option value="">Pilih Pembayaran</option>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                    <option value="debit">Debit</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>
            <div class="form-group" id="cashSection" style="display:none;">
                <label>Uang Diterima</label>
                <input type="number" name="uang_diterima" id="uang_diterima" class="form-control">
            </div>

            <div class="form-group" id="kembalianSection" style="display:none;">
                <label>Kembalian</label>
                <input type="text" id="kembalian" class="form-control" readonly value="Rp 0">
            </div>

            <button class="btn btn-success btn-block">Bayar</button>
            <a href="orderItem.php?id_pesanan=<?= $id_pesanan ?>" class="btn btn-secondary btn-block">Kembali</a>
        </form>
    </div>
</div>

<script>
    const total = <?= $total ?>;

    const metode = document.getElementById("metode");
    const cashSection = document.getElementById("cashSection");
    const kembalianSection = document.getElementById("kembalianSection");

    const uangInput = document.getElementById("uang_diterima");
    const kembalianInput = document.getElementById("kembalian");

    metode.addEventListener("change", function(){
        if(this.value === "cash"){
            cashSection.style.display = "block";
            kembalianSection.style.display = "block";
            uangInput.required = true;
        } else {
            cashSection.style.display = "none";
            kembalianSection.style.display = "none";
            uangInput.required = false;
            uangInput.value = "";
            kembalianInput.value = "Rp 0";
        }
    });

    uangInput.addEventListener("input", function(){
        let uang = parseFloat(this.value) || 0;
        let kembalian = uang - total;

        if(kembalian < 0){
            kembalianInput.value = "Uang kurang";
        } else {
            kembalianInput.value = "Rp " + kembalian.toLocaleString("id-ID");
        }
    });
</script>

</body>
</html>
