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
</style>

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">

                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Reservation Details</h2>
                    <a href="../reservationsCrud/createReservation.php" class="btn btn-outline-dark">
                        <i class="fa fa-plus"></i> Add Reservation
                    </a>
                </div>

                <div class="mb-3">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Cari Nama / Meja / Status / Tanggal (2026-01)">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>
                            <div class="col text-right">
                                <a href="reservation-panel.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                $sql = "SELECT p.*, m.nomor_meja, l.nama AS nama_login
                        FROM pesanan p
                        LEFT JOIN meja m ON p.id_meja = m.id_meja
                        LEFT JOIN login l ON p.id_customer = l.id_user
                        WHERE p.status_pesanan = 'reservasi'
                        ORDER BY p.tanggal_pesan DESC";

                if (isset($_POST['search']) && !empty($_POST['search'])) {
                    $search = mysqli_real_escape_string($link, $_POST['search']);
                    $sql = "SELECT p.*, m.nomor_meja, l.nama AS nama_login
                            FROM pesanan p
                            LEFT JOIN meja m ON p.id_meja = m.id_meja
                            LEFT JOIN login l ON p.id_customer = l.id_user
                            WHERE p.status_pesanan = 'reservasi'
                            AND (
                                p.nama_customer LIKE '%$search%' OR
                                l.nama LIKE '%$search%' OR
                                m.nomor_meja LIKE '%$search%' OR
                                p.tanggal_pesan LIKE '%$search%'
                            )
                            ORDER BY p.tanggal_pesan DESC";
                }

                $result = mysqli_query($link, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<table class="table table-bordered table-striped">';
                    echo "<thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Nama Customer</th>
                                <th>Meja</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th style='width:120px'>Action</th>
                            </tr>
                          </thead>";
                    echo "<tbody>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        $nama = $row['nama_customer'] ?: $row['nama_login'];
                        $meja = $row['nomor_meja'] ?? "-";

                        echo "<tr>";
                        echo "<td>{$row['id_pesanan']}</td>";
                        echo "<td>" . htmlspecialchars($nama) . "</td>";
                        echo "<td>$meja</td>";
                        echo "<td>{$row['tanggal_pesan']}</td>";
                        echo "<td>{$row['status_pesanan']}</td>";
                        echo "<td>{$row['catatan']}</td>";

                        echo "<td>";
                        echo '<a href="../reservationsCrud/updateReservation.php?id=' . (int)$row['id_pesanan'] . '" title="Edit">
                                <i class="fa fa-pencil text-black"></i>
                              </a> &nbsp;';
                        echo '<a href="../reservationsCrud/deleteReservation.php?id=' . (int)$row['id_pesanan'] . '" 
                                onclick="return confirm(\'Yakin hapus reservasi ini?\')" title="Delete">
                                <i class="fa fa-trash text-black"></i>
                              </a> &nbsp;';
                        echo '<a href="../reservationsCrud/reservationReceipt.php?id_pesanan=' . (int)$row['id_pesanan'] . '" title="Receipt">
                                <i class="fa fa-receipt text-black"></i>
                              </a>';

                        echo "</td>";

                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-danger'><em>No reservation found.</em></div>";
                }
                mysqli_close($link);
                ?>

            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
