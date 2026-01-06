<?php
session_start();
require_once "../config.php";
include "../inc/dashHeader.php";
$cek = mysqli_query($link, "SELECT id_pesanan 
                            FROM pesanan 
                            WHERE status_pesanan='pending'
                            ORDER BY id_pesanan DESC LIMIT 1");

if(mysqli_num_rows($cek) > 0){
    $row = mysqli_fetch_assoc($cek);
    $id_pesanan = $row['id_pesanan'];
} else {
    // kalau gak ada, buat pesanan baru
    mysqli_query($link, "INSERT INTO pesanan (tanggal_pesan, status_pesanan, total_harga)
                         VALUES (NOW(), 'pending', 0)");
    $id_pesanan = mysqli_insert_id($link);
}

$search = $_POST['search'] ?? "";
if(!empty($search)){
    $searchSafe = mysqli_real_escape_string($link, $search);
    $menuQuery = "SELECT * FROM menu 
                  WHERE nama_menu LIKE '%$searchSafe%' 
                  ORDER BY id_menu DESC";
} else {
    $menuQuery = "SELECT * FROM menu ORDER BY id_menu DESC";
}

$menuResult = mysqli_query($link, $menuQuery);

$cartQuery = "SELECT d.*, m.nama_menu 
              FROM detail_pesanan d
              JOIN menu m ON d.id_menu = m.id_menu
              WHERE d.id_pesanan = '$id_pesanan'";
$cartResult = mysqli_query($link, $cartQuery);

$cart_total = 0;
while($row = mysqli_fetch_assoc($cartResult)){
    $cart_total += $row['subtotal'];
}
mysqli_data_seek($cartResult, 0);

mysqli_query($link, "UPDATE pesanan SET total_harga = '$cart_total' WHERE id_pesanan='$id_pesanan'");

?>

<link href="../css/pos.css" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 order-md-1 m-1" id="item-select-section">
            <div class="container-fluid pt-4 pl-500 row" style="margin-left: 10rem;width: 81%;">

                <div class="mt-5 mb-2">
                    <h3 class="pull-left">Food & Drinks</h3>
                </div>
                <div class="mb-3">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="search" name="search" class="form-control"
                                       placeholder="Search Food & Drinks"
                                       value="<?= htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>
                            <div class="col text-right">
                                <a href="orderItem.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div style="max-height:45rem;overflow-y:auto;">
                    <?php
                    if($menuResult && mysqli_num_rows($menuResult) > 0){
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead><tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Add</th>
                              </tr></thead><tbody>";

                        while($m = mysqli_fetch_assoc($menuResult)){
                            echo "<tr>";
                            echo "<td>{$m['id_menu']}</td>";
                            echo "<td>{$m['nama_menu']}</td>";
                            echo "<td>{$m['id_kategori']}</td>";
                            echo "<td>Rp " . number_format($m['harga'], 0, ',', '.') . "</td>";

                            echo "<td>
                                    <form method='POST' action='addItem.php'>
                                        <input type='hidden' name='id_pesanan' value='$id_pesanan'>
                                        <input type='hidden' name='id_menu' value='{$m['id_menu']}'>
                                        <input type='number' name='qty' required min='1' max='1000'
                                               style='width:120px' placeholder='1 to 1000'>
                                        <button type='submit' class='btn btn-primary'>Add to Cart</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }

                        echo "</tbody></table>";
                    } else {
                        echo "<div class='alert alert-danger'>No menu found.</div>";
                    }
                    ?>
                </div>

            </div>
        </div>
        <div class="col-md-4 order-md-2 m-1" id="cart-section">
            <div class="container-fluid pt-5 pl-600 pr-6 row mt-3" style="max-width:200%; width:150%;">
                <div class="cart-section">
                    <h3>Cart</h3>

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        if($cartResult && mysqli_num_rows($cartResult) > 0){
                            while($c = mysqli_fetch_assoc($cartResult)){
                                echo "<tr>";
                                echo "<td>{$c['nama_menu']}</td>";
                                echo "<td>{$c['qty']}</td>";
                                echo "<td>Rp " . number_format($c['subtotal'], 0, ',', '.') . "</td>";
                                echo "<td>
                                        <a href='deleteItem.php?id_detail={$c['id_detail']}&id_pesanan=$id_pesanan'
                                           class='btn btn-dark'>Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No items in cart</td></tr>";
                        }
                        ?>

                        </tbody>
                    </table>

                    <hr>

                    <h4>Total : Rp <?= number_format($cart_total, 0, ',', '.'); ?></h4>

                    <?php if($cart_total > 0): ?>
                        <a href="checkout.php?id_pesanan=<?= $id_pesanan; ?>" class="btn btn-success mt-3">Pay Bill</a>
                    <?php else: ?>
                        <p class="mt-3"><b>Add item to cart to proceed</b></p>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<?php include "../inc/dashFooter.php"; ?>
