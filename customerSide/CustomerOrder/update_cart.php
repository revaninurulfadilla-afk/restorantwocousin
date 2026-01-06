<?php
session_start();
require_once "../config.php";

$id = (int)($_GET["id"] ?? 0);
$action = $_GET["action"] ?? "";

if($id <= 0 || !isset($_SESSION["cart"][$id])){
    header("Location: cart.php");
    exit;
}

// ambil stok dari tabel stok
$sql = "SELECT IFNULL(jumlah_stok,0) AS stok 
        FROM stok 
        WHERE id_menu = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

$stok = (int)($row["stok"] ?? 0);

// PLUS
if($action == "plus"){
    if($_SESSION["cart"][$id]["qty"] < $stok){
        $_SESSION["cart"][$id]["qty"] += 1;
    }
}

// MINUS
if($action == "minus"){
    $_SESSION["cart"][$id]["qty"] -= 1;

    if($_SESSION["cart"][$id]["qty"] <= 0){
        unset($_SESSION["cart"][$id]);
    }
}

header("Location: cart.php");
exit;
?>
