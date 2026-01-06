<?php
session_start();
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../panel/menu-panel.php");
    exit;
}

$id_menu       = (int)$_POST["id_menu"];
$nama_menu     = trim($_POST["nama_menu"]);
$harga         = (float)$_POST["harga"];
$deskripsi     = trim($_POST["deskripsi"]);
$id_kategori   = (int)$_POST["id_kategori"];

$jumlah_stok   = (int)($_POST["jumlah_stok"] ?? 0);
$stok_minimum  = (int)($_POST["stok_minimum"] ?? 0);
$satuan        = trim($_POST["satuan"] ?? "porsi");

// FOTO
$foto_lama = $_POST["foto_lama"] ?? "";
$foto_baru = $foto_lama;

if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES["foto"]["tmp_name"];
    $name = basename($_FILES["foto"]["name"]);
    $safeName = time() . "_" . preg_replace("/\s+/", "_", $name);
    $uploadDir = __DIR__ . "/../../customerSide/image/";
    $target    = $uploadDir . $safeName;

    if (!is_dir($uploadDir)) { die("Folder upload tidak ditemukan: " . $uploadDir); }

    if (move_uploaded_file($tmp, $target)) {
        $foto_baru = $safeName;
        if (!empty($foto_lama) && file_exists($uploadDir . $foto_lama)) { unlink($uploadDir . $foto_lama); }
    } else {
        die("Upload foto gagal.");
    }
}

// UPDATE MENU
$sqlMenu = "UPDATE menu SET nama_menu=?, harga=?, deskripsi=?, id_kategori=?, foto=? WHERE id_menu=?";
$stmtMenu = mysqli_prepare($link, $sqlMenu);
mysqli_stmt_bind_param($stmtMenu, "sdsisi", $nama_menu, $harga, $deskripsi, $id_kategori, $foto_baru, $id_menu);
if (!mysqli_stmt_execute($stmtMenu)) { die("ERROR update menu: " . mysqli_error($link)); }
mysqli_stmt_close($stmtMenu);

// UPSERT STOK
$cek = mysqli_query($link, "SELECT id_stok FROM stok WHERE id_menu='$id_menu' LIMIT 1");
if (mysqli_num_rows($cek) > 0) {
    $sqlStok = "UPDATE stok SET jumlah_stok=?, stok_minimum=?, satuan=?, updated_at=NOW() WHERE id_menu=?";
    $stmtStok = mysqli_prepare($link, $sqlStok);
    mysqli_stmt_bind_param($stmtStok, "iisi", $jumlah_stok, $stok_minimum, $satuan, $id_menu);
    if (!mysqli_stmt_execute($stmtStok)) { die("ERROR update stok: " . mysqli_error($link)); }
    mysqli_stmt_close($stmtStok);
} else {
    $sqlStok = "INSERT INTO stok (id_menu, jumlah_stok, stok_minimum, satuan, updated_at) VALUES (?,?,?,?,NOW())";
    $stmtStok = mysqli_prepare($link, $sqlStok);
    mysqli_stmt_bind_param($stmtStok, "iiis", $id_menu, $jumlah_stok, $stok_minimum, $satuan);
    if (!mysqli_stmt_execute($stmtStok)) { die("ERROR insert stok: " . mysqli_error($link)); }
    mysqli_stmt_close($stmtStok);
}

header("Location: ../panel/menu-panel.php?success=1");
exit;
