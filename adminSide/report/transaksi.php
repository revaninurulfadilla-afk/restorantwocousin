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

    /* ✅ PRINT HANYA TABLE */
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

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 12px !important;
        }

        th, td {
            border: 1px solid black !important;
            padding: 6px !important;
            font-size: 12px !important;
        }

        h2, h5 {
            text-align: center;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5">

        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="pull-left">Laporan Transaksi</h2>
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
                    <a href="transaksi.php" class="btn btn-light">Reset</a>
                </div>
            </div>
        </form>

        <?php
        $start_date = $_GET['start_date'] ?? "";
        $end_date   = $_GET['end_date'] ?? "";

        $whereDate = "";
        $periodeText = "All Time";

        if (!empty($start_date) && !empty($end_date)) {
            $whereDate = "AND DATE(b.tanggal_bayar) BETWEEN '$start_date' AND '$end_date'";
            $periodeText = "$start_date sampai $end_date";
        }

        $sql = "
            SELECT b.*, p.id_meja
            FROM pembayaran b
            JOIN pesanan p ON b.id_pesanan = p.id_pesanan
            WHERE b.status_bayar = 'lunas'
            $whereDate
            ORDER BY b.tanggal_bayar DESC
        ";

        $result = mysqli_query($link, $sql);

        $totalPendapatan = 0;
        $totalTransaksi = 0;
        $dataTransaksi = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $totalTransaksi++;
                $totalPendapatan += $row['total_bayar'];
                $dataTransaksi[] = $row;
            }
        }
        ?>

        <div class="mb-3 no-print">
            <h5><b>Periode:</b> <?= $periodeText ?></h5>
        </div>

        <div class="row mb-4 no-print">
            <div class="col-md-4">
                <div class="card shadow p-3">
                    <h5>Total Transaksi</h5>
                    <h3><?= $totalTransaksi ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-3">
                    <h5>Total Pendapatan</h5>
                    <h3>Rp <?= number_format($totalPendapatan,0,',','.') ?></h3>
                </div>
            </div>
        </div>
        <div id="printArea">
            <h2 class="text-center">Data Laporan Transaksi</h2>
            <h5 class="text-center">Periode: <?= $periodeText ?></h5>

            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Meja</th>
                        <th>Metode</th>
                        <th>Total Bayar</th>
                        <th>Uang Diterima</th>
                        <th>Kembalian</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($dataTransaksi) > 0): ?>
                        <?php foreach($dataTransaksi as $row): ?>
                            <tr>
                                <td><?= $row['id_pesanan'] ?></td>
                                <td><?= $row['id_meja'] ?></td>
                                <td><?= strtoupper($row['metode']) ?></td>
                                <td>Rp <?= number_format($row['total_bayar'],0,',','.') ?></td>
                                <td>Rp <?= number_format($row['uang_diterima'],0,',','.') ?></td>
                                <td>Rp <?= number_format($row['kembalian'],0,',','.') ?></td>
                                <td><?= strtoupper($row['status_bayar']) ?></td>
                                <td><?= $row['tanggal_bayar'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-danger">
                                Tidak ada transaksi ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="3">TOTAL</td>
                        <td colspan="5">Rp <?= number_format($totalPendapatan,0,',','.') ?></td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
