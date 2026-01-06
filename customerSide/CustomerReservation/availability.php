<?php
require_once '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $head_count = (int)($_GET["head_count"] ?? 0);

    if ($head_count <= 0) {
        echo "<option value=''>Jumlah orang tidak valid</option>";
        exit;
    }

    $sql = "SELECT id_meja, nomor_meja, kapasitas 
            FROM meja 
            WHERE kapasitas >= ? AND status = 'kosong'
            ORDER BY kapasitas ASC";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $head_count);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<option value=''>Pilih Meja</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id_meja']}'>
                    Meja {$row['nomor_meja']} (Kapasitas: {$row['kapasitas']})
                  </option>";
        }
    } else {
        echo "<option value=''>Tidak ada meja tersedia</option>";
    }

    mysqli_stmt_close($stmt);
}
?>
