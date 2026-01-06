<?php
session_start();
require_once '../config.php'; 
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: ../home/home.php");
    exit;
}


$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1) ambil input
    if (empty(trim($_POST["username"] ?? ""))) {
        $username_err = "Username wajib diisi.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"] ?? ""))) {
        $password_err = "Password wajib diisi.";
    } else {
        $password = trim($_POST["password"]);
    }
    if (empty($username_err) && empty($password_err)) {

        $sql = "SELECT id_user, nama, username, password, role, status
                FROM login
                WHERE username = ? AND role = 'customer' AND status = 'aktif'
                LIMIT 1";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    if ($password === $row["password"]) {


                        session_regenerate_id(true);

                        $_SESSION["loggedin"] = true;
                        $_SESSION["id_user"]  = (int)$row["id_user"];
                        $_SESSION["nama"]     = $row["nama"];
                        $_SESSION["username"] = $row["username"];
                        $_SESSION["role"]     = $row["role"];

                        header("Location: ../home/home.php");
                        exit;

                    } else {
                        $password_err = "Password salah.";
                    }
                } else {
                    $username_err = "Akun customer tidak ditemukan / tidak aktif.";
                }
            } else {
                $password_err = "Terjadi kesalahan sistem. Coba lagi.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $password_err = "Query gagal dipersiapkan.";
        }
    }
}
?>
<?php if(isset($_GET["reset"]) && $_GET["reset"]=="success"): ?>
<div class="alert alert-success">
    Password berhasil direset. Silahkan login.
</div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Customer</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white" style="min-height:100vh; display:flex; align-items:center;">
  <div class="container" style="max-width:420px;">
    <h3 class="text-center mb-4">Login Customer</h3>

    <form action="login.php" method="post">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
        <small class="text-danger"><?php echo $username_err; ?></small>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
        <small class="text-danger"><?php echo $password_err; ?></small>
      </div>

      <button type="submit" class="btn btn-light btn-block">Login</button>
      <p style="margin-top:10px;">
    <a href="forgot_password.php" style="color:lightblue;">Lupa Password?</a>
</p>

      <p class="mt-3 text-center">Belum punya akun? <a href="register.php">Daftar</a></p>
    </form>
  </div>
</body>
</html>
