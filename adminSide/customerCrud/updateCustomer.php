<?php
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_user  = (int) $_POST["id_user"];
    $nama     = trim($_POST["nama"]);
    $email    = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $no_hp    = trim($_POST["no_hp"]);
    $status   = trim($_POST["status"]);

    $sql = "UPDATE login 
            SET nama=?, email=?, username=?, no_hp=?, status=? 
            WHERE id_user=? AND role='customer'";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $nama, $email, $username, $no_hp, $status, $id_user);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../panel/customer-panel.php?success=1");
        exit;
    } else {
        echo "Gagal update customer: " . mysqli_error($link);
    }
}
?>
