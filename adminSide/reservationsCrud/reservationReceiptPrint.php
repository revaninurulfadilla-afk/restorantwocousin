<?php
require_once "../config.php";

if (!isset($_GET['id_pesanan'])) {
    die("ID reservation tidak valid.");
}

$id_pesanan = (int)$_GET['id_pesanan'];

$sql = "SELECT p.*, m.nomor_meja
        FROM pesanan p
        LEFT JOIN meja m ON p.id_meja = m.id_meja
        WHERE p.id_pesanan = '$id_pesanan'";

$result = mysqli_query($link, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("ID reservation tidak valid.");
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Reservation Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 300px;
            margin: auto;
            padding: 15px;
            border: 1px solid #000;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        table {
            width: 100%;
            font-size: 12px;
        }

        td {
            padding: 3px 0;
        }

        @media print {
            body {
                margin: 0;
            }
            .receipt {
                border: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

<div class="receipt">
    <div class="center">
        <h3 style="margin:0;">TWO COUSIN Restaurant</h3>
        <p style="margin:0;">Reservation Receipt</p>
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td><b>ID Reservasi</b></td>
            <td>: <?= $data['id_pesanan'] ?></td>
        </tr>
        <tr>
            <td><b>Meja</b></td>
            <td>: <?= $data['nomor_meja'] ?? '-' ?></td>
        </tr>
        <tr>
            <td><b>Tanggal</b></td>
            <td>: <?= $data['tanggal_pesan'] ?></td>
        </tr>
        <tr>
            <td><b>Status</b></td>
            <td>: <?= strtoupper($data['status_pesanan']) ?></td>
        </tr>
        <tr>
            <td><b>Catatan</b></td>
            <td>: <?= !empty($data['catatan']) ? $data['catatan'] : '-' ?></td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center">
        <p style="margin:0;">Terima kasih</p>
        <small><?= date("Y-m-d H:i:s") ?></small>
    </div>
</div>

</body>
</html>
