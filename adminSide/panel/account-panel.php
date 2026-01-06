<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>
    <style>
        .wrapper{ width: 1300px; padding-left: 200px; padding-top: 20px  }
    </style>

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Staff Details</h2>
                    <a href="../staffCrud/createStaff.php" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Add Staff</a>
                </div>
                
                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="search" name="search" class="form-control" placeholder="Cari ID / Nama / Username / No HP Staff">

                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>
                            <div class="col" style="text-align: right;" >
                                <a href="account-panel.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php
                    require_once "../config.php";

                    $search = trim($_POST["search"] ?? "");

                    if ($search != "") {

                        $sql = "SELECT id_user, nama, username, role, no_hp, status, created_at
                                FROM login
                                WHERE role IN ('admin','kasir')
                                AND (
                                    id_user LIKE CONCAT('%', ?, '%')
                                    OR nama LIKE CONCAT('%', ?, '%')
                                    OR username LIKE CONCAT('%', ?, '%')
                                    OR no_hp LIKE CONCAT('%', ?, '%')
                                )
                                ORDER BY id_user ASC";

                        $stmt = mysqli_prepare($link, $sql);
                        mysqli_stmt_bind_param($stmt, "ssss", $search, $search, $search, $search);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                    } else {

                        $sql = "SELECT id_user, nama, username, role, no_hp, status, created_at
                                FROM login
                                WHERE role IN ('admin','kasir')
                                ORDER BY id_user ASC";

                        $result = mysqli_query($link, $sql);
                    }

                    if ($result && mysqli_num_rows($result) > 0) {

                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>ID Staff</th>";
                        echo "<th>Nama</th>";
                        echo "<th>Username</th>";
                        echo "<th>Role</th>";
                        echo "<th>No HP</th>";
                        echo "<th>Status</th>";
                        echo "<th>Created</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . (int)$row["id_user"] . "</td>";
                            echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                            echo "<td><b>" . strtoupper(htmlspecialchars($row["role"])) . "</b></td>";
                            echo "<td>" . htmlspecialchars($row["no_hp"] ?? "-") . "</td>";
                            echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";

                    } else {
                        echo '<div class="alert alert-danger"><em>No staff records were found.</em></div>';
                    }

                    mysqli_close($link);
                    ?>

            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
