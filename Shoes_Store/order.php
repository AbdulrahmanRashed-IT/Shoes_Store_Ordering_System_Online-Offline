<?php include('partials_front/menu.php'); ?>

<?php
// Handle form submission first, before any output
if (isset($_POST['submit'])) {
    $shoes = mysqli_real_escape_string($conn, $_POST['shoes']);
    $price = floatval($_POST['price']);
    $qty = intval($_POST['qty']);

    date_default_timezone_set('Asia/Jakarta');
    $total = $price * $qty;
    $order_date = date("Y-m-d H:i:s");
    $status = "Ordered";

    $customer_name = mysqli_real_escape_string($conn, $_POST['full-name']);
    $customer_contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['email']);
    $customer_address = mysqli_real_escape_string($conn, $_POST['address']);

    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $sql2 = "INSERT INTO tbl_order SET
        shoes = '$shoes',
        price = '$price',
        qty = '$qty',
        size = '$size',
        total = '$total',
        order_date = '$order_date',
        status = '$status',
        customer_name = '$customer_name',
        customer_contact = '$customer_contact',
        customer_email = '$customer_email',
        customer_address = '$customer_address'
    ";

    $res2 = mysqli_query($conn, $sql2);

    if ($res2) {
        $_SESSION['order'] = "<div class='success'>Product Ordered Successfully.</div>";
    } else {
        $_SESSION['order'] = "<div class='error'>Failed to order Product.</div>";
    }

    header('Location: ' . SITEURL);
    exit();
}

// Get product information
if (isset($_GET['shoes_id'])) {
    $shoes_id = intval($_GET['shoes_id']);
    $sql = "SELECT * FROM tbl_shoes WHERE id = $shoes_id";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $price = $row['price'];
        $image_name = $row['image_name'];
    } else {
        header('Location: ' . SITEURL);
        exit();
    }
} else {
    header('Location: ' . SITEURL);
    exit();
}
?>

<!-- PRODUCT SEARCH Section Starts Here -->
<section class="product-search">
    <div class="container">
        <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

        <form action="" method="POST" class="order">
            <fieldset>
                <legend>Selected Product</legend>

                <div class="product-menu-img">
                    <?php
                    if ($image_name == "") {
                        echo "<div class='error'>Image not Available.</div>";
                    } else {
                    ?>
                        <img src="<?php echo SITEURL; ?>images/shoes/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                    <?php
                    }
                    ?>
                </div>

                <div class="product-menu-desc">
                    <h3><?php echo $title; ?></h3>
                    <input type="hidden" name="shoes" value="<?php echo $title; ?>">

                    <p class="product-price">Rp<?php echo $price; ?></p>
                    <input type="hidden" name="price" value="<?php echo $price; ?>">

                    <div class="order-label">Quantity</div>
                    <input type="number" name="qty" class="input-responsive" value="1" min="1" required>

                    <div class="order-label">Size</div>
                    <select name="size" class="input-responsive" required>
                        <option value="">Select Size</option>
                        <?php
                        for ($i = 1; $i <= 50; $i++) {
                            echo "<option value=\"$i\">$i</option>";
                        }
                        ?>
                    </select>

                </div>
            </fieldset>

            <fieldset>
                <legend>Delivery Details</legend>

                <div class="order-label">Full Name</div>
                <input type="text" name="full-name" placeholder="Enter your full name" class="input-responsive" required>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="contact" placeholder="E.g. 9843xxxxxx" class="input-responsive" required>

                <div class="order-label">Email</div>
                <input type="email" name="email" placeholder="E.g. hi@vijaythapa.com" class="input-responsive" required>

                <div class="order-label">Address</div>
                <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive" required></textarea>

                <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
            </fieldset>
        </form>
    </div>
</section>
<!-- PRODUCT SEARCH Section Ends Here -->

<?php include('partials_front/footer.php'); ?>