<?php
session_start();
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $account_id = trim($_POST["account_id"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id_user, nama, role, password 
            FROM login
            WHERE id_user = ? AND role IN ('admin','kasir')
            LIMIT 1";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $account_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    if ($user) {

        // ✅ Kalau password kamu masih plain text
        if ($password === $user["password"]) {

            $_SESSION["logged_account_id"] = $user["id_user"];
            $_SESSION["staff_name"] = $user["nama"];
            $_SESSION["staff_role"] = $user["role"];

            header("Location: ../panel/pos-panel.php");
            exit;
        } else {
            echo "Password salah!";
        }

        // ✅ kalau password hash, pakai ini:
        // if(password_verify($password, $user["password"])) { ... }
    } else {
        echo "Staff tidak ditemukan!";
    }
}
?>
