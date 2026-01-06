<?php
session_start();
require_once "../config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_kategori   = (int)($_POST["id_kategori"] ?? 0);
    $nama_menu     = trim($_POST["nama_menu"] ?? "");
    $harga         = (int)($_POST["harga"] ?? 0);
    $deskripsi     = trim($_POST["deskripsi"] ?? "");
    $status        = trim($_POST["status"] ?? "tersedia");

    $jumlah_stok   = (int)($_POST["jumlah_stok"] ?? 0);
    $stok_minimum  = (int)($_POST["stok_minimum"] ?? 0);
    $satuan        = trim($_POST["satuan"] ?? "porsi");

    if ($id_kategori == 0 || $nama_menu == "" || $harga <= 0) {
        die("Data menu tidak lengkap!");
    }

    // ✅ Folder upload aman (langsung ke customerSide/image)
    $uploadDir = __DIR__ . "/../../customerSide/image/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $foto = $_FILES["foto"]["name"] ?? "";
    $tmp  = $_FILES["foto"]["tmp_name"] ?? "";

    if ($foto == "" || $tmp == "") {
        die("Foto wajib diupload!");
    }

    $namaFile = time() . "_" . basename($foto);

    if (!move_uploaded_file($tmp, $uploadDir . $namaFile)) {
        die("Upload foto gagal!");
    }

    //transaksi: insert menu + stok harus sama-sama berhasil
    mysqli_begin_transaction($link);

    try {

        // 1) insert menu
        $sql = "INSERT INTO menu (id_kategori, nama_menu, harga, deskripsi, foto, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "isisss",
            $id_kategori,
            $nama_menu,
            $harga,
            $deskripsi,
            $namaFile,
            $status
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal insert menu: " . mysqli_error($link));
        }

        $id_menu = mysqli_insert_id($link);
        mysqli_stmt_close($stmt);

        // 2) insert stok
        $sql2 = "INSERT INTO stok (id_menu, jumlah_stok, stok_minimum, satuan, updated_at)
                 VALUES (?, ?, ?, ?, NOW())";

        $stmt2 = mysqli_prepare($link, $sql2);
        mysqli_stmt_bind_param($stmt2, "iiis",
            $id_menu,
            $jumlah_stok,
            $stok_minimum,
            $satuan
        );

        if (!mysqli_stmt_execute($stmt2)) {
            throw new Exception("Gagal insert stok: " . mysqli_error($link));
        }

        mysqli_stmt_close($stmt2);

        mysqli_commit($link);

        header("Location: ../panel/menu-panel.php?success=1");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($link);
        echo "<h3 style='color:red;'>".$e->getMessage()."</h3>";
        exit;
    }
}
?>
