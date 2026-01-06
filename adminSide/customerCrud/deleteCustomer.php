<?php
require_once "../config.php";
session_start();

if (!isset($_SESSION["logged_account_id"])) {
    header("location: ../StaffLogin/login.php");
    exit;
}

if (isset($_GET["id"])) {

    $id_user = (int)$_GET["id"];
    $sql = "DELETE FROM login WHERE id_user = ? AND role = 'customer'";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_user);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../panel/customer-panel.php");
            exit;
        } else {
            echo "Gagal hapus customer: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($link);
?>
