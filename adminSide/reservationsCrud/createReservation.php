<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
include '../inc/dashHeader.php';
require_once "../config.php";

$meja = mysqli_query($link, "SELECT * FROM meja ORDER BY nomor_meja ASC");

?>

<style>
.wrapper {
    width: 600px;
    margin-left: 250px;
    padding-top: 80px;
}
</style>

<div class="wrapper">
    <h2>Add Reservation</h2>
    <p>Isi data reservasi dengan benar.</p>

    <form action="insertReservation.php" method="POST">

        <div class="form-group">
            <label>Nama Customer</label>
            <input type="text" name="nama_customer" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Pilih Meja</label>
            <select name="id_meja" class="form-control" required>
                <option value="">-- Pilih Meja --</option>
                <?php while ($m = mysqli_fetch_assoc($meja)): ?>
                    <option value="<?= $m['id_meja']; ?>">
                        Meja <?= $m['nomor_meja']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal Reservasi</label>
            <input type="date" name="reservation_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Jam Reservasi</label>
            <input type="time" name="reservation_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Catatan</label>
            <textarea name="special_request" class="form-control" rows="3"></textarea>
        </div>

        <button class="btn btn-dark btn-block">Simpan Reservasi</button>
        <a href="../panel/reservation-panel.php" class="btn btn-light btn-block">Kembali</a>

    </form>
</div>

<?php include '../inc/dashFooter.php'; ?>
