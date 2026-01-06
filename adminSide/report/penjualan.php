<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
include '../inc/dashHeader.php';
require_once "../config.php";
?>

<style>
    .wrapper {
        width: 85%;
        padding-left: 200px;
        padding-top: 20px;
    }

    @media print {

        @page {
            size: landscape;
            margin: 10mm;
        }

        body * {
            visibility: hidden;
        }

        #printArea, #printArea * {
            visibility: visible;
        }

        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 12px !important;
        }

        th, td {
            border: 1px solid black !important;
            padding: 6px !important;
        }

        h2, h5 {
            text-align: center;
        }
    }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5">

        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="pull-left">Laporan Penjualan</h2>
            </div>

            <div class="col-md-4 text-right no-print">
                <button onclick="window.print()" class="btn btn-dark">
                    <i class="fa fa-print"></i> Print Report
                </button>
            </div>
        </div>
        <form method="GET" class="mb-4 no-print">
            <div class="row">
                <div class="col-md-3">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control"
                           value="<?= $_GET['start_date'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control"
                           value="<?= $_GET['end_date'] ?? '' ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark mr-2">Filter</button>
                    <a href="penjualan.php" class="btn btn-light">Reset</a>
                </div>
            </div>
        </form>

        <?php
        $start_date = $_GET['start_date'] ?? "";
        $end_date   = $_GET['end_date'] ?? "";

        $whereDate = "";
        $periodeText = "All Time";

        if (!empty($start_date) && !empty($end_date)) {
            $whereDate = "AND DATE(p.tanggal_pesan) BETWEEN '$start_date' AND '$end_date'";
            $periodeText = "$start_date sampai $end_date";
        }
        $sql = "
            SELECT 
                m.id_menu,
                m.nama_menu,
                m.harga,
                SUM(d.qty) AS total_qty,
                SUM(d.qty * m.harga) AS total_omzet
            FROM pesanan p
            JOIN detail_pesanan d ON p.id_pesanan = d.id_pesanan
            JOIN menu m ON d.id_menu = m.id_menu
            WHERE p.status_pesanan = 'selesai'
            $whereDate
            GROUP BY m.id_menu
            ORDER BY total_qty DESC
        ";

        $result = mysqli_query($link, $sql);

        $totalQty = 0;
        $totalOmzet = 0;
        $dataPenjualan = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $totalQty += $row['total_qty'];
                $totalOmzet += $row['total_omzet'];
                $dataPenjualan[] = $row;
            }
        }
        ?>

        <div class="mb-3 no-print">
            <h5><b>Periode:</b> <?= $periodeText ?></h5>
        </div>

        <div class="row mb-4 no-print">
            <div class="col-md-4">
                <div class="card shadow p-3">
                    <h5>Total Item Terjual</h5>
                    <h3><?= $totalQty ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-3">
                    <h5>Total Omzet Penjualan</h5>
                    <h3>Rp <?= number_format($totalOmzet,0,',','.') ?></h3>
                </div>
            </div>
        </div>
        <div id="printArea">
            <h2>Laporan Penjualan</h2>
            <h5>Periode: <?= $periodeText ?></h5>

            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Qty Terjual</th>
                        <th>Omzet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($dataPenjualan) > 0): ?>
                        <?php $no=1; foreach($dataPenjualan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['nama_menu'] ?></td>
                                <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
                                <td><?= $row['total_qty'] ?></td>
                                <td>Rp <?= number_format($row['total_omzet'],0,',','.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-danger">
                                Tidak ada data penjualan ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="3">TOTAL</td>
                        <td><?= $totalQty ?></td>
                        <td>Rp <?= number_format($totalOmzet,0,',','.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
