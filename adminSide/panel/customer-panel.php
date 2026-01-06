<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
include '../inc/dashHeader.php';
require_once "../config.php";
?>

<style>
    .wrapper { width: 80%; padding-left: 200px; padding-top: 20px; }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5">
        <div class="row">
            <div class="col">

                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Customer Details</h2>
                    <a href="../customerCrud/createCust.php" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Add Customer</a>
                </div>

                <div class="mb-3">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search ID, Name, Username"
                                    value="<?php echo $_POST['search'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>
                            <div class="col text-right">
                                <a href="customer-panel.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST['search']) && !empty($_POST['search'])) {
                    $search = mysqli_real_escape_string($link, $_POST['search']);

                    $sql = "SELECT id_user, nama, username, no_hp, status, created_at
                            FROM login
                            WHERE role='customer'
                            AND (nama LIKE '%$search%' OR username LIKE '%$search%' OR id_user LIKE '%$search%')
                            ORDER BY id_user DESC";
                } else {
                    $sql = "SELECT id_user, nama, username, no_hp, status, created_at
                            FROM login
                            WHERE role='customer'
                            ORDER BY id_user DESC";
                }

                $result = mysqli_query($link, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<table class="table table-bordered table-striped">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Nama</th>';
                    echo '<th>Username</th>';
                    echo '<th>No HP</th>';
                    echo '<th>Status</th>';
                    echo '<th>Created At</th>';
                    echo '<th>Action</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['id_user']}</td>";
                        echo "<td>{$row['nama']}</td>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>{$row['no_hp']}</td>";
                        echo "<td>{$row['status']}</td>";
                        echo "<td>{$row['created_at']}</td>";
                        echo "<td>";
                        echo '<a href="../customerCrud/deleteCustomer.php?id=' . (int)$row["id_user"] . '"
                                onclick="return confirm(\'Yakin hapus customer ini?\')">
                                <span class="fa fa-trash text-black"></span>
                            </a>';
                       
                        echo '<a href="../customerCrud/editCustomer.php?id=' . (int)$row["id_user"] . '" title="Edit">
                                <span class="fa fa-pencil"></span>
                             </a>';
                        echo "</td>";

                        echo "</tr>";

                        
                    }
                    


                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-danger"><em>No records found.</em></div>';
                }
                echo "<td>";


                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
