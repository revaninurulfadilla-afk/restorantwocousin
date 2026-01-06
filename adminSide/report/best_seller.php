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
        .no-print {
            display: none !important;
        }
        body {
            background: white !important;
        }
        .wrapper {
            padding-left: 0 !important;
            width: 100% !important;
        }
    }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5">

        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="pull-left">Best Seller Menu Report</h2>
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
                    <a href="best_seller.php" class="btn btn-light">Reset</a>
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

        $query = "
            SELECT m.nama_menu,
                   SUM(d.qty) AS total_terjual,
                   SUM(d.subtotal) AS total_pendapatan
            FROM detail_pesanan d
            JOIN menu m ON d.id_menu = m.id_menu
            JOIN pesanan p ON d.id_pesanan = p.id_pesanan
            JOIN pembayaran b ON p.id_pesanan = b.id_pesanan
            WHERE b.status_bayar = 'lunas'
            $whereDate
            GROUP BY d.id_menu
            ORDER BY total_terjual DESC
        ";

        $result = mysqli_query($link, $query);

        $labels = [];
        $data = [];
        $totalPendapatan = 0;
        $totalTerjual = 0;
        ?>

        <div class="mb-3">
            <h5><b>Periode:</b> <?= $periodeText ?></h5>
        </div>
        <div class="row">
            <div class="col-md-7">
                <h4>Daftar Best Seller</h4>
                <table class="table table-bordered table-striped mt-3">
                    <thead class="thead-dark">
                        <tr>
                            <th>Menu</th>
                            <th>Total Terjual</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {

                            $totalPendapatan += $row['total_pendapatan'];
                            $totalTerjual += $row['total_terjual'];

                            echo "<tr>";
                            echo "<td>{$row['nama_menu']}</td>";
                            echo "<td>{$row['total_terjual']}</td>";
                            echo "<td>Rp " . number_format($row['total_pendapatan'], 0, ',', '.') . "</td>";
                            echo "</tr>";

                            // Chart Top 5
                            if (count($labels) < 5) {
                                $labels[] = $row['nama_menu'];
                                $data[]   = $row['total_terjual'];
                            }
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center text-danger'>Tidak ada data.</td></tr>";
                    }
                    ?>
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td>Total</td>
                            <td><?= $totalTerjual ?></td>
                            <td>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <div class="col-md-5">
                <h4>Top 5 Best Seller</h4>
                <div style="width:100%; height:400px;">
                    <canvas id="bestSellerChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById("bestSellerChart");

    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                data: <?= json_encode($data) ?>,
                backgroundColor: [
                    'rgb(8, 32, 50)',
                    'rgb(255, 76, 41)',
                    'rgb(13, 18, 130)',
                    'rgb(143, 67, 238)',
                    'rgb(179, 19, 18)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "right"
                }
            }
        }
    });
</script>

<?php include '../inc/dashFooter.php'; ?>
