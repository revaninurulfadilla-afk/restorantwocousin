<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["logged_account_id"])) {
    header("Location: ../StaffLogin/login.php");
    exit;
}

if (isset($_GET["id"])) {

    $id_user = (int) $_GET["id"];
    if ($id_user == $_SESSION["logged_account_id"]) {
        die("Tidak boleh menghapus akun sendiri!");
    }
    $sql = "DELETE FROM login 
            WHERE id_user = ? AND role IN ('admin','kasir')";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_user);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../panel/staff-panel.php?deleted=1");
        exit;
    } else {
        echo "Gagal hapus staff: " . mysqli_error($link);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>
