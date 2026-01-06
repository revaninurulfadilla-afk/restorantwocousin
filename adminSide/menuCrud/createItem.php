<?php
session_start();
require_once "../posBackend/checkIfLoggedIn.php";
require_once "../config.php";
include "../inc/dashHeader.php";
?>

<style>
.wrapper { width: 85%; padding-left: 200px; padding-top: 20px; }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5">
        <h2 class="mb-4">Tambah Menu</h2>

        <form action="success_create.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $q = mysqli_query($link, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                    while($k = mysqli_fetch_assoc($q)){
                        echo "<option value='{$k['id_kategori']}'>{$k['nama_kategori']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Foto Menu</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="habis">Habis</option>
                </select>
            </div>

            <hr>
            <h4>Stok Menu</h4>

            <div class="form-group">
                <label>Jumlah Stok</label>
                <input type="number" name="jumlah_stok" class="form-control" value="0" required>
            </div>

            <div class="form-group">
                <label>Stok Minimum</label>
                <input type="number" name="stok_minimum" class="form-control" value="0" required>
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" class="form-control" value="porsi">
            </div>

            <button type="submit" class="btn btn-dark">Simpan Menu</button>
            <a href="../panel/menu-panel.php" class="btn btn-secondary">Kembali</a>

        </form>
    </div>
</div>

<?php include "../inc/dashFooter.php"; ?>
