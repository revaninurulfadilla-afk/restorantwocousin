<?php
session_start();
require_once "../config.php";
include "../inc/dashHeader.php";

if (!isset($_GET['id'])) {
    die("ID pesanan tidak ditemukan!");
}
$id_pesanan = (int)$_GET['id'];

$q = mysqli_query($link, "
    SELECT p.*, m.nomor_meja
    FROM pesanan p
    LEFT JOIN meja m ON p.id_meja = m.id_meja
    WHERE p.id_pesanan='$id_pesanan'
");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Pesanan tidak ditemukan!");
}

$id_meja_lama = $data['id_meja'];

$qMeja = mysqli_query($link, "
    SELECT * FROM meja 
    WHERE status='kosong' OR id_meja='$id_meja_lama'
    ORDER BY nomor_meja ASC
");

$tgl = date("Y-m-d", strtotime($data['tanggal_pesan']));
$jam = date("H:i", strtotime($data['tanggal_pesan']));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="background:#f5f5f5;">
<div class="container mt-5" style="max-width:500px;">
    <div class="card p-4 shadow">
        <h3 class="text-center">Update Reservation</h3>
        <p class="text-center">ID Pesanan #<?= $id_pesanan ?></p>

        <form method="POST" action="processUpdateReservation.php">

            <input type="hidden" name="id_pesanan" value="<?= $id_pesanan ?>">
            <input type="hidden" name="id_meja_lama" value="<?= $data['id_meja'] ?>">

            <div class="form-group">
                <label>Pilih Meja</label>
                <select name="id_meja" class="form-control" required>
                    <option value="">-- Pilih Meja --</option>
                    <?php while($m = mysqli_fetch_assoc($qMeja)): ?>
                        <option value="<?= $m['id_meja'] ?>" 
                            <?= ($m['id_meja']==$data['id_meja']) ? "selected" : "" ?>>
                            Meja <?= $m['nomor_meja'] ?> (<?= $m['status'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required value="<?= $tgl ?>">
            </div>

            <div class="form-group">
                <label>Jam</label>
                <input type="time" name="jam" class="form-control" required value="<?= $jam ?>">
            </div>

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="3"><?= htmlspecialchars($data['catatan']); ?></textarea>
            </div>

            <button class="btn btn-success btn-block">Simpan Perubahan</button>
            <a href="../panel/reservation-panel.php" class="btn btn-secondary btn-block">Batal</a>
        </form>
    </div>
</div>
</body>
</html>

<?php include "../inc/dashFooter.php"; ?>
