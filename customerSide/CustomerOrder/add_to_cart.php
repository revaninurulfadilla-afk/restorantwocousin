<?php
session_start();
require_once "../config.php";

$id_menu = (int)($_GET['id'] ?? 0);
if ($id_menu <= 0) {
    die("Menu tidak valid.");
}

/* ambil stok dari tabel stok */
$sqlCek = "
    SELECT m.id_menu, m.nama_menu, m.harga, m.foto,
           IFNULL(s.jumlah_stok,0) AS stok
    FROM menu m
    LEFT JOIN stok s ON m.id_menu = s.id_menu
    WHERE m.id_menu = ?
    LIMIT 1
";

$stmt = mysqli_prepare($link, $sqlCek);
mysqli_stmt_bind_param($stmt, "i", $id_menu);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Menu tidak ditemukan.");
}

/* kalau stok habis */
if ((int)$data["stok"] <= 0) {
    die("Menu habis, tidak bisa dipesan!");
}

/*simpan cart di session */
if (!isset($_SESSION["cart"])) $_SESSION["cart"] = [];

if (isset($_SESSION["cart"][$id_menu])) {

    /*cegah qty melebihi stok */
    if ($_SESSION["cart"][$id_menu]["qty"] >= $data["stok"]) {
        die("Stok tidak cukup untuk menambah pesanan.");
    }

    $_SESSION["cart"][$id_menu]["qty"] += 1;
} else {
    $_SESSION["cart"][$id_menu] = [
        "nama_menu" => $data["nama_menu"],
        "harga" => $data["harga"],
        "qty" => 1,
        "foto" => $data["foto"],
        "stok" => $data["stok"] // optional
    ];
}

header("Location: cart.php");
exit;
?>
