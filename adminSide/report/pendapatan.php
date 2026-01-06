<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
include '../inc/dashHeader.php';
require_once "../config.php";

$start_date = $_GET['start_date'] ?? "";
$end_date   = $_GET['end_date'] ?? "";

$whereDate = "";
$periodeText = "All Time";

if (!empty($start_date) && !empty($end_date)) {
    $start_date = mysqli_real_escape_string($link, $start_date);
    $end_date   = mysqli_real_escape_string($link, $end_date);

    $whereDate = "AND DATE(tanggal_bayar) BETWEEN '$start_date' AND '$end_date'";
    $periodeText = "$start_date sampai $end_date";
}

$sql = "
    SELECT *
    FROM pembayaran
    WHERE status_bayar='lunas'
    $whereDate
    ORDER BY tanggal_bayar DESC
";

$result = mysqli_query($link, $sql);

$totalPendapatan = 0;
$totalTransaksi = 0;
$dataPendapatan = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $totalTransaksi++;
        $totalPendapatan += $row['total_bayar'];
        $dataPendapatan[] = $row;
    }
}
$sqlChart = "
    SELECT DATE(tanggal_bayar) AS hari, SUM(total_bayar) AS total
    FROM pembayaran
    WHERE status_bayar='lunas'
    $whereDate
    GROUP BY DATE(tanggal_bayar)
    ORDER BY hari ASC
";

$resultChart = mysqli_query($link, $sqlChart);

$chartLabels = [];
$chartData   = [];

if ($resultChart && mysqli_num_rows($resultChart) > 0) {
    while ($row = mysqli_fetch_assoc($resultChart)) {
        $chartLabels[] = $row['hari'];
        $chartData[]   = $row['total'];
    }
}
?>

<style>
.wrapper {
    width: 85%;
    padding-left: 200px;
    padding-top: 20px;
}
@media print {
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { position: absolute; left: 0; top: 0; width: 100%; }
    .no-print { display: none !important; }
}
</style>

<div class="wrapper">
    <div class="container-fluid pt-5">

        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Laporan Pendapatan</h2>
                <p><b>Periode:</b> <?= $periodeText ?></p>
            </div>

            <div class="col-md-4 text-right no-print">
                <button onclick="window.print()" class="btn btn-dark">
                    <i class="fa fa-print"></i> Print Report
                </button>
            </div>
        </div>

        <!-- FILTER -->
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
                    <a href="pendapatan.php" class="btn btn-light">Reset</a>
                </div>
            </div>
        </form>
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

        <div class="card shadow p-3 mt-4">
    <h4 class="mb-3">Grafik Pendapatan</h4>
    <div style="height:300px;">
        <canvas id="pendapatanChart"></canvas>
    </div>
</div><br>

        <!-- TABLE -->
        <div id="printArea">
            <h4>Data Pendapatan</h4>
            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Metode</th>
                        <th>Total Bayar</th>
                        <th>Uang Diterima</th>
                        <th>Kembalian</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($dataPendapatan) > 0): ?>
                        <?php foreach($dataPendapatan as $row): ?>
                            <tr>
                                <td><?= $row['id_pesanan'] ?></td>
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
                            <td colspan="7" class="text-center text-danger">
                                Tidak ada data pendapatan ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="2">TOTAL</td>
                        <td colspan="5">Rp <?= number_format($totalPendapatan,0,',','.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById("pendapatanChart").getContext("2d");

const labels = <?= json_encode($chartLabels); ?>;
const dataPendapatan = <?= json_encode($chartData); ?>;

new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: [{
            label: "Pendapatan (Rp)",
            data: dataPendapatan,
            backgroundColor: "rgba(54, 162, 235, 0.8)",
            borderRadius: 8,
            barThickness: 50
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return "Rp " + context.raw.toLocaleString("id-ID");
                    }
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    maxRotation: 0,
                    minRotation: 0
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return "Rp " + value.toLocaleString("id-ID");
                    }
                }
            }
        }
    }
});
</script>


<?php include '../inc/dashFooter.php'; ?>
