<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
include '../inc/dashHeader.php';
require_once "../config.php";

$search = trim($_POST['search'] ?? "");
?>
<style>
    .wrapper{ width: 80%; padding-left: 200px; padding-top: 20px; }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">

                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Staff Details</h2>
                    <a href="../staffCrud/createStaff.php" class="btn btn-outline-dark">
                        <i class="fa fa-plus"></i> Add Staff
                    </a>
                </div>

                <div class="mb-3">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="search" name="search"
                                       class="form-control"
                                       placeholder="Cari Staff ID / Nama / Username"
                                       value="<?= htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>
                            <div class="col" style="text-align: right;">
                                <a href="staff-panel.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>

<?php
if ($search !== "") {
    $sql = "SELECT id_user, nama, username, role, no_hp, status, created_at
            FROM login
            WHERE role IN ('admin','kasir')
              AND (CAST(id_user AS CHAR) LIKE ? OR nama LIKE ? OR username LIKE ?)
            ORDER BY id_user DESC";
    $stmt = mysqli_prepare($link, $sql);
    $like = "%$search%";
    mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
} else {
    $sql = "SELECT id_user, nama, username, role, no_hp, status, created_at
            FROM login
            WHERE role IN ('admin','kasir')
            ORDER BY id_user DESC";
    $stmt = mysqli_prepare($link, $sql);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {

    echo '<table class="table table-bordered table-striped">';
    echo "<thead>
            <tr>
                <th style='width:7em;'>Staff ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th style='width:7em;'>Role</th>
                <th>No HP</th>
                <th style='width:7em;'>Status</th>
                <th style='width:12em;'>Created</th>
                <th>Action</th>
            </tr>
          </thead>";
    echo "<tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . (int)$row["id_user"] . "</td>";
        echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["no_hp"] ?? "-") . "</td>";
        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
        echo "<td>";

        echo '<a href="../staffCrud/editStaff.php?id=' . (int)$row["id_user"] . '" title="Edit">
                <span class="fa fa-pencil text-dark"></span>
            </a>';

        echo " | ";

        echo '<a href="../staffCrud/delete_staff.php?id=' . (int)$row["id_user"] . '" 
                onclick="return confirm(\'Yakin hapus customer ini?\')" title="Delete">
                <span class="fa fa-trash text-danger"></span>
            </a>';

        echo "</td>";

        echo "</tr>";
    }

    echo "</tbody></table>";

} else {
    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>

            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
