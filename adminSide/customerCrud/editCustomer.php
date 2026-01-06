<?php
require_once "../config.php";

if (!isset($_GET["id"])) {
    header("Location: ../panel/customer-panel.php");
    exit;
}

$id_user = (int) $_GET["id"];
$sql = "SELECT id_user, nama, email, username, no_hp, status 
        FROM login 
        WHERE id_user = ? AND role='customer'";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$customer = mysqli_fetch_assoc($result);

if (!$customer) {
    echo "Customer tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="background:#f5f5f5;">
<div class="container mt-5">
    <div class="card p-4">
        <h2>Edit Customer</h2>

        <form action="updateCustomer.php" method="POST">
            <input type="hidden" name="id_user" value="<?= $customer["id_user"]; ?>">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control"
                       value="<?= htmlspecialchars($customer["nama"]); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($customer["email"]); ?>" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control"
                       value="<?= htmlspecialchars($customer["username"]); ?>" required>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control"
                       value="<?= htmlspecialchars($customer["no_hp"]); ?>" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="aktif" <?= ($customer["status"]=="aktif") ? "selected" : ""; ?>>Aktif</option>
                    <option value="nonaktif" <?= ($customer["status"]=="nonaktif") ? "selected" : ""; ?>>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-dark btn-block">Update</button>
            <a href="../panel/customer-panel.php" class="btn btn-secondary btn-block">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
