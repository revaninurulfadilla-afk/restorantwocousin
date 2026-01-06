<?php
require_once "../config.php";

if (!isset($_GET["id"])) {
    header("Location: ../panel/menu-panel.php");
    exit;
}

$id_menu = (int) $_GET["id"];

$sqlFoto = "SELECT foto FROM menu WHERE id_menu = ?";
$stmtFoto = mysqli_prepare($link, $sqlFoto);
mysqli_stmt_bind_param($stmtFoto, "i", $id_menu);
mysqli_stmt_execute($stmtFoto);
$resultFoto = mysqli_stmt_get_result($stmtFoto);
$dataMenu = mysqli_fetch_assoc($resultFoto);

$foto = $dataMenu["foto"] ?? "";

$sql = "DELETE FROM menu WHERE id_menu = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_menu);

if (mysqli_stmt_execute($stmt)) {

    if (!empty($foto)) {
        $uploadDir = __DIR__ . "/../../customerSide/image/";
        $pathFoto = $uploadDir . $foto;

        if (file_exists($pathFoto)) {
            unlink($pathFoto);
        }
    }

    header("Location: ../panel/menu-panel.php?deleted=1");
    exit;
} else {
    echo "Gagal hapus menu!";
}
?>
