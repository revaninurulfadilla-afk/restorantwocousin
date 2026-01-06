<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
include '../inc/dashHeader.php';
require_once "../config.php";
?>

<style>
    .wrapper { width: 90%; padding-left: 200px; padding-top: 20px; }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5">
        <div class="row">
            <div class="col">

                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Menu Items</h2>
                    <a href="../menuCrud/createItem.php" class="btn btn-outline-dark">
                        <i class="fa fa-plus"></i> Add Menu
                    </a>
                </div>

                <div class="mb-3">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="kategori" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    <?php
                                    $q = mysqli_query($link, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                                    while ($k = mysqli_fetch_assoc($q)) {
                                        $selected = ($_POST["kategori"] ?? "") == $k["id_kategori"] ? "selected" : "";
                                        echo "<option value='{$k["id_kategori"]}' $selected>{$k["nama_kategori"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>

                            <div class="col text-right">
                                <a href="menu-panel.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if (!empty($_POST["kategori"])) {
                    $id_kategori = (int) $_POST["kategori"];
                    $sql = "SELECT m.*, k.nama_kategori, 
                                   s.jumlah_stok, s.satuan, s.stok_minimum
                            FROM menu m
                            JOIN kategori k ON m.id_kategori = k.id_kategori
                            LEFT JOIN stok s ON m.id_menu = s.id_menu
                            WHERE m.id_kategori = $id_kategori
                            ORDER BY m.id_menu DESC";
                } else {
                    $sql = "SELECT m.*, k.nama_kategori, 
                                   s.jumlah_stok, s.satuan, s.stok_minimum
                            FROM menu m
                            JOIN kategori k ON m.id_kategori = k.id_kategori
                            LEFT JOIN stok s ON m.id_menu = s.id_menu
                            ORDER BY m.id_menu DESC";
                }

                $result = mysqli_query($link, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<table class="table table-bordered table-striped">';
                    echo "<thead>
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>";

                    while ($row = mysqli_fetch_assoc($result)) {

                        $stok = $row['jumlah_stok'] ?? 0;
                        $satuan = $row['satuan'] ?? "porsi";
                        $stok_min = $row['stok_minimum'] ?? 0;

                        // indikator stok kurang
                        $stokStyle = "";
                        if ($stok <= $stok_min) {
                            $stokStyle = "style='color:black;font-weight:'";
                        }

                        echo "<tr>";
                        echo "<td>{$row['id_menu']}</td>";

                        echo "<td>
                                <img src='../../customerSide/image/" . htmlspecialchars($row['foto']) . "' 
                                     width='70' height='70' style='object-fit:cover;border-radius:10px;'>
                              </td>";

                        echo "<td>{$row['nama_menu']}</td>";
                        echo "<td>{$row['nama_kategori']}</td>";
                        echo "<td>Rp" . number_format($row['harga'], 0, ',', '.') . "</td>";

                        // STOK
                        echo "<td $stokStyle>{$stok} {$satuan}</td>";

                        echo "<td>{$row['status']}</td>";
                        echo "<td>{$row['created_at']}</td>";

                        // ACTION
                        echo "<td>
                                <a href='../menuCrud/editItem.php?id={$row['id_menu']}' title='Edit'>
                                    <i class='fa fa-pencil'></i>
                                </a>

                                &nbsp;&nbsp;

                                <a href='../menuCrud/deleteItem.php?id={$row['id_menu']}' 
                                   onclick=\"return confirm('Yakin hapus menu ini?')\" title='Delete'>
                                    <i class='fa fa-trash text-danger'></i>
                                </a>
                              </td>";

                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo '<div class="alert alert-danger"><em>No records found.</em></div>';
                }

                mysqli_close($link);
                ?>

            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
