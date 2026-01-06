<?php
session_start();
require_once "../config.php";
include "../inc/dashHeader.php";
?>

<style>
.wrapper{ width: 85%; padding-left: 200px; padding-top: 20px; }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Bills / Riwayat Pembayaran</h2>

                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="search" name="search" class="form-control"
                                    placeholder="Cari ID Pesanan / Meja / Metode">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>
                            <div class="col" style="text-align:right;">
                                <a href="bill-panel.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST['search']) && $_POST['search'] != "") {
                    $search = mysqli_real_escape_string($link, $_POST['search']);
                    $sql = "
                        SELECT p.id_pesanan, p.id_meja, p.tanggal_pesan, p.total_harga, p.status_pesanan,
                               b.metode, b.total_bayar, b.uang_diterima, b.kembalian, b.tanggal_bayar
                        FROM pesanan p
                        LEFT JOIN pembayaran b ON p.id_pesanan = b.id_pesanan
                        WHERE p.id_pesanan LIKE '%$search%'
                           OR p.id_meja LIKE '%$search%'
                           OR b.metode LIKE '%$search%'
                        ORDER BY p.id_pesanan DESC
                    ";
                } else {
                    $sql = "
                        SELECT p.id_pesanan, p.id_meja, p.tanggal_pesan, p.total_harga, p.status_pesanan,
                               b.metode, b.total_bayar, b.uang_diterima, b.kembalian, b.tanggal_bayar
                        FROM pesanan p
                        LEFT JOIN pembayaran b ON p.id_pesanan = b.id_pesanan
                        ORDER BY p.id_pesanan DESC
                    ";
                }

                $result = mysqli_query($link, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<table class="table table-bordered table-striped">';
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>ID Pesanan</th>";
                    echo "<th>Status</th>";
                    echo "<th>Total</th>";
                    echo "<th>Metode</th>";
                    echo "<th>Bayar</th>";
                    echo "<th>Kembali</th>";
                    echo "<th>Tanggal Pesan</th>";
                    echo "<th>Tanggal Bayar</th>";
                    echo "<th>Receipt</th>";
                    echo "<th>Delete</th>";
                    echo "</tr>";
                    echo "</thead><tbody>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['id_pesanan']}</td>";
                        echo "<td>{$row['status_pesanan']}</td>";
                        echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                        echo "<td>" . strtoupper($row['metode'] ?? '-') . "</td>";
                        echo "<td>Rp " . number_format($row['uang_diterima'] ?? 0, 0, ',', '.') . "</td>";
                        echo "<td>Rp " . number_format($row['kembalian'] ?? 0, 0, ',', '.') . "</td>";
                        echo "<td>{$row['tanggal_pesan']}</td>";
                        echo "<td>" . ($row['tanggal_bayar'] ?? '-') . "</td>";

                        echo "<td>
                            <a href='../posBackend/receipt_pdf.php?id_pesanan={$row['id_pesanan']}' 
                               title='Receipt'>
                               <span class='fa fa-receipt text-black'></span>
                            </a>
                        </td>";
                        echo "<td>
                        <a href='../billsCrud/deleteBill.php?id={$row['id_pesanan']}'
                            onclick=\"return confirm('Yakin mau hapus pesanan ini?')\"
                            class='text-danger'>
                            <span class='fa fa-trash'></span>
                        </a>
                        </td>";

                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-danger'><em>No records were found.</em></div>";
                }
                ?>

            </div>
        </div>
    </div>
</div>

<?php include "../inc/dashFooter.php"; ?>
