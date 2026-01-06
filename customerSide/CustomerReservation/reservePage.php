<?php
require_once '../config.php';
session_start();

// Proteksi harus login customer
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../customerLogin/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: black;
            background-image: url('../image/loginBackground.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }

        .reserve-container {
            padding: 50px;
            border-radius: 10px;
            margin: auto;
            max-width: 500px;
            background: rgba(0,0,0,0.6);
        }

        h2 {
            text-align: center;
            font-family: 'Montserrat', serif;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
        }

        .btn-reserve {
            width: 100%;
            background: black;
            color: white;
            border: 1px solid white;
        }

        .btn-reserve:hover {
            background: white;
            color: black;
        }
    </style>
</head>
<body>

<div class="reserve-container">
    <a class="nav-link" href="../home/home.php">
        <h1 class="text-center" style="font-family:Copperplate; color:white;">TWO COUSIN</h1>
    </a>

    <h2>Reservation</h2>

    <form action="insertReservation.php" method="POST">

        <div class="form-group">
            <label>Reservation Date</label>
            <input type="date" name="reservation_date" required class="form-control">
        </div>

        <div class="form-group">
            <label>Reservation Time</label>
            <input type="time" name="reservation_time" required class="form-control">
        </div>

        <div class="form-group">
            <label>Jumlah Orang</label>
            <input type="number" id="head_count" name="head_count" min="1" required class="form-control">
        </div>

        <div class="form-group">
            <label>Pilih Meja</label>
            <select name="id_meja" id="table_list" required class="form-control">
                <option value="">-- Pilih jumlah orang dulu --</option>
            </select>
        </div>

        <div class="form-group">
            <label>Catatan (opsional)</label>
            <textarea name="special_request" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-reserve">Reserve</button>

    </form>
</div>

<script>
$(document).ready(function(){
    $("#head_count").on("input", function(){
        let head_count = $(this).val();

        if(head_count > 0){
            $("#table_list").html("<option>Loading...</option>");

            $.get("availability.php", { head_count: head_count }, function(data){
                $("#table_list").html(data);
            });
        }
    });
});
</script>

</body>
</html>
