<?php
session_start(); // Ensure session is started
?>
<?php
require_once '../config.php';

            //success login pattern
            $message   = "";
$iconClass = "fa-check-circle";
$cardClass = "alert-success";
$bgColor   = "#D4F4DD";
$direction = "login.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $message   = "Access denied.";
    $iconClass = "fa-times-circle";
    $cardClass = "alert-danger";
    $bgColor   = "#FFA7A7";
    $direction = "register.php";
} else {

    $email       = trim($_POST["email"] ?? "");
    $member_name = trim($_POST["nama"] ?? "");
    $password    = trim($_POST["password"] ?? "");
    $phone       = trim($_POST["phone_number"] ?? "");

    $errors = [];

    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid.";
    if ($member_name === "") $errors[] = "Nama wajib diisi.";
    if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter.";
    if ($phone === "" || !ctype_digit($phone)) $errors[] = "No HP harus angka.";

    if (empty($errors)) {
        $check = "SELECT id_user FROM login WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $check)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = "Email sudah terdaftar.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    if (!empty($errors)) {
        $message   = implode("<br>", $errors);
        $iconClass = "fa-times-circle";
        $cardClass = "alert-danger";
        $bgColor   = "#FFA7A7";
        $direction = "register.php";
    } else {

        $sql = "INSERT INTO login (nama, username, password, role, no_hp, status, created_at)
                VALUES (?, ?, ?, 'customer', ?, 'aktif', NOW())";

        if ($stmt = mysqli_prepare($link, $sql)) {

            $save_password = $password;

            mysqli_stmt_bind_param($stmt, "ssss", $member_name, $email, $save_password, $phone);

            if (mysqli_stmt_execute($stmt)) {
                $message   = "Register successful.<br>Welcome to TWO COUSIN.<br>Please Login with your Account.";
                $iconClass = "fa-check-circle";
                $cardClass = "alert-success";
                $bgColor   = "#D4F4DD";
                $direction = "login.php";
            } else {
                $message   = "Register failed.<br>Error: " . mysqli_error($link);
                $iconClass = "fa-times-circle";
                $cardClass = "alert-danger";
                $bgColor   = "#FFA7A7";
                $direction = "register.php";
            }

            mysqli_stmt_close($stmt);
        } else {
            $message   = "Register failed.<br>Query error.";
            $iconClass = "fa-times-circle";
            $cardClass = "alert-danger";
            $bgColor   = "#FFA7A7";
            $direction = "register.php";
        }
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <style>
        body {
            text-align: center;
            padding: 40px 0;
            background: #EBF0F5;
        }
        h1 {
            color: #88B04B;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-weight: 900;
            font-size: 40px;
            margin-bottom: 10px;
        }
        p {
            color: #404F5E;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-size: 20px;
            margin: 0;
        }
        i.checkmark {
            color: #9ABC66;
            font-size: 100px;
            line-height: 200px;
            margin-left: -15px;
        }
        .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;
        }
        /* Additional CSS styles based on success/error message */
        .alert-success {
            /* Customize the styles for the success message card */
            background-color: <?php echo $bgColor; ?>;
        }
        .alert-success i {
            color: #5DBE6F; /* Customize the checkmark icon color for success */
        }
        .alert-danger {
            /* Customize the styles for the error message card */
            background-color: #FFA7A7; /* Custom background color for error */
        }
        .alert-danger i {
            color: #F25454; /* Customize the checkmark icon color for error */
        }
        .custom-x {
            color: #F25454; /* Customize the "X" symbol color for error */
            font-size: 100px;
            line-height: 200px;
        }
            .alert-box {
            max-width: 300px;
            margin: 0 auto;
        }

        .alert-icon {
            padding-bottom: 20px;
        }
    
    </style>
</head>
<body>
    <div class="card <?php echo $cardClass; ?>" style="display: none;">
        <div style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: 0 auto;">
            <?php if ($iconClass === 'fa-check-circle'): ?>
                <i class="checkmark">✓</i>
            <?php else: ?>
                <i class="custom-x" style="font-size: 100px; line-height: 200px;">✘</i>
            <?php endif; ?>
        </div>
        <h1><?php echo ($cardClass === 'alert-success') ? 'Success' : 'Error'; ?></h1>
        <p><?php echo $message; ?></p>
    </div>

    <div style="text-align: center; margin-top: 20px;">Redirecting back in <span id="countdown">3</span></div>

    <script>
        //Declare the direction of login success and fail 
        var direction = "<?php echo $direction; ?>";
        
        // Function to show the message card as a pop-up and start the countdown
        function showPopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "block";

            var i = 3;
            var countdownElement = document.getElementById("countdown");
            var countdownInterval = setInterval(function() {
                i--;
                countdownElement.textContent = i;
                if (i <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = direction;
                }
            }, 1000); // 1000 milliseconds = 1 second
        }

        // Show the message card and start the countdown when the page is loaded
        window.onload = showPopup;

        // Function to hide the message card after a delay
        function hidePopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "none";
            // Redirect to another page after hiding the pop-up (adjust the delay as needed)
            setTimeout(function () {
                window.location.href = direction; // Replace with your desired URL
            }, 3000); // 3000 milliseconds = 3 seconds
        }

        // Hide the message card after 3 seconds (adjust the delay as needed)
        setTimeout(hidePopup, 3000);
    </script>
</body>
</html>