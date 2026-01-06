<?php
session_start();
require_once "../config.php";
require_once "../posBackend/checkIfLoggedIn.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die("ID tidak valid.");

$sql = "SELECT id_user, nama, username, role, no_hp, status
        FROM login
        WHERE id_user = ? AND role IN ('admin','kasir')
        LIMIT 1";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$data) die("Staff tidak ditemukan.");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Staff</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="container" style="max-width:600px;">
  <h3>Edit Staff</h3>
  <form action="update_staff.php" method="post">
    <input type="hidden" name="id_user" value="<?= (int)$data['id_user']; ?>">

    <div class="form-group">
      <label>Nama</label>
      <input class="form-control" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
    </div>

    <div class="form-group">
      <label>Username</label>
      <input class="form-control" name="username" value="<?= htmlspecialchars($data['username']); ?>" required>
    </div>

    <div class="form-group">
      <label>No HP</label>
      <input class="form-control" name="no_hp" value="<?= htmlspecialchars($data['no_hp'] ?? ''); ?>">
    </div>

    <div class="form-group">
      <label>Role</label>
      <select class="form-control" name="role" required>
        <option value="admin" <?= $data['role']=='admin'?'selected':''; ?>>admin</option>
        <option value="kasir" <?= $data['role']=='kasir'?'selected':''; ?>>kasir</option>
      </select>
    </div>

    <div class="form-group">
      <label>Status</label>
      <select class="form-control" name="status" required>
        <option value="aktif" <?= $data['status']=='aktif'?'selected':''; ?>>aktif</option>
        <option value="nonaktif" <?= $data['status']=='nonaktif'?'selected':''; ?>>nonaktif</option>
      </select>
    </div>

    <div class="form-group">
      <label>Password Baru (opsional)</label>
      <input type="password" class="form-control" name="password_new" placeholder="Kosongkan kalau tidak diubah">
    </div>

    <button class="btn btn-primary" type="submit">Update</button>
    <a class="btn btn-secondary" href="../panel/staff-panel.php">Cancel</a>
  </form>
</div>

</body>
</html>
