<?php
require_once "../config.php";

$id_detail = (int)$_GET['id_detail'];

mysqli_query($link, "DELETE FROM detail_pesanan WHERE id_detail='$id_detail'");

header("Location: orderItem.php");
exit;
