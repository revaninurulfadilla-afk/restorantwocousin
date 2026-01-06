<?php
session_start();
require_once "../config.php";

if (!isset($_GET["id"])) { header("Location: ../panel/menu-panel.php"); exit; }
$id_menu = (int)$_GET["id"];

// data menu
$sql = "SELECT * FROM menu WHERE id_menu = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_menu);
mysqli_stmt_execute($stmt);
$menu = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if (!$menu) { die("Menu tidak ditemukan!"); }

// data stok
$stok = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM stok WHERE id_menu = $id_menu LIMIT 1"));
if (!$stok) { $stok = ["jumlah_stok"=>0, "stok_minimum"=>0, "satuan"=>"porsi"]; }

// kategori
$kategori = mysqli_query($link, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Update Menu</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body{background:#0e0e0e;color:#fff;padding:40px}
    .card{max-width:700px;margin:auto;background:rgba(255,255,255,.05);border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.3)}
  </style>
</head>
<body>
<div class="card p-4">
  <h3>Update Menu</h3>
  <form action="updateItem.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>">
    <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($menu['foto']) ?>">

    <div class="form-group">
      <label>Nama Menu</label>
      <input type="text" name="nama_menu" class="form-control" value="<?= htmlspecialchars($menu['nama_menu']) ?>" required>
    </div>

    <div class="form-group">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" min="0" step="100" value="<?= (float)$menu['harga'] ?>" required>
    </div>

    <div class="form-group">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($menu['deskripsi']) ?></textarea>
    </div>

    <div class="form-group">
      <label>Kategori</label>
      <select name="id_kategori" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while($k = mysqli_fetch_assoc($kategori)): ?>
          <option value="<?= $k['id_kategori'] ?>" <?= ($menu['id_kategori']==$k['id_kategori']?'selected':'') ?>>
            <?= htmlspecialchars($k['nama_kategori']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Foto</label>
      <input type="file" name="foto" class="form-control-file" accept="image/*">
      <small class="text-muted">Kosongkan jika tidak mengubah foto. Saat ini: <b><?= htmlspecialchars($menu['foto']) ?></b></small>
    </div>

    <hr class="bg-secondary">

    <h5>Stok</h5>
    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Jumlah Stok</label>
        <input type="number" name="jumlah_stok" class="form-control" min="0" value="<?= (int)$stok['jumlah_stok'] ?>" required>
      </div>
      <div class="form-group col-md-4">
        <label>Stok Minimum</label>
        <input type="number" name="stok_minimum" class="form-control" min="0" value="<?= (int)$stok['stok_minimum'] ?>" required>
      </div>
      <div class="form-group col-md-4">
        <label>Satuan</label>
        <input type="text" name="satuan" class="form-control" maxlength="20" value="<?= htmlspecialchars($stok['satuan']) ?>" required>
      </div>
    </div>

    <button class="btn btn-light">Update</button>
    <a href="../panel/menu-panel.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
